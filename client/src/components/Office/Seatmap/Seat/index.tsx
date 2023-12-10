import { SeatType } from "@/schema/types";
import { useSetUserMutation } from "@/stores/seat/service";
import { ROW_LABEL, SEATMAP_COLUMNS_PER_ROW } from "@config/seatmapSize";
import { cn } from "@lib/clsx";
import DefaultAvatar from "@assets/default-avatar.png";

type Props = {
  seat: SeatType;
  officeId: number;
};

const Seat = ({ seat, officeId }: Props) => {
  const { position, avatar, userId } = seat;
  const rowLabelIndex = Math.floor(position / SEATMAP_COLUMNS_PER_ROW);
  const rowIndex = position % SEATMAP_COLUMNS_PER_ROW;
  const rowLabel = ROW_LABEL[rowLabelIndex];

  const [setUser] = useSetUserMutation();

  const handleOnDrop = async (e: React.DragEvent) => {
    const userId = e.dataTransfer.getData("userId");
    if (!userId) return;

    try {
      await setUser({
        id: seat.id,
        user_id: Number(userId),
        office_id: officeId,
      });
    } catch (error) {
      return;
    }
  };

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
  };

  return (
    <div
      onDrop={handleOnDrop}
      onDragOver={handleDragOver}
      className={cn(
        "relative h-12 w-12 bg-tertiary font-semibold text-white z-20 rounded-md flex items-center justify-center shadow-md overflow-hidden"
      )}
    >
      {userId ? (
        <img
          alt=""
          className="absolute top-0 left-0 w-full h-full object-cover"
          src={avatar ?? DefaultAvatar}
        />
      ) : (
        rowLabel + rowIndex
      )}
    </div>
  );
};

export default Seat;
