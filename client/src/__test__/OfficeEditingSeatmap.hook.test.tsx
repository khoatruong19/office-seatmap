import useEditingOfficeSeatmap from "@/hooks/useEditingOfficeSeatmap";
import { MODALS } from "@/providers/ModalProvider/constants";
import { act, renderHook } from "@testing-library/react";
import { describe, expect, it, vi } from "vitest";

const mockNavigate = vi.fn();

vi.mock("react-router-dom", async () => {
  const actual = await vi.importActual("react-router-dom");
  return {
    ...actual,
    useNavigate: () => mockNavigate,
  };
});

const mockUpdateOfficeMutation = vi.fn().mockResolvedValue({});
const mockShowModal = vi.fn();
const mockCloseModal = vi.fn();
const mockUpdateOffice = vi.fn(() => [mockUpdateOfficeMutation]);
const mockDeleteOffice = vi.fn();
const mockModalContext = {
  showModal: mockShowModal,
  closeModal: mockCloseModal,
};

vi.mock("@/stores/office/service", () => ({
  ...vi.importActual("@/stores/office/service"),
  useUpdateOfficeMutation: vi.fn(() => [mockUpdateOffice]),
  useDeleteOfficeMutation: () => [mockDeleteOffice],
}));

vi.mock("@/providers/ModalProvider", () => ({
  ...vi.importActual("@/providers/ModalProvider"),
  useModalContext: () => mockModalContext,
}));

describe("Editing Office Seatmap hook test suites", () => {
  let initialProps: any = {
    cells: [{ label: "B0", position: 20 }],
    initBlocks: [{ id: "blockId", name: "firstBlock", cells: [] }],
    isVisible: true,
    officeName: "test office",
    officeId: "someId",
  };

  afterEach(() => {
    vi.clearAllMocks();
  });

  it("should add new seat", () => {
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    act(() => {
      result.current.handleAddSeat({ label: "AO", position: 10 });
    });

    expect(result.current.seats.length).toBe(2);
  });

  it("should delete existed seat", async () => {
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    act(() => {
      result.current.handleAddSeat({ label: "A1", position: 10 });
    });
    act(() => {
      result.current.handleDeleteCell({ label: "A1", position: 10 });
    });

    expect(result.current.seats.length).toBe(1);
  });

  it("should add deleted seat to deletedCells state when that cell is already saved!", async () => {
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    act(() => {
      result.current.handleAddSeat({ label: "A1", position: 10 });
    });

    expect(result.current.seats.length).toBe(2);

    act(() => {
      result.current.handleDeleteCell({ label: "B0", position: 20 });
    });

    expect(result.current.seats.length).toBe(1);
    expect(result.current.deletedCells.length).toBe(1);
  });

  it("should remove added seat of deletedCells state when that cell is created again!", async () => {
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    act(() => {
      result.current.handleAddSeat({ label: "A1", position: 10 });
    });

    expect(result.current.seats.length).toBe(2);

    act(() => {
      result.current.handleDeleteCell({ label: "B0", position: 20 });
    });

    expect(result.current.seats.length).toBe(1);
    expect(result.current.deletedCells.length).toBe(1);

    act(() => {
      result.current.handleAddSeat({ label: "B0", position: 20 });
      result.current.handleCheckUnDeleteCell({ label: "B0", position: 20 });
    });

    expect(result.current.seats.length).toBe(2);
    expect(result.current.deletedCells.length).toBe(0);
  });

  it("should toggle office's visible", () => {
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    act(() => {
      result.current.handleToggleVisible();
    });

    expect(result.current.visible).toBeFalsy();

    act(() => {
      result.current.handleToggleVisible();
    });

    expect(result.current.visible).toBeTruthy();
  });

  it("should change office name", () => {
    const newOfficeName = "new office";
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    act(() => {
      result.current.handleChangeName(newOfficeName);
    });

    expect(result.current.name).toBe(newOfficeName);
  });

  it("should call delete office mutation", async () => {
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    await act(async () => {
      await result.current.handleDeleteOffice();
    });

    expect(mockShowModal).toHaveBeenCalledWith(MODALS.CONFIRM, {
      text: "Are you sure you want to delete this office?",
      confirmHandler: expect.any(Function),
    });

    const confirmHandler = mockShowModal.mock.calls[0][1].confirmHandler;
    await act(async () => {
      confirmHandler();
    });

    expect(mockShowModal).toHaveBeenCalled();
    expect(mockCloseModal).toHaveBeenCalled();
    expect(mockDeleteOffice).toBeCalledTimes(1);
    expect(mockNavigate).toHaveBeenCalled();
  });

  it("should delete office's block", async () => {
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    act(() => {
      result.current.handleDeleteBlock("blockId");
    });

    expect(mockShowModal).toHaveBeenCalledWith(MODALS.CONFIRM, {
      text: "Are you sure you want to delete this block?",
      confirmHandler: expect.any(Function),
    });

    const confirmHandler = mockShowModal.mock.calls[0][1].confirmHandler;
    await act(async () => {
      confirmHandler();
    });

    expect(mockShowModal).toHaveBeenCalled();
    expect(mockCloseModal).toHaveBeenCalled();
    expect(result.current.blocks).toHaveLength(0);
  });

  it("should select cell when user hold Shift and mouse over cells", async () => {
    const mockMouseEvent = {
      target: {
        id: "seat123",
      },
      shiftKey: true,
    };
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    act(() => {
      result.current.handleSelectingCells(mockMouseEvent as any as MouseEvent);
    });

    expect(result.current.selectedCells[0]).toEqual({
      position: 123,
      label: "seat123",
    });

    expect(result.current.lastSelectingCell).toEqual({
      position: 123,
      label: "seat123",
    });
  });

  it("should add block when user done selecting cells", async () => {
    const mockMouseEvent = {
      target: {
        id: "seat123",
      },
      shiftKey: true,
    };
    const { result } = renderHook(() => useEditingOfficeSeatmap(initialProps));

    act(() => {
      result.current.handleSelectingCells(mockMouseEvent as any as MouseEvent);
    });

    expect(result.current.selectedCells[0]).toEqual({
      position: 123,
      label: "seat123",
    });

    expect(result.current.lastSelectingCell).toEqual({
      position: 123,
      label: "seat123",
    });

    const mockKeyboardEvent = {
      keyCode: 16,
    };

    act(() => {
      result.current.handleDoneSelectingCells(
        mockKeyboardEvent as any as KeyboardEvent
      );
    });

    expect(result.current.lastSelectingCell).toBeNull();

    expect(mockShowModal).toHaveBeenCalledWith(MODALS.ADD_BLOCK, {
      confirmHandler: expect.any(Function),
      cancelHandler: expect.any(Function),
    });

    const confirmHandler = mockShowModal.mock.calls[0][1].confirmHandler;
    await act(async () => {
      confirmHandler("new block");
    });

    expect(result.current.blocks).toHaveLength(2);
    expect(result.current.selectedCells).toHaveLength(0);
    expect(mockCloseModal).toBeCalled();
  });
});
