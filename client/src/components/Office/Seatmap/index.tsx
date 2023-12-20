import { useMemo } from "react";
import { SEATMAP_COLUMNS_PER_ROW, SEATMAP_ROWS } from "@config/seatmapSize";
import { BlockType, CellType, SeatType } from "@schema/types";
import OfficeTitle from "@components/Office/OfficeTitle";
import Seat from "./Seat";
import BackToHomeButton from "@/components/Layout/BackButton";
import Block from "@/components/OfficeEditing/Seatmap/Block";
import { v4 as uuid } from "uuid";

type Props = {
  officeName: string;
  officeId: number;
  blocks: BlockType[];
  seats: SeatType[];
};

const Seatmap = ({ officeName, officeId, blocks, seats }: Props) => {
  const blockCells: CellType[] = useMemo(() => {
    const cells = ([] as CellType[]).concat(
      ...blocks.map((block) => [...block.cells])
    );

    return cells;
  }, [blocks]);

  return (
    <div className="z-1 max-w-7xl w-full mx-auto lg:px-32 py-10 rounded-2xl ">
      <BackToHomeButton className="top-8 left-56" />
      <OfficeTitle title={officeName} />
      <div className="relative max-w-4xl w-full mx-auto flex flex-col gap-4 items-start scale-50 lg:scale-[0.8] 2xl:scale-100">
        <div className="relative flex items-center gap-3 flex-wrap">
          {new Array(SEATMAP_ROWS * SEATMAP_COLUMNS_PER_ROW)
            .fill(0)
            .map((_, idx) => {
              if (blockCells.find((cell) => cell.position === idx))
                return (
                  <div key={uuid()}>
                    {blocks.map((block) => (
                      <Block
                        key={uuid()}
                        block={block}
                        position={idx}
                        deleteBlock={() => {}}
                      />
                    ))}
                  </div>
                );

              if (!seats.find((seat) => seat.position == idx))
                return <div key={uuid()} className="w-12 h-12" />;

              const seat = seats.find((item) => item.position == idx);

              if (!seat) return <div key={uuid()} className="w-12 h-12" />;

              return <Seat key={idx} seat={seat} officeId={officeId} />;
            })}
        </div>
      </div>
    </div>
  );
};

export default Seatmap;
