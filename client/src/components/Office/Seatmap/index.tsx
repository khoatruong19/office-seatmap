import { useMemo } from "react";
import { SEATMAP_COLUMNS_PER_ROW, SEATMAP_ROWS } from "@config/seatmapSize";
import { cn } from "@lib/clsx";
import { BlockType, CellType, SeatType } from "@schema/types";
import OfficeTitle from "@components/Office/OfficeTitle";
import Seat from "./Seat";
import BackToHomeButton from "@/components/Layout/BackToHomeButton";

type Props = {
  officeName: string;
  officeId: number;
  blocks: BlockType[];
  seats: SeatType[];
};

const Seatmap = ({ officeName, officeId, blocks, seats }: Props) => {
  const renderBlockName = (name: string) => (
    <span
      className={
        "absolute top-0.5 right-0 w-[90%] h-full text-secondary font-bold text-xs text-center break-all"
      }
    >
      {name}
    </span>
  );

  const renderBlocks = (block: BlockType, position: number) => {
    const { cells } = block;
    const seatIndex = cells.findIndex((item) => item.position === position);
    if (seatIndex < 0) return null;

    const seatNumber = cells[seatIndex].position;
    const foundNextToBlock = cells.find(
      (item) =>
        item.position - seatNumber == 1 &&
        seatNumber % SEATMAP_COLUMNS_PER_ROW !== SEATMAP_COLUMNS_PER_ROW - 1
    );
    const foundBelowBlock = cells.find(
      (item) => item.position - seatNumber == SEATMAP_COLUMNS_PER_ROW
    );
    const foundRightBelowBlock = cells.find(
      (item) => item.position - seatNumber == SEATMAP_COLUMNS_PER_ROW + 1
    );
    if (foundBelowBlock && foundNextToBlock && !foundRightBelowBlock)
      return (
        <div key={Math.random() * 1} className="relative h-12 w-12 z-40">
          <div
            className={cn("absolute top-0 left-0 w-[100%] h-[100%] bg-primary")}
          >
            {seatIndex == 0 && renderBlockName(block.name)}
          </div>
          <div
            className={cn(
              "absolute top-0 left-[100%] w-[30%] h-[100%] bg-primary"
            )}
          />
          <div
            className={cn(
              "absolute top-[100%] left-0 w-[100%] h-[30%] bg-primary"
            )}
          />
        </div>
      );

    if (foundNextToBlock) {
      return (
        <div key={Math.random() * 1} className="relative h-12 w-12 z-40">
          <div
            className={cn(
              "absolute top-0 left-0 w-[125%] h-[100%] bg-primary",
              {
                "h-[125%]": foundBelowBlock,
              }
            )}
          >
            {seatIndex == 0 && renderBlockName(block.name)}
          </div>
        </div>
      );
    }
    if (foundBelowBlock)
      return (
        <div key={Math.random() * 2} className="relative h-12 w-12 z-40">
          <div className="absolute top-0 left-0 w-[100%] h-[125%] bg-primary">
            {seatIndex == 0 && renderBlockName(block.name)}
          </div>
        </div>
      );

    return (
      <div key={Math.random() * 3} className="relative h-12 w-12 z-40">
        <div className="absolute top-0 left-0 w-[100%] h-[100%] bg-primary">
          {seatIndex == 0 && renderBlockName(block.name)}
        </div>
      </div>
    );
  };

  const blockCells: CellType[] = useMemo(() => {
    const cells = ([] as CellType[]).concat(
      ...blocks.map((block) => [...block.cells])
    );

    return cells;
  }, [blocks]);

  return (
    <div className="z-1 max-w-7xl w-full mx-auto lg:px-32 py-10 rounded-2xl ">
      <BackToHomeButton className="top-7 left-32" />
      <OfficeTitle title={officeName} />
      <div className="relative max-w-4xl w-full mx-auto flex flex-col gap-4 items-start scale-50 lg:scale-[0.8] 2xl:scale-100">
        <div className="relative flex items-center gap-3 flex-wrap">
          {new Array(SEATMAP_ROWS * SEATMAP_COLUMNS_PER_ROW)
            .fill(0)
            .map((_, idx) => {
              if (blockCells.find((cell) => cell.position === idx))
                return (
                  <div key={Math.random() * 4}>
                    {blocks.map((block) => renderBlocks(block, idx))}
                  </div>
                );

              if (!seats.find((seat) => seat.position == idx))
                return <div key={Math.random() * 4} className="w-12 h-12" />;

              const seat = seats.find((item) => item.position == idx);

              if (!seat)
                return <div key={Math.random() * 4} className="w-12 h-12" />;

              return <Seat key={idx} seat={seat} officeId={officeId} />;
            })}
        </div>
      </div>
    </div>
  );
};

export default Seatmap;
