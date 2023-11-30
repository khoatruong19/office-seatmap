import OfficeTitle from "./OfficeTitle";
import Room from "./Room";
import Seat from "./Seat";
import { getRow } from "./constants";

const Seatmap = () => {
  const __renderRows = (row: string) => {
    const rows = getRow(row);
    return (
      <div className="w-full flex items-center gap-1">
        {rows.map((seat, index) => {
          return (
            <div key={index} className="flex items-center gap-1">
              {new Array(seat.order - index).fill(0).map((_, idx) => (
                <div key={idx} className="h-12 w-12" />
              ))}
              <Seat key={seat.id + seat.order + seat.row} seat={seat} />
            </div>
          );
        })}
      </div>
    );
  };

  return (
    <div className="z-1">
      <OfficeTitle title="Office 101" />

      <div className="relative max-w-5xl w-full mx-auto flex flex-col gap-4 items-start">
        {__renderRows("A")}
        <Room className="left-[262px] h-28 w-24" />
        {__renderRows("B")}
        {__renderRows("C")}
      </div>
    </div>
  );
};

export default Seatmap;
