import { LogOut, User } from "lucide-react";
import { useLogoutMutation } from "@stores/auth/service";
import { useModalContext } from "@providers/ModalProvider";
import { APP_ROUTES } from "@config/routes";
import { MODALS } from "@providers/ModalProvider/constants";

type Props = {
  close: () => void;
};

const UserDropdown = ({ close }: Props) => {
  const [logout] = useLogoutMutation();
  const { showModal } = useModalContext();

  const handleLogout = async () => {
    try {
      await logout(null);
      window.location.pathname = APP_ROUTES.LOGIN;
    } catch (error) {
      return;
    }
  };

  const handleOpenProfileModal = () => {
    showModal(MODALS.PROFILE, {});
    close();
  };

  return (
    <div className="absolute right-0 top-14 z-20 w-40 bg-white border-2 border-secondary rounded-md">
      <div
        onClick={handleOpenProfileModal}
        className="flex items-center gap-2 px-1.5 py-2 cursor-pointer hover:bg-primary"
      >
        <User />
        <span>Profile</span>
      </div>
      <div
        onClick={handleLogout}
        className="flex items-center gap-2 px-1.5 py-2 cursor-pointer hover:bg-danger"
      >
        <LogOut />
        <span>Log Out</span>
      </div>
    </div>
  );
};

export default UserDropdown;
