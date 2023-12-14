import { DRAG_EVENTS } from "@/config/events";
import { SeatType } from "@/schema/types";
import {
  useRemoveUserMutation,
  useSetUserMutation,
  useSwapUsersMutation,
} from "@/stores/seat/service";
import DefaultAvatar from "@assets/default-avatar.png";
import { ROW_LABEL, SEATMAP_COLUMNS_PER_ROW } from "@config/seatmapSize";
import { cn } from "@lib/clsx";
import { useRef, useState } from "react";
import ContextMenu from "./ContextMenu";
import { useModalContext } from "@/providers/ModalProvider";
import { MODALS } from "@/providers/ModalProvider/constants";
import useCheckAdmin from "@/hooks/useCheckAdmin";

type Props = {
  seat: SeatType;
  officeId: number;
};

const Seat = ({ seat, officeId }: Props) => {
  const { position, avatar, userId } = seat;
  const rowLabelIndex = Math.floor(position / SEATMAP_COLUMNS_PER_ROW);
  const rowIndex = position % SEATMAP_COLUMNS_PER_ROW;
  const rowLabel = ROW_LABEL[rowLabelIndex];

  const [showContextMenu, setShowContextMenu] = useState(false);

  const { showModal } = useModalContext();
  const [setUser] = useSetUserMutation();
  const [removeUser] = useRemoveUserMutation();
  const [swapUsers] = useSwapUsersMutation();

  const seatRef = useRef<HTMLDivElement | null>(null);
  const isAdmin = useCheckAdmin();

  const handleOnDrop = async (e: React.DragEvent) => {
    const userId = e.dataTransfer.getData(DRAG_EVENTS.USER_ID);
    const seatInfoInString = e.dataTransfer.getData(DRAG_EVENTS.SEAT_INFO);
    try {
      if (userId) {
        await setUser({
          id: seat.id,
          user_id: Number(userId),
          office_id: officeId,
        });
        return;
      }

      const { firstSeatId, firstUserId } = JSON.parse(seatInfoInString) as {
        firstSeatId: number;
        firstUserId: number;
      };
      if (firstSeatId === seat.id) return;

      if (!seat.userId) {
        await setUser({
          id: seat.id,
          user_id: Number(firstUserId),
          office_id: officeId,
        });
        await removeUser({ id: firstSeatId });
        return;
      }

      if (!seatRef?.current) return;

      await swapUsers({
        officeId,
        firstSeatId,
        firstUserId,
        secondSeatId: seat.id,
        secondUserId: seat.userId,
      });
    } catch (error) {
      return;
    }
    e.dataTransfer.clearData();
  };

  const handleOnDrag = (e: React.DragEvent, userId?: number) => {
    if (!userId) return;

    e.dataTransfer.setData(
      DRAG_EVENTS.SEAT_INFO,
      JSON.stringify({
        firstSeatId: seat.id,
        firstUserId: userId,
      })
    );
  };

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
  };

  const handleContextMenu = (
    e: React.MouseEvent<HTMLImageElement, MouseEvent>
  ) => {
    e.preventDefault();
    if (!isAdmin) return;
    setShowContextMenu(true);
  };

  const handleOpenUserInformationModal = () =>
    showModal(MODALS.USER_INFORMATION, {
      avatar: seat?.avatar,
      full_name: seat?.full_name,
      role: seat?.role,
    });

  return (
    <div
      ref={seatRef}
      onDrop={handleOnDrop}
      onDragOver={handleDragOver}
      className={cn(
        "relative h-12 w-12 bg-tertiary font-semibold text-white rounded-md flex items-center justify-center shadow-md"
      )}
    >
      {userId ? (
        <img
          draggable={isAdmin}
          onDragStart={(e) => handleOnDrag(e, seat.userId)}
          onContextMenu={handleContextMenu}
          onClick={handleOpenUserInformationModal}
          alt=""
          className="absolute top-0 left-0 w-full h-full rounded-md object-cover cursor-pointer z-40"
          src={avatar ?? DefaultAvatar}
        />
      ) : (
        rowLabel + rowIndex
      )}

      {showContextMenu && (
        <ContextMenu seatId={seat.id} setShowContext={setShowContextMenu} />
      )}
    </div>
  );
};

export default Seat;
