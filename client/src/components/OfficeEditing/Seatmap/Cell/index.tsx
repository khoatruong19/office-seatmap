import { cn } from "../../../../lib/clsx";

type Props = {
  idx: number;
  done: boolean;
  seats: string[];
  selectedCells: string[];
  setSeats: (seats: string[]) => void;
};

const Cell = ({ idx, done, seats, selectedCells, setSeats }: Props) => {
  return (
    <div
      id={"seat" + idx}
      key={Math.random() * 1}
      className={cn(
        "relative h-12 w-12 border border-secondary z-20 rounded-md",
        {
          "border-2 border-red-400":
            !done && selectedCells.includes("seat" + idx),
          "bg-red-400": seats.includes("seat" + idx),
        }
      )}
      onClick={() =>
        !selectedCells.length && setSeats([...seats, "seat" + idx])
      }
    ></div>
  );
};

export default Cell;
