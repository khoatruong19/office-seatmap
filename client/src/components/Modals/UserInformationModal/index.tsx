import DefaultAvatar from "@assets/default-avatar.png";
import Button from "@components/Form/Button";
import FieldControl from "@components/Form/FieldControl";
import Label from "@components/Form/Label";
import { useModalContext } from "@providers/ModalProvider";
import { KeyRound, UserIcon } from "lucide-react";
import ClipBackground from "../ModalLayout/ClipBackground";

type Props = {
  avatar?: null | string;
  full_name?: string;
  role?: string;
};

const UserInformationModal = ({
  avatar = null,
  full_name = "",
  role = "",
}: Props) => {
  const { closeModal } = useModalContext();

  return (
    <div className="w-[500px] py-8 font-mono">
      <h1 className="text-3xl font-semibold text-center text-white">
        User Information
      </h1>

      <div className="relative w-40 h-40 rounded-full overflow-hidden mx-auto my-8">
        <img
          src={avatar ?? DefaultAvatar}
          alt=""
          className="absolute top-0 left-0 w-full h-full object-cover"
        />
      </div>

      <div className="max-w-md w-full rounded-md flex flex-col gap-5 items-center justify-center pl-20 pr-8">
        <FieldControl
          field="Fullname"
          name="fullname"
          inputDisabled
          inputValue={full_name}
          icon={<UserIcon color="#fff" />}
          inputClass="text-white"
        />

        <div className="flex flex-col gap-1 w-full">
          <Label field="Role" />
          <div className="flex items-center gap-5 capitalize py-2 rounded-md">
            <KeyRound color="#fff" />
            <span className="text-white">{role}</span>
          </div>
        </div>

        <div className="flex items-center gap-4 pt-20">
          <Button
            type="button"
            onClick={closeModal}
            className="mx-auto block rounded-lg text-tertiary hover:text-black w-fit"
          >
            Close
          </Button>
        </div>
      </div>
      <ClipBackground />
    </div>
  );
};

export default UserInformationModal;
