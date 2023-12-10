import { useAuth } from "../../../../../../hooks/useAuth";
import { useModalContext } from "../../../../../../providers/ModalProvider";
import { MODALS } from "../../../../../../providers/ModalProvider/constants";
import { UserRole, UserType } from "../../../../../../schema/types";
import DefaultAvatar from "../../../../../../assets/default-avatar.png";

type Props = {
  user: UserType;
};

const UserCard = ({ user }: Props) => {
  const { user: me } = useAuth();
  const { showModal } = useModalContext();

  const handleOpenUpdateModal = () => {
    if (user.id === me!.id) {
      showModal(MODALS.PROFILE, {});
      return;
    }

    if (user.role === UserRole.ADMIN) {
      showModal(MODALS.USER_INFORMATION, { user });
      return;
    }

    showModal(MODALS.UPDATE_USER, { type: "update", user });
  };

  const handleOnDrag = (e: React.DragEvent, user?: UserType) => {
    if (!user) return;

    e.dataTransfer.setData("userId", JSON.stringify(user.id));
  };

  return (
    <div
      onClick={handleOpenUpdateModal}
      className="w-full h-[70px] px-2 flex items-center gap-2 hover-opacity bg-white rounded-2xl mb-1"
    >
      <img
        draggable
        src={user?.avatar ?? DefaultAvatar}
        className="w-12 h-12 rounded-full object-cover shadow-sm"
        alt=""
        onDragStart={(e) => handleOnDrag(e, user)}
      />
      <div>
        <h3 className="font-semibold truncate max-w-[260px] text-secondary">
          {user.full_name}
        </h3>
        <p className="capitalize text-sm font-bold text-[#0081C9]">
          {user.role}
        </p>
      </div>
    </div>
  );
};

export default UserCard;
