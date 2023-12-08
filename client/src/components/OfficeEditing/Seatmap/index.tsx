import { useEffect, useMemo, useState } from "react";
import { SEATMAP_COLUMNS_PER_ROW, SEATMAP_ROWS } from "@config/seatmapSize";
import { cn } from "@lib/clsx";
import { useModalContext } from "@providers/ModalProvider";
import { MODALS } from "@providers/ModalProvider/constants";
import { BlockType, CellType } from "@schema/types";
import Cell from "./Cell";
import {
  useDeleteOfficeMutation,
  useUpdateOfficeMutation,
} from "@stores/office/service";
import Toolbar from "./Toolbar";
import { useNavigate } from "react-router";
import { APP_ROUTES } from "@config/routes";
import OfficeTitleInput from "@components/OfficeEditing/OfficeTitleInput";

type Props = {
  officeName: string;
  officeId: number;
  initBlocks: BlockType[];
  initSeats: CellType[];
  isVisible: boolean;
};

const Seatmap = ({
  officeName,
  officeId,
  initBlocks,
  initSeats,
  isVisible,
}: Props) => {
  const [done, setDone] = useState(false);
  const [selectedCells, setSelectedCells] = useState<CellType[]>([]);
  const [blocks, setBlocks] = useState<BlockType[]>(initBlocks);
  const [seats, setSeats] = useState<CellType[]>(initSeats);
  const [lastSelectingCell, setLastSelectingCell] = useState<CellType | null>(
    null
  );
  const [visible, setVisible] = useState(isVisible);
  const [name, setName] = useState(officeName);

  const navigate = useNavigate();

  const { showModal, closeModal } = useModalContext();
  const [updateOffice] = useUpdateOfficeMutation();
  const [deleteOffice] = useDeleteOfficeMutation();

  useEffect(() => {
    const selectingCells = (e: MouseEvent) => {
      if (!e.target) return;

      if (e.shiftKey) {
        const cellId = (e.target as HTMLDivElement).id;
        if (!!!cellId.length) return;

        const newCellPosition = Number(cellId.split("seat")[1]);
        if (lastSelectingCell) {
          const lastCellPosition = lastSelectingCell.position;
          if (
            Math.abs(newCellPosition - lastCellPosition) !== 1 &&
            Math.abs(newCellPosition - lastCellPosition) !==
              SEATMAP_COLUMNS_PER_ROW
          )
            return;
        }
        setLastSelectingCell({ position: newCellPosition, label: cellId });
        const tempCells = [...selectedCells];
        const existingCellIndex = selectedCells.findIndex(
          (item) => item.label === cellId
        );
        if (existingCellIndex >= 0) return;

        tempCells.push({ label: cellId, position: newCellPosition });
        setSelectedCells(tempCells);
      }
    };

    const doneSelectingCells = (event: KeyboardEvent) => {
      if (event.keyCode === 16 && selectedCells.length > 0) {
        setDone(true);
        setLastSelectingCell(null);
      }
    };
    window.addEventListener("mouseover", selectingCells);
    window.addEventListener("keyup", doneSelectingCells);

    return () => {
      window.removeEventListener("mouseover", selectingCells);
      window.removeEventListener("keyup", doneSelectingCells);
    };
  }, [selectedCells, done, lastSelectingCell]);

  const handleOpenBlockModal = () => showModal(MODALS.CONFIRM, {});

  const handleToggleVisible = () => setVisible((prev) => !prev);

  const handleChangeName = (value: string) => setName(value);

  const handleDeleteOffice = () => {
    const confirmHandler = () => {
      deleteOffice({ id: officeId })
        .then(() => {
          closeModal();
          navigate(APP_ROUTES.HOME);
        })
        .catch(() => {});
    };

    showModal(MODALS.CONFIRM, {
      text: "Are you sure you want to delete this office?",
      confirmHandler,
    });
  };

  const handleSaveSeatmap = () => {
    updateOffice({
      id: officeId,
      name,
      visible,
      seats,
      blocks: JSON.stringify(blocks),
    })
      .then(() => {})
      .catch(() => {});
  };

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
    if (foundNextToBlock) {
      const foundBlock = cells.find(
        (item) => item.position - seatNumber == SEATMAP_COLUMNS_PER_ROW
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
          >
            {seatIndex == 0 && renderBlockName(block.name)}
          </div>
        </div>
      );
    }

    const foundBlock = cells.find(
      (item) => item.position - seatNumber == SEATMAP_COLUMNS_PER_ROW
    );
    if (foundBlock)
      return (
        <div
          onClick={handleOpenBlockModal}
          key={Math.random() * 2}
          className="relative h-12 w-12 z-40"
        >
          <div className="absolute top-0 left-0 w-[100%] h-[125%] bg-primary">
            {seatIndex == 0 && renderBlockName(block.name)}
          </div>
        </div>
      );
    else
      return (
        <div
          onClick={handleOpenBlockModal}
          key={Math.random() * 3}
          className="relative h-12 w-12 z-40"
        >
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

  useEffect(() => {
    if (done) {
      showModal(MODALS.ADD_BLOCK, {
        confirmHandler: (name: string) => {
          setBlocks((prev) => [...prev, { name, cells: selectedCells }]);
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

  return (
    <div className="relative z-1 max-w-7xl w-full mx-auto lg:px-32 py-10 rounded-2xl ">
      <OfficeTitleInput title={name} onChange={handleChangeName} />
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
              else
                return (
                  <Cell
                    key={Math.random() * 4}
                    done={done}
                    position={idx}
                    seats={seats}
                    selectedCells={selectedCells}
                    setSeats={setSeats}
                  />
                );
            })}
        </div>
      </div>
    </div>
  );
};

export default Seatmap;
