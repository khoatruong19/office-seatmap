import { SubmitHandler, useForm } from "react-hook-form";
import { ProfileSchema, ProfileSchemaType } from "../../../schema/form";
import { zodResolver } from "@hookform/resolvers/zod";
import Label from "../../Form/Label";
import { KeyRound, Pencil, User } from "lucide-react";
import Input from "../../Form/Input";
import Button from "../../Form/Button";
import DefaultAvatar from "../../../assets/default-avatar.png";
import { useAuth } from "../../../hooks/useAuth";
import { useEffect, useRef, useState } from "react";
import { useModalContext } from "../../../providers/ModalProvider";
import resizeImage from "../../../utils/resizeImage";
import { useUpdateMutation } from "../../../stores/user/service";

const ProfileModal = () => {
  const { user } = useAuth();
  const { closeModal } = useModalContext();
  const [file, setFile] = useState<File | Blob | null>(null);
  const [avatar, setAvatar] = useState(user?.avatar ?? "");

  const fileInputRef = useRef<HTMLInputElement | null>(null);

  const [update, { isLoading }] = useUpdateMutation();

  const {
    register,
    handleSubmit,
    setValue,
    formState: { errors },
  } = useForm<ProfileSchemaType>({
    resolver: zodResolver(ProfileSchema),
  });

  const onSubmit: SubmitHandler<ProfileSchemaType> = (
    value: ProfileSchemaType
  ) => {
    if (!user) return;

    let formData = new FormData();

    formData.append("full_name", value.full_name);

    if (file) {
      resizeImage({ file }, async (resultBlob) => {
        formData.append("file", resultBlob);
        update({ userId: user.id, formData })
          .then(() => closeModal())
          .catch(() => {});
      });
      return;
    }

    update({ userId: user.id, formData })
      .then(() => closeModal())
      .catch(() => {});
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

  const handleCloseModal = () => closeModal();

  useEffect(() => {
    if (!user) return;
    setValue("full_name", user.full_name);
  }, [user]);

  return (
    <div className="w-[500px] py-8 font-mono">
      <h1 className="text-3xl font-semibold text-center">Profile</h1>

      <div
        onClick={handleOpenChooseFile}
        className="relative w-40 h-40 rounded-full overflow-hidden mx-auto hover-opacity mt-5"
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
        className="max-w-md w-full bg-white rounded-md flex flex-col items-center justify-center pl-20 pr-8"
      >
        <div className="flex flex-col gap-1 w-full">
          <Label field="Email" />
          <div className="flex items-center gap-2 bg-primary rounded-md overflow-hidden py-1">
            <User />
            <Input
              disabled
              value={user?.email}
              className="flex-1 disabled:bg-primary"
            />
          </div>
        </div>

        <div className="flex flex-col gap-1 mt-5 w-full">
          <Label field="Fullname" />
          <div className="flex items-center gap-2">
            <Pencil />
            <Input
              register={register}
              placeholder="Password..."
              name="full_name"
              className="flex-1"
            />
          </div>
          {errors.full_name && (
            <span className="text-xs text-red-400 font-semibold">
              {errors.full_name.message}
            </span>
          )}
        </div>

        <div className="flex flex-col gap-1 mt-5 w-full">
          <Label field="Role" />
          <div className="flex items-center gap-5 capitalize">
            <KeyRound />
            <span>{user?.role}</span>
          </div>
        </div>

        <div className="flex items-center gap-4 mt-8">
          <Button
            onClick={handleCloseModal}
            className="mx-auto block rounded-lg text-primary hover:text-secondary w-fit"
          >
            Cancel
          </Button>
          <Button
            disabled={isLoading}
            type="submit"
            className="mx-auto block rounded-lg disabled:bg-primary bg-secondary disabled:cursor-default disabled:hover:opacity-100"
          >
            Save
          </Button>
        </div>
      </form>
    </div>
  );
};

export default ProfileModal;
