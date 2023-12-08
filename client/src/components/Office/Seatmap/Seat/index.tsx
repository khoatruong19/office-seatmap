import { ROW_LABEL, SEATMAP_COLUMNS_PER_ROW } from "@config/seatmapSize";
import { cn } from "@lib/clsx";

type Props = {
  position: number;
};

const Seat = ({ position }: Props) => {
  const rowLabelIndex = Math.floor(position / SEATMAP_COLUMNS_PER_ROW);
  const rowIndex = position % SEATMAP_COLUMNS_PER_ROW;
  const rowLabel = ROW_LABEL[rowLabelIndex];

  return (
    <div
      draggable
      className={cn(
        "h-12 w-12 bg-secondary text-white z-20 rounded-md flex items-center justify-center shadow-md"
      )}
    >
      {rowLabel + rowIndex}
    </div>
  );
};

export default Seat;
