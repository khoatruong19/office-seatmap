import { SeatType } from "../../../../schema/type";

type Props = {
  seat: SeatType;
};

const Seat = ({ seat }: Props) => {
  const { order, row } = seat;

  return (
    <div
      draggable
      className="w-12 h-12 rounded-md bg-green-300 flex items-center justify-center"
    >
      {row + `${order}`}
    </div>
  );
};

export default Seat;
