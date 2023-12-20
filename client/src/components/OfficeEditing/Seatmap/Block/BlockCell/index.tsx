import { cn } from "@/lib/clsx";

type Props = {
  className?: string;
  index: number;
  blockName: string;
  isCorner?: boolean;
  deleteBlock: () => void;
};

const BlockCell = ({
  className = "",
  index,
  blockName,
  isCorner = false,
  deleteBlock,
}: Props) => {
  const renderBlockName = () => (
    <span
      className={
        "absolute top-0.5 right-0 w-[90%] h-full text-black font-semibold text-xs text-center break-all"
      }
    >
      {blockName}
    </span>
  );

  return (
    <div onClick={deleteBlock} className="relative h-12 w-12 z-40">
      <div
        className={cn(
          "absolute top-0 left-0 w-[100%] h-[100%] bg-primary",
          className
        )}
      >
        {index == 0 && renderBlockName()}
      </div>
      {isCorner && (
        <>
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
        </>
      )}
    </div>
  );
};

export default BlockCell;
