import { ROW_LABEL, SEATMAP_COLUMNS_PER_ROW } from "@config/seatmapSize";
import { cn } from "@lib/clsx";
import { CellType } from "@schema/types";

type Props = {
  position: number;
  done: boolean;
  seats: CellType[];
  selectedCells: CellType[];
  setSeats: (seats: CellType[]) => void;
};

const Cell = ({ position, done, seats, selectedCells, setSeats }: Props) => {
  const rowLabelIndex = Math.floor(position / SEATMAP_COLUMNS_PER_ROW);
  const rowIndex = position % SEATMAP_COLUMNS_PER_ROW;
  const rowLabel = ROW_LABEL[rowLabelIndex];

  return (
    <div
      id={"seat" + position}
      className={cn(
        "h-12 w-12 border border-secondary z-20 rounded-md flex items-center justify-center",
        {
          "border-2 border-red-500":
            !done && selectedCells.find((item) => item.position === position),
          "bg-red-400": seats.find((item) => item.position === position),
        }
      )}
      onClick={() =>
        !selectedCells.length &&
        setSeats([...seats, { label: rowLabel + rowIndex, position }])
      }
    >
      {rowLabel + rowIndex}
    </div>
  );
};

export default Cell;
