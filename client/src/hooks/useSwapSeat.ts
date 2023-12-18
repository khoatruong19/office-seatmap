import { RootState } from "@/stores";
import { useDispatch, useSelector } from "react-redux";
import { setDragSeat, setTargetSeat } from "@/stores/seat/slice";
import { SeatType } from "@/schema/types";
import { useMemo } from "react";
import { SEATMAP_COLUMNS_PER_ROW } from "@/config/seatmapSize";
import { officeApi } from "@/stores/office/service";

const SEAT_WIDTH = 48;
const SEAT_GAP = 12;

const useSwapSeat = () => {
  const { dragSeat, targetSeat } = useSelector(
    (state: RootState) => state.seat
  );

  const dispatch = useDispatch();
  const handleSetDragSeat = (dragSeat: SeatType) =>
    dispatch(setDragSeat(dragSeat));
  const handleSetTargetSeat = (targetSeat: SeatType) =>
    dispatch(setTargetSeat(targetSeat));
  const handleReset = () => {
    dispatch(setDragSeat(null));
    dispatch(setTargetSeat(null));
  };
  const handleInvalidateSeats = () =>
    dispatch(officeApi.util.invalidateTags(["office"]));

  const newPositions = useMemo(() => {
    if (!dragSeat || !targetSeat) return;

    const dragSeatRowNumber = Math.floor(
      dragSeat.position / SEATMAP_COLUMNS_PER_ROW
    );
    const targetSeatRowNumber = Math.floor(
      targetSeat.position / SEATMAP_COLUMNS_PER_ROW
    );
    const dragSeatColumnNumber = Math.floor(
      dragSeat.position % SEATMAP_COLUMNS_PER_ROW
    );
    const targetSeatColumnNumber = Math.floor(
      targetSeat.position % SEATMAP_COLUMNS_PER_ROW
    );
    const newDragSeatX =
      -(dragSeatColumnNumber - targetSeatColumnNumber) *
      (SEAT_WIDTH + SEAT_GAP);
    const newDragSeatY =
      -(dragSeatRowNumber - targetSeatRowNumber) * (SEAT_WIDTH + SEAT_GAP);
    const newTargetSeatX = -newDragSeatX;
    const newTargetSeatY = -newDragSeatY;

    return {
      newDragSeatPostion: {
        label: dragSeat.label,
        position: { x: newDragSeatX, y: newDragSeatY },
      },
      newTargetSeatPosition: {
        label: targetSeat.label,
        position: { x: newTargetSeatX, y: newTargetSeatY },
      },
    };
  }, [dragSeat, targetSeat]);

  return {
    dragSeat,
    targetSeat,
    newPositions,
    handleSetDragSeat,
    handleSetTargetSeat,
    handleReset,
    handleInvalidateSeats,
  };
};

export default useSwapSeat;
