import BackToHomeButton from "@/components/Layout/BackButton";
import useEditingOfficeSeatmap from "@/hooks/useEditingOfficeSeatmap";
import OfficeTitleInput from "@components/OfficeEditing/OfficeTitleInput";
import { SEATMAP_COLUMNS_PER_ROW, SEATMAP_ROWS } from "@config/seatmapSize";
import { BlockType, CellType } from "@schema/types";
import Cell from "./Cell";
import Toolbar from "./Toolbar";
import { v4 as uuid } from "uuid";
import Block from "./Block";

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
                  <div key={uuid()}>
                    {blocks.map((block) => (
                      <Block
                        key={uuid()}
                        block={block}
                        position={idx}
                        deleteBlock={() => handleDeleteBlock(block.id)}
                      />
                    ))}
                  </div>
                );
              return (
                <Cell
                  deleteCell={handleDeleteCell}
                  checkUnDeleteCell={handleCheckUnDeleteCell}
                  key={uuid()}
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
