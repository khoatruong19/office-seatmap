import { zodResolver } from "@hookform/resolvers/zod";
import { KeyRound, Lock, Pencil, Trash2, UserIcon } from "lucide-react";
import { useEffect, useMemo, useRef, useState } from "react";
import { SubmitHandler, useForm } from "react-hook-form";
import DefaultAvatar from "@assets/default-avatar.png";
import { useModalContext } from "@providers/ModalProvider";
import { UserSchema, UserSchemaType } from "@schema/form";
import { UserType, UserRole, UserEditingModalType } from "@schema/types";
import {
  useCreateUserMutation,
  useDeleteUserMutation,
  useUpdateUserMutation,
} from "@stores/user/service";
import Button from "@components/Form/Button";
import Label from "@components/Form/Label";
import FieldControl from "@components/Form/FieldControl";
import resizeImage from "@utils/resizeImage";
import { MODALS } from "@providers/ModalProvider/constants";
import ClipBackground from "../ModalLayout/ClipBackground";
import { useClickOutside } from "@/hooks/useClickOutside";

type Props = {
  type: UserEditingModalType;
  user?: UserType;
};

const UserEditingModal = ({ type, user }: Props) => {
  const { showModal, closeModal } = useModalContext();
  const [file, setFile] = useState<File | Blob | null>(null);
  const [avatar, setAvatar] = useState("");

  const fileInputRef = useRef<HTMLInputElement | null>(null);
  const containerRef = useRef<HTMLDivElement | null>(null);

  const [create, { isLoading: createLoading }] = useCreateUserMutation();
  const [update, { isLoading: updateLoading }] = useUpdateUserMutation();
  const [deleteUser, { isLoading: deleteLoading }] = useDeleteUserMutation();

  const {
    register,
    handleSubmit,
    setValue,
    reset,
    watch,
    formState: { errors },
  } = useForm<UserSchemaType>({
    resolver: zodResolver(UserSchema),
  });

  const createSubmitHandler = (formData: FormData) => {
    return create(formData);
  };

  const updateSubmitHandler = (formData: FormData) => {
    return update({ id: user!.id, data: formData });
  };

  const onSubmit: SubmitHandler<UserSchemaType> = async (
    values: UserSchemaType
  ) => {
    const submitHandler =
      type === "create" ? createSubmitHandler : updateSubmitHandler;
    const formData = new FormData();
    Object.entries(values).forEach(([key, value]) => {
      formData.append(key, value);
    });
    if (file) {
      resizeImage({ file }, async (resultBlob) => {
        formData.append("file", resultBlob);
        try {
          const data = await submitHandler(formData);
          if ("error" in data) return;
          reset();
          closeModal();
        } catch (error) {
          return;
        }
      });
      return;
    }

    try {
      const data = await submitHandler(formData);
      if ("error" in data) return;
      reset();
      closeModal();
    } catch (error) {
      return;
    }
  };

  const handleOpenChooseFile = () => {
    fileInputRef.current?.click();
  };

  const onChangFile = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (!e.target.files) return;

    const file = e.target.files[0];
    if (!file) return;
    setFile(file);
    setAvatar(URL.createObjectURL(file));
  };

  const handleDeleteUser = () => {
    const deleteHandler = async () => {
      try {
        await deleteUser({ userId: user?.id! });
        closeModal();
      } catch (error) {
        return;
      }
    };
    showModal(MODALS.CONFIRM, {
      confirmHandler: deleteHandler,
      isLoading: deleteLoading,
    });
  };

  useEffect(() => {
    if (!user) return;

    setValue("email", user.email);
    setValue("full_name", user.full_name);
    setValue("role", user.role);
    setAvatar(user.avatar ?? "");
  }, [user]);

  const isInformationChanged = useMemo(() => {
    if (!user) return false;

    const textInfoChange =
      watch("full_name") !== user.full_name ||
      watch("email") !== user.email ||
      watch("role") !== user.role;
    if (file || textInfoChange) return true;

    return false;
  }, [watch("full_name"), watch("email"), watch("role"), file]);

  useClickOutside(containerRef, () => reset());

  return (
    <div ref={containerRef} className="relative w-[500px] py-8 font-mono">
      {type === "update" && (
        <Button
          onClick={handleDeleteUser}
          className="absolute right-0 top-5 text-white"
        >
          <Trash2 strokeWidth={2.5} fill="#FF6969" />
        </Button>
      )}

      <h1 className="text-3xl font-semibold text-center text-white">
        {type === "create" ? "Create User" : "Update User"}
      </h1>

      <div
        onClick={handleOpenChooseFile}
        className="relative w-40 h-40 rounded-full overflow-hidden mx-auto hover-opacity mt-8"
      >
        <img
          src={!!avatar ? avatar : DefaultAvatar}
          alt=""
          className="absolute top-0 left-0 w-full h-full object-cover"
        />
        <input
          ref={fileInputRef}
          onChange={onChangFile}
          type="file"
          hidden
          accept=".png, .jpg, .jpeg"
        />
      </div>

      <form
        onSubmit={handleSubmit(onSubmit)}
        className="max-w-md w-full rounded-md flex flex-col gap-5 items-center justify-center pl-20 pr-8"
      >
        <FieldControl
          field="Email"
          errors={errors}
          name="email"
          placeholder="Email..."
          register={register}
          icon={<UserIcon color="#fff" />}
          inputClass="text-white"
        />

        {type === "create" && (
          <FieldControl
            field="Password"
            errors={errors}
            name="password"
            placeholder="Password..."
            register={register}
            icon={<Lock color="#fff" />}
            inputClass="text-white"
          />
        )}

        <FieldControl
          field="Fullname"
          errors={errors}
          name="full_name"
          placeholder="Fullname..."
          register={register}
          icon={<Pencil color="#fff" />}
          inputClass="text-white"
        />

        <div className="flex flex-col gap-1 w-full">
          <Label field="Role" />
          <div className="flex items-center gap-5 capitalize">
            <KeyRound color="#fff" />
            <select
              {...register("role")}
              className="capitalize focus:outline-0 bg-transparent text-white font-semibold"
              defaultValue={UserRole.USER}
            >
              <option value={UserRole.ADMIN}>Admin</option>
              <option value={UserRole.USER}>User</option>
            </select>
          </div>
        </div>

        <div className="flex items-center gap-4 mt-10 pt-5">
          <Button
            type="button"
            onClick={closeModal}
            className="mx-auto block rounded-lg  text-tertiary hover:text-black  w-fit"
          >
            Cancel
          </Button>
          <Button
            disabled={
              (type == "update" && !isInformationChanged) ||
              createLoading ||
              updateLoading
            }
            type="submit"
            className="mx-auto block rounded-lg disabled:bg-primary text-white bg-tertiary disabled:cursor-default disabled:hover:opacity-100"
          >
            Save
          </Button>
        </div>
      </form>
      <ClipBackground />
    </div>
  );
};

export default UserEditingModal;
