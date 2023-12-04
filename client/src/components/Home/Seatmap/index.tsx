import { useEffect, useState } from "react";
import OfficeTitle from "./OfficeTitle";
import Room from "./Room";
import Seat from "./Seat";
import { getRow } from "./constants";
import { cn } from "../../../lib/clsx";

const Seatmap = () => {
  const [done, setDone] = useState(false);
  const [selectedCells, setSelectedCells] = useState<string[]>([]);
  const [blocks, setBlocks] = useState<string[][]>([]);
  const [seats, setSeats] = useState<string[]>([]);

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

  useEffect(() => {
    const selectingCells = (e: any) => {
      if (e.shiftKey) {
        const cellId = e.target.id;

        if (!!!cellId.length) return;

        const tempCells = [...selectedCells];

        const existingCellIndex = selectedCells.findIndex(
          (item) => item === cellId
        );

        if (existingCellIndex >= 0) return;

        tempCells.push(cellId);
        console.log({ tempCells });
        setSelectedCells(tempCells);
      } else {
        if (selectedCells.length > 0) setDone(true);
      }
    };

    window.addEventListener("mouseover", selectingCells);

    return () => {
      window.removeEventListener("mouseover", selectingCells);
    };
  }, [selectedCells, done]);

  useEffect(() => {
    if (done) {
      setBlocks((prev) => [...prev, selectedCells]);
      setDone(false);
      setSelectedCells([]);
    }
  }, [done]);

  return (
    <div className="z-1  max-w-7xl w-full mx-auto px-32 py-10 rounded-2xl">
      <OfficeTitle title="Office 101" />

      <div className="relative max-w-4xl w-full mx-auto flex flex-col gap-4 items-start">
        {/* {__renderRows("A")}
        <Room className="left-[262px] h-28 w-24" />
        {__renderRows("B")}
        {__renderRows("C")} */}
        <div className="relative flex items-center gap-3 flex-wrap">
          {new Array(100).fill(0).map((_, idx) => (
            <div
              id={"seat" + idx}
              key={idx}
              className={cn("relative h-12 w-12 border z-20", {
                "border-2 border-red-400":
                  !done && selectedCells.includes("seat" + idx),
                "bg-red-400": seats.includes("seat" + idx),
              })}
              onClick={() => setSeats((prev) => [...prev, "seat" + idx])}
            ></div>
          ))}
          <div className="absolute top-0 left-0 w-full h-full">
            {blocks.map((block, blockIdx) => (
              <div
                key={"blocks" + blockIdx}
                className="absolute top-0 left-0 w-full h-full flex items-center gap-3 flex-wrap"
              >
                {new Array(100).fill(0).map((_, idx) => {
                  const seatIndex = block.findIndex(
                    (item) => item === "seat" + idx
                  );
                  if (seatIndex < 0)
                    return (
                      <div key={Math.random() * 1} className="h-12 w-12" />
                    );

                  const seatNumber = Number(block[seatIndex].split("seat")[1]);

                  const foundNextToBlock = block.find(
                    (item) => Number(item.split("seat")[1]) - seatNumber == 1
                  );
                  if (foundNextToBlock) {
                    const foundBlock = block.find(
                      (item) => Number(item.split("seat")[1]) - seatNumber == 15
                    );
                    return (
                      <div
                        key={Math.random() * 1}
                        className="relative h-12 w-12  z-50"
                      >
                        <div
                          className={cn(
                            "absolute top-0 left-0 w-[124%] h-[100%] bg-secondary",
                            {
                              "h-[124%]": foundBlock,
                            }
                          )}
                        ></div>
                      </div>
                    );
                  }

                  const foundBlock = block.find(
                    (item) => Number(item.split("seat")[1]) - seatNumber == 15
                  );
                  if (foundBlock)
                    return (
                      <div
                        key={Math.random() * 1}
                        className="relative h-12 w-12 z-50"
                      >
                        <div className="absolute top-0 left-0 w-[100%] h-[124%] bg-secondary" />
                      </div>
                    );
                  else
                    return (
                      <div
                        key={Math.random() * 1}
                        className="relative h-12 w-12  z-50"
                      >
                        <div className="absolute top-0 left-0 w-[100%] h-[100%] bg-secondary">
                          <span className="absolute top-0 right-0 w-full h-full text-black text-xs text-center pt-2 break-all">
                            Himalaya
                          </span>
                        </div>
                      </div>
                    );
                })}
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Seatmap;
