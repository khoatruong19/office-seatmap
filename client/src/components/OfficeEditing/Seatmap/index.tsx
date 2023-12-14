import BackToHomeButton from "@/components/Layout/BackButton";
import useEditingOfficeSeatmap from "@/hooks/useEditingOfficeSeatmap";
import OfficeTitleInput from "@components/OfficeEditing/OfficeTitleInput";
import { SEATMAP_COLUMNS_PER_ROW, SEATMAP_ROWS } from "@config/seatmapSize";
import { cn } from "@lib/clsx";
import { BlockType, CellType } from "@schema/types";
import Cell from "./Cell";
import Toolbar from "./Toolbar";

type Props = {
  officeName: string;
  officeId: number;
  initBlocks: BlockType[];
  cells: CellType[];
  isVisible: boolean;
};

const Seatmap = ({
  officeName,
  officeId,
  initBlocks,
  cells,
  isVisible,
}: Props) => {
  const {
    name,
    blockCells,
    blocks,
    done,
    seats,
    selectedCells,
    visible,
    handleAddSeat,
    handleDeleteCell,
    handleToggleVisible,
    handleCheckUnDeleteCell,
    handleChangeName,
    handleDeleteOffice,
    handleDeleteBlock,
    handleSaveSeatmap,
  } = useEditingOfficeSeatmap({
    cells,
    initBlocks,
    isVisible,
    officeId,
    officeName,
  });

  const renderBlockName = (name: string) => (
    <span
      className={
        "absolute top-0.5 right-0 w-[90%] h-full text-black font-semibold text-xs text-center break-all"
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
        <div
          onClick={() => handleDeleteBlock(block.id)}
          key={Math.random() * 1}
          className="relative h-12 w-12 z-40"
        >
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
        <div
          onClick={() => handleDeleteBlock(block.id)}
          key={Math.random() * 1}
          className="relative h-12 w-12 z-40"
        >
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
        <div
          onClick={() => handleDeleteBlock(block.id)}
          key={Math.random() * 2}
          className="relative h-12 w-12 z-40"
        >
          <div className="absolute top-0 left-0 w-[100%] h-[125%] bg-primary">
            {seatIndex == 0 && renderBlockName(block.name)}
          </div>
        </div>
      );

    return (
      <div
        onClick={() => handleDeleteBlock(block.id)}
        key={Math.random() * 3}
        className="relative h-12 w-12 z-40"
      >
        <div className="absolute top-0 left-0 w-[100%] h-[100%] bg-primary">
          {seatIndex == 0 && renderBlockName(block.name)}
        </div>
      </div>
    );
  };

  return (
    <div className="relative z-1 max-w-7xl w-full mx-auto lg:px-32 py-10 rounded-2xl ">
      <OfficeTitleInput title={name} onChange={handleChangeName} />
      <BackToHomeButton className="top-[-10px] left-[-100px]" />
      <div className="absolute right-32 top-52">
        <Toolbar
          handleDeleteOffice={handleDeleteOffice}
          handleSaveSeatmap={handleSaveSeatmap}
          handleToggleVisible={handleToggleVisible}
          visible={visible}
          officeId={officeId}
        />
      </div>
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
              return (
                <Cell
                  deleteCell={handleDeleteCell}
                  checkUnDeleteCell={handleCheckUnDeleteCell}
                  key={Math.random() * 4}
                  done={done}
                  position={idx}
                  seats={seats}
                  selectedCells={selectedCells}
                  addSeat={handleAddSeat}
                />
              );
            })}
        </div>
      </div>
    </div>
  );
};

export default Seatmap;
