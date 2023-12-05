import { useEffect, useMemo, useState } from "react";
import { SEATMAP_COLUMNS_PER_ROW } from "../../../config/seatmapSize";
import { cn } from "../../../lib/clsx";
import { useModalContext } from "../../../providers/ModalProvider";
import { MODALS } from "../../../providers/ModalProvider/constants";
import { BlockType, CellType } from "../../../schema/types";
import OfficeTitle from "../../Office/OfficeTitle";
import Cell from "./Cell";

const Seatmap = () => {
  const [done, setDone] = useState(false);
  const [selectedCells, setSelectedCells] = useState<CellType[]>([]);
  const [blocks, setBlocks] = useState<BlockType[]>([]);
  const [seats, setSeats] = useState<CellType[]>([]);

  const { showModal, closeModal } = useModalContext();

  useEffect(() => {
    const selectingCells = (e: any) => {
      if (e.shiftKey) {
        const cellId = e.target.id;

        if (!!!cellId.length) return;

        const lastCell = selectedCells[selectedCells.length - 1];
        const newCellOrder = Number(cellId.split("seat")[1]);

        if (lastCell) {
          const lastCellOrder = lastCell.order;
          if (
            !(
              Math.abs(newCellOrder - lastCellOrder) == 1 ||
              Math.abs(newCellOrder - lastCellOrder) == SEATMAP_COLUMNS_PER_ROW
            )
          )
            return;
        }

        const tempCells = [...selectedCells];

        const existingCellIndex = selectedCells.findIndex(
          (item) => item.id === cellId
        );

        if (existingCellIndex >= 0) return;

        tempCells.push({ id: cellId, order: newCellOrder });
        setSelectedCells(tempCells);
      }
    };

    const doneSelectingCells = (event: any) => {
      if (event.keyCode === 16 && selectedCells.length > 0) {
        setDone(true);
      }
    };

    window.addEventListener("mouseover", selectingCells);
    window.addEventListener("keyup", doneSelectingCells);

    return () => {
      window.removeEventListener("mouseover", selectingCells);
      window.removeEventListener("keyup", doneSelectingCells);
    };
  }, [selectedCells, done]);

  useEffect(() => {
    if (done) {
      showModal(MODALS.CONFIRM, {
        text: "Are you sure you want to create the block here?",
        confirmHandler: () => {
          setBlocks((prev) => [
            ...prev,
            { name: "sdhsdj", cells: selectedCells },
          ]);
          setSelectedCells([]);
          closeModal();
        },
        cancelHandler: () => {
          setSelectedCells([]);
        },
      });
      setDone(false);
    }
  }, [done]);

  const handleOpenBlockModal = () => showModal(MODALS.CONFIRM, {});

  const renderBlocks = (block: BlockType, order: number) => {
    const { cells } = block;

    const seatIndex = cells.findIndex((item) => item.id === "seat" + order);

    if (seatIndex < 0) return null;

    const seatNumber = Number(cells[seatIndex].id.split("seat")[1]);

    const foundNextToBlock = cells.find((item) => item.order - seatNumber == 1);
    if (foundNextToBlock) {
      const foundBlock = cells.find(
        (item) => item.order - seatNumber == SEATMAP_COLUMNS_PER_ROW
      );

      return (
        <div
          onClick={handleOpenBlockModal}
          key={Math.random() * 1}
          className="relative h-12 w-12 z-40"
        >
          <div
            className={cn(
              "absolute top-0 left-0 w-[125%] h-[100%] bg-primary",
              {
                "h-[125%]": foundBlock,
              }
            )}
          ></div>
        </div>
      );
    }

    const foundBlock = cells.find(
      (item) => item.order - seatNumber == SEATMAP_COLUMNS_PER_ROW
    );
    if (foundBlock)
      return (
        <div
          onClick={handleOpenBlockModal}
          key={Math.random() * 1}
          className="relative h-12 w-12 z-40"
        >
          <div className="absolute top-0 left-0 w-[100%] h-[125%] bg-primary"></div>
        </div>
      );
    else
      return (
        <div
          onClick={handleOpenBlockModal}
          key={Math.random() * 1}
          className="relative h-12 w-12 z-40"
        >
          <div className="absolute top-0 left-0 w-[100%] h-[100%] bg-primary">
            <span
              id="asdasds"
              className={cn(
                "absolute bottom-5 right-0 w-[100px] h-full text-black font-semibold text-xs text-center pt-3 break-all",
                ""
              )}
            >
              {block.name}
            </span>
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
      <OfficeTitle title="Office 101" />

      <div className="relative max-w-4xl w-full mx-auto flex flex-col gap-4 items-start scale-50 lg:scale-[0.8] 2xl:scale-100">
        <div className="relative flex items-center gap-3 flex-wrap">
          {new Array(100).fill(0).map((_, idx) => (
            <>
              {blockCells.find((cell) => cell.order === idx) ? (
                <>{blocks.map((block) => renderBlocks(block, idx))}</>
              ) : (
                <Cell
                  key={Math.random() * 1}
                  done={done}
                  order={idx}
                  seats={seats}
                  selectedCells={selectedCells}
                  setSeats={setSeats}
                />
              )}
            </>
          ))}
        </div>
      </div>
    </div>
  );
};

export default Seatmap;
