import { cn } from "../../../../lib/clsx";
import { CellType } from "../../../../schema/types";

type Props = {
  order: number;
  done: boolean;
  seats: CellType[];
  selectedCells: CellType[];
  setSeats: (seats: CellType[]) => void;
};

const Cell = ({ order, done, seats, selectedCells, setSeats }: Props) => {
  return (
    <div
      id={"seat" + order}
      className={cn(
        "relative h-12 w-12 border border-secondary z-20 rounded-md",
        {
          "border-2 border-red-400":
            !done && selectedCells.find((item) => item.order === order),
          "bg-red-400": seats.find((item) => item.order === order),
        }
      )}
      onClick={() =>
        !selectedCells.length &&
        setSeats([...seats, { id: "seat" + order, order }])
      }
    ></div>
  );
};

export default Cell;
