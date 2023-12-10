import { useEffect, useMemo, useRef, useState } from "react";
import { SubmitHandler, useForm } from "react-hook-form";
import { ProfileSchema, ProfileSchemaType } from "@schema/form";
import { zodResolver } from "@hookform/resolvers/zod";
import Button from "@components/Form/Button";
import Label from "@components/Form/Label";
import FieldControl from "@components/Form/FieldControl";
import { KeyRound, Pencil, UserIcon } from "lucide-react";
import DefaultAvatar from "@assets/default-avatar.png";
import { useAuth } from "@hooks/useAuth";
import { useModalContext } from "@providers/ModalProvider";
import resizeImage from "@utils/resizeImage";
import {
  useUpdateProfileMutation,
  useUploadAvatarMutation,
} from "@stores/user/service";
import { UserType } from "@schema/types";
import ClipBackground from "../ModalLayout/ClipBackground";

const ProfileModal = () => {
  const { user } = useAuth();
  const { closeModal } = useModalContext();
  const [file, setFile] = useState<File | Blob | null>(null);
  const [avatar, setAvatar] = useState(user?.avatar ?? "");

  const fileInputRef = useRef<HTMLInputElement | null>(null);

  const [upload, { isLoading: uploadLoading }] = useUploadAvatarMutation();
  const [update, { isLoading: updateLoading }] = useUpdateProfileMutation();

  const {
    register,
    handleSubmit,
    setValue,
    watch,
    formState: { errors },
  } = useForm<ProfileSchemaType>({
    resolver: zodResolver(ProfileSchema),
  });

  const onSubmit: SubmitHandler<ProfileSchemaType> = (
    values: ProfileSchemaType
  ) => {
    if (!user) return;

    const formData = new FormData();
    formData.append("full_name", values.full_name);
    if (file) {
      resizeImage({ file }, async (resultBlob) => {
        formData.append("file", resultBlob);
        upload({ userId: user.id, formData })
          .then(() => setFile(null))
          .catch(() => {});
      });
    }

    let informationChanged = false;
    for (const [key, value] of Object.entries(values)) {
      if (user[key as keyof UserType] != value) informationChanged = true;
    }
    if (informationChanged)
      update({ userId: user.id, ...values })
        .then(() => !file && closeModal())
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

  useEffect(() => {
    if (!user) return;

    setValue("full_name", user.full_name);
  }, [user, setValue]);

  const isInformationChanged = useMemo(() => {
    return file || watch("full_name") != user?.full_name;
  }, [watch("full_name"), file, user]);

  return (
    <div className="w-[500px] py-8 font-mono">
      <h1 className="text-3xl font-semibold text-center text-white">Profile</h1>

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
        className="max-w-md w-full bg-transparent rounded-md flex flex-col items-center justify-center pl-20 pr-8"
      >
        <FieldControl
          field="Email"
          type="email"
          name="email"
          inputDisabled
          inputValue={user?.email}
          icon={<UserIcon color="#fff" />}
          inputClass="text-white/70"
        />

        <FieldControl
          field="Fullname"
          errors={errors}
          name="full_name"
          placeholder="Fullname..."
          register={register}
          icon={<Pencil color="#fff" />}
          containerClass="mt-5 text-white"
          inputClass="font-semibold"
        />

        <div className="flex flex-col gap-1 mt-5 w-full">
          <Label field="Role" />
          <div className="flex items-center gap-5 capitalize py-2 rounded-md">
            <KeyRound color="#fff" />
            <span className="text-white/70">{user?.role}</span>
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
            disabled={!isInformationChanged || uploadLoading || updateLoading}
            type="submit"
            className="mx-auto block rounded-lg text-white disabled:bg-primary bg-tertiary disabled:cursor-default disabled:hover:opacity-100"
          >
            Save
          </Button>
        </div>
      </form>
      <ClipBackground />
    </div>
  );
};

export default ProfileModal;
