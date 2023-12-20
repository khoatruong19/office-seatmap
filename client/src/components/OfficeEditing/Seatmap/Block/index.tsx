import { BlockType } from "@/schema/types";
import BlockCell from "./BlockCell";
import { v4 as uuid } from "uuid";
import { SEATMAP_COLUMNS_PER_ROW } from "@/config/seatmapSize";
import { cn } from "@/lib/clsx";

type Props = {
  block: BlockType;
  position: number;
  deleteBlock: () => void;
};

const Block = ({ block, position, deleteBlock }: Props) => {
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
      <BlockCell
        key={uuid()}
        className={cn("w-[125%]", {
          "h-[125%]": foundBelowBlock,
        })}
        blockName={block.name}
        index={seatIndex}
        isCorner
        deleteBlock={deleteBlock}
      />
    );
  if (foundNextToBlock) {
    return (
      <BlockCell
        key={uuid()}
        className={cn("w-[125%]", {
          "h-[125%]": foundBelowBlock,
        })}
        blockName={block.name}
        index={seatIndex}
        deleteBlock={deleteBlock}
      />
    );
  }

  if (foundBelowBlock)
    return (
      <BlockCell
        key={uuid()}
        className="h-[125%]"
        blockName={block.name}
        index={seatIndex}
        deleteBlock={deleteBlock}
      />
    );

  return (
    <BlockCell
      key={uuid()}
      blockName={block.name}
      index={seatIndex}
      deleteBlock={deleteBlock}
    />
  );
};

export default Block;
