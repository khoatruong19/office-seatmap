import { ROW_LABEL, SEATMAP_COLUMNS_PER_ROW } from "@config/seatmapSize";
import { cn } from "@lib/clsx";
import { CellType } from "@schema/types";
import { useMemo } from "react";

type Props = {
  position: number;
  done: boolean;
  seats: CellType[];
  selectedCells: CellType[];
  addSeat: (seats: CellType) => void;
  deleteCell: (seat: CellType) => void;
  checkUnDeleteCell: (seat: CellType) => void;
};

const Cell = ({
  position,
  done,
  seats,
  selectedCells,
  addSeat,
  deleteCell,
  checkUnDeleteCell,
}: Props) => {
  const rowLabelIndex = Math.floor(position / SEATMAP_COLUMNS_PER_ROW);
  const rowIndex = position % SEATMAP_COLUMNS_PER_ROW;
  const rowLabel = ROW_LABEL[rowLabelIndex];
  const label = rowLabel + rowIndex;

  const isFound = useMemo(() => {
    return !!seats.find((item) => item.position === position);
  }, [seats, deleteCell]);

  const handleOnClick = () => {
    if (isFound) {
      deleteCell({ label, position });
      return;
    }

    if (!isFound && !selectedCells.length) {
      addSeat({ label: rowLabel + rowIndex, position });
      checkUnDeleteCell({ label, position });
    }
  };

  return (
    <div
      data-testid={"office-cell"}
      id={"seat" + position}
      className={cn(
        "h-12 w-12 border border-secondary z-20 rounded-md flex items-center justify-center",
        {
          "border-2 border-red-500":
            !done && selectedCells.find((item) => item.position === position),
          "bg-red-400": isFound,
        }
      )}
      onClick={handleOnClick}
    >
      {label}
    </div>
  );
};

export default Cell;
