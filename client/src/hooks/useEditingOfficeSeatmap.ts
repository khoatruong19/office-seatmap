import { APP_ROUTES } from "@/config/routes";
import { SEATMAP_COLUMNS_PER_ROW } from "@/config/seatmapSize";
import { useModalContext } from "@/providers/ModalProvider";
import { MODALS } from "@/providers/ModalProvider/constants";
import { BlockType, CellType } from "@/schema/types";
import {
  useDeleteOfficeMutation,
  useUpdateOfficeMutation,
} from "@/stores/office/service";
import { useEffect, useMemo, useState } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { v4 as uuid } from "uuid";

const MAX_OFFICE_NAME_LENGTH = 100;

type Props = {
  officeName: string;
  officeId: number;
  initBlocks: BlockType[];
  cells: CellType[];
  isVisible: boolean;
};

const useEditingOfficeSeatmap = (props: Props) => {
  const { initBlocks, cells, isVisible, officeName, officeId } = props;

  const [selectedCells, setSelectedCells] = useState<CellType[]>([]);
  const [blocks, setBlocks] = useState<BlockType[]>(initBlocks);
  const [seats, setSeats] = useState<CellType[]>(cells);
  const [lastSelectingCell, setLastSelectingCell] = useState<CellType | null>(
    null
  );
  const [visible, setVisible] = useState(isVisible);
  const [name, setName] = useState(officeName);
  const [deletedCells, setDeletedCells] = useState<CellType[]>([]);
  const [done, setDone] = useState(false);
  const navigate = useNavigate();

  const { showModal, closeModal } = useModalContext();
  const [updateOffice] = useUpdateOfficeMutation();
  const [deleteOffice] = useDeleteOfficeMutation();

  const blockCells: CellType[] = useMemo(() => {
    const cells = ([] as CellType[]).concat(
      ...blocks.map((block) => [...block.cells])
    );

    return cells;
  }, [blocks]);

  const handleToggleVisible = () => setVisible((prev) => !prev);

  const handleChangeName = (value: string) => setName(value);

  const handleAddSeat = (seat: CellType) => {
    setSeats((prev) => [...prev, seat]);
  };

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

  const handleDeleteBlock = (blockId: string) => {
    const confirmHandler = () => {
      const tempBlocks = [...blocks].filter((block) => block.id !== blockId);
      setBlocks(tempBlocks);
      closeModal();
    };
    showModal(MODALS.CONFIRM, {
      text: "Are you sure you want to delete this block?",
      confirmHandler,
    });
  };

  const handleDeleteCell = (cell: CellType) => {
    const tempCells = [...seats].filter((item) => item.label !== cell.label);
    if (
      !deletedCells.find((item) => item.label === cell.label) &&
      cells.find((item) => item.label === cell.label)
    ) {
      setDeletedCells((prev) => [...prev, cell]);
    }

    setSeats(tempCells);
  };

  const handleCheckUnDeleteCell = (cell: CellType) => {
    if (deletedCells.find((item) => item.label === cell.label)) {
      const tempCells = deletedCells.filter(
        (item) => item.label !== cell.label
      );
      setDeletedCells(tempCells);
    }
  };

  const handleSaveSeatmap = () => {
    if (name.length > MAX_OFFICE_NAME_LENGTH) {
      toast.error(`Office's name must be no more than 100 characters`, {
        theme: "colored",
        style: {
          fontWeight: 600,
          backgroundColor: "#FF8080",
          color: "#fff",
        },
      });
      return;
    }
    updateOffice({
      id: officeId,
      name,
      visible,
      seats,
      blocks: JSON.stringify(blocks),
      delete_seats: deletedCells,
    })
      .then(() => {})
      .catch(() => {});
  };

  const handleSelectingCells = (e: MouseEvent) => {
    if (!e.target) return;

    if (!e.shiftKey) return;

    const cellId = (e.target as HTMLDivElement).id;
    if (!!!cellId.length) return;

    const newCellPosition = Number(cellId.split("seat")[1]);
    if (lastSelectingCell) {
      const lastCellPosition = lastSelectingCell.position;
      if (
        Math.abs(newCellPosition - lastCellPosition) !== 1 &&
        Math.abs(newCellPosition - lastCellPosition) !== SEATMAP_COLUMNS_PER_ROW
      )
        return;
    }

    setLastSelectingCell({ position: newCellPosition, label: cellId });
    const tempCells = [...selectedCells];
    const existingCellIndex = selectedCells.findIndex(
      (item) => item.position === newCellPosition
    );
    if (existingCellIndex >= 0) return;

    tempCells.push({ label: cellId, position: newCellPosition });
    setSelectedCells(tempCells);
  };

  const handleDoneSelectingCells = (event: KeyboardEvent) => {
    if (event.keyCode === 16 && selectedCells.length > 0) {
      setDone(true);
      setLastSelectingCell(null);
    }
  };

  useEffect(() => {
    window.addEventListener("mouseover", handleSelectingCells);
    window.addEventListener("keyup", handleDoneSelectingCells);

    return () => {
      window.removeEventListener("mouseover", handleSelectingCells);
      window.removeEventListener("keyup", handleDoneSelectingCells);
    };
  }, [selectedCells, done, lastSelectingCell]);

  useEffect(() => {
    if (done) {
      showModal(MODALS.ADD_BLOCK, {
        confirmHandler: (name: string) => {
          setBlocks((prev) => [
            ...prev,
            { name, cells: selectedCells, id: uuid() },
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

  return {
    name,
    visible,
    blockCells,
    blocks,
    done,
    seats,
    selectedCells,
    handleAddSeat,
    handleChangeName,
    handleDeleteOffice,
    handleDeleteBlock,
    handleDeleteCell,
    handleCheckUnDeleteCell,
    handleSaveSeatmap,
    handleToggleVisible,
  };
};

export default useEditingOfficeSeatmap;
