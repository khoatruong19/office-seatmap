import { KeyRound, Pencil, UserIcon } from "lucide-react";
import DefaultAvatar from "@assets/default-avatar.png";
import { useModalContext } from "@providers/ModalProvider";
import { UserType } from "@schema/types";
import Button from "@components/Form/Button";
import FieldControl from "@components/Form/FieldControl";
import Label from "@components/Form/Label";
import ClipBackground from "../ModalLayout/ClipBackground";

type Props = {
  user?: UserType;
};

const AdminInformationModal = ({ user }: Props) => {
  const { closeModal } = useModalContext();

  return (
    <div className="w-[500px] py-8 font-mono">
      <h1 className="text-3xl font-semibold text-center text-white">
        Admin Information
      </h1>

      <div className="relative w-40 h-40 rounded-full overflow-hidden mx-auto mt-8">
        <img
          src={user?.avatar ?? DefaultAvatar}
          alt=""
          className="absolute top-0 left-0 w-full h-full object-cover"
        />
      </div>

      <div className="max-w-md w-full rounded-md flex flex-col gap-5 items-center justify-center pl-20 pr-8">
        <FieldControl
          field="Email"
          name="email"
          inputDisabled
          inputValue={user?.email}
          icon={<UserIcon color="#fff" />}
          inputClass="text-white/70"
        />

        <FieldControl
          field="Fullname"
          name="full_name"
          inputDisabled
          inputValue={user?.full_name}
          icon={<Pencil color="#fff" />}
          inputClass="text-white/70"
        />

        <div className="flex flex-col gap-1 w-full">
          <Label field="Role" />
          <div className="flex items-center gap-5 capitalize py-2 rounded-md">
            <KeyRound color="#fff" />
            <span className="text-white/70">{user?.role}</span>
          </div>
        </div>

        <div className="flex items-center gap-4 mt-5">
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

export default AdminInformationModal;
