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
      className="w-full h-16 px-2 border-b flex items-center gap-2 bg-white hover-opacity"
    >
      <img
        draggable
        src={user?.avatar ?? DefaultAvatar}
        className="w-12 h-12 rounded-full object-cover"
        alt=""
        onDragStart={(e) => handleOnDrag(e, user)}
      />
      <div>
        <h3 className="text-lg font-semibold truncate max-w-[260px]">
          {user.full_name}
        </h3>
        <p className="capitalize text-sm">{user.role}</p>
      </div>
    </div>
  );
};

export default UserCard;
