import { SeatType } from "@/schema/types";
import {
  useRemoveUserMutation,
  useSetUserMutation,
  useSwapUsersMutation,
} from "@/stores/seat/service";
import { ROW_LABEL, SEATMAP_COLUMNS_PER_ROW } from "@config/seatmapSize";
import { cn } from "@lib/clsx";
import DefaultAvatar from "@assets/default-avatar.png";
import { DRAG_EVENTS } from "@/config/events";
import { animated, useSpring } from "@react-spring/web";
import { useRef } from "react";

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
  const [removeUser] = useRemoveUserMutation();
  const [swapUsers] = useSwapUsersMutation();
  const seatRef = useRef<HTMLDivElement | null>(null);

  const [{ x, y }, api] = useSpring(() => ({
    x: 0,
    y: 0,
  }));

  const handleOnDrop = async (e: React.DragEvent) => {
    const userId = e.dataTransfer.getData(DRAG_EVENTS.USER_ID);

    if (userId) {
      try {
        await setUser({
          id: seat.id,
          user_id: Number(userId),
          office_id: officeId,
        });
      } catch (error) {
        return;
      }
      return;
    }

    const seatInfoInString = e.dataTransfer.getData(DRAG_EVENTS.SEAT_INFO);
    try {
      const { firstSeatId, firstUserId, firstX, firstY } = JSON.parse(
        seatInfoInString
      ) as {
        firstSeatId: number;
        firstUserId: number;
        firstX: number;
        firstY: number;
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
      console.log(
        firstX,
        firstY,
        seatRef?.current?.offsetLeft,
        seatRef?.current?.offsetTop
      );
      if (!seatRef?.current) return;
      await swapUsers({
        firstSeatId,
        firstUserId,
        secondSeatId: seat.id,
        secondUserId: seat.userId,
      });

      // api({
      //   x: firstX - seatRef?.current?.offsetLeft,
      //   y: firstY - seatRef?.current?.offsetTop,
      // });
    } catch (error) {
      return;
    }
  };

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault();
    e.dataTransfer.setData(
      DRAG_EVENTS.NEW_SEAT_INFO,
      JSON.stringify({
        secondX: seatRef?.current?.offsetTop,
        secondY: seatRef?.current?.offsetLeft,
      })
    );
    console.log(seatRef?.current?.offsetTop, seatRef?.current?.offsetLeft);
  };

  const handleOnDrag = (e: React.DragEvent, userId?: number) => {
    if (!userId) return;
    console.log(seatRef?.current?.offsetTop);

    e.dataTransfer.setData(
      DRAG_EVENTS.SEAT_INFO,
      JSON.stringify({
        firstSeatId: seat.id,
        firstUserId: userId,
        firstX: seatRef?.current?.offsetLeft,
        firstY: seatRef?.current?.offsetTop,
      })
    );
  };

  const handleDragEnd = (e: React.DragEvent) => {
    e.preventDefault();
    console.log(e.dataTransfer.getData(DRAG_EVENTS.NEW_SEAT_INFO));
    // api({
    //   x: 0,
    //   y: -60,
    // });
  };

  return (
    <div
      ref={seatRef}
      onDrop={handleOnDrop}
      onDragEnd={handleDragEnd}
      onDragOver={handleDragOver}
      className={cn(
        "relative h-12 w-12 bg-tertiary font-semibold text-white rounded-md flex items-center justify-center shadow-md"
      )}
    >
      {userId ? (
        <animated.div
          draggable
          onDragStart={(e) => handleOnDrag(e, seat.userId)}
          style={{ x, y, zIndex: 9999 }}
          className={cn(
            "relative h-full w-full  z-20 rounded-md overflow-hidden"
          )}
        >
          <img
            alt=""
            className="absolute top-0 left-0 w-full h-full object-cover cursor-pointer"
            src={avatar ?? DefaultAvatar}
          />
        </animated.div>
      ) : (
        rowLabel + rowIndex
      )}
    </div>
  );
};

export default Seat;
