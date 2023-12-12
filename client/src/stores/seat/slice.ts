import { createSlice } from "@reduxjs/toolkit";
import type { PayloadAction } from "@reduxjs/toolkit";
import { SeatType } from "@schema/types";

type SeatState = {
  dragSeat: SeatType | null;
  targetSeat: SeatType | null;
};

const SEAT_SLICE_NAME = "seat";

const seatSlice = createSlice({
  name: SEAT_SLICE_NAME,
  initialState: { dragSeat: null, targetSeat: null } as SeatState,
  reducers: {
    setDragSeat: (state, { payload }: PayloadAction<SeatType | null>) => {
      state.dragSeat = payload;
    },
    setTargetSeat: (state, { payload }: PayloadAction<SeatType | null>) => {
      state.targetSeat = payload;
    },
  },
});

export const { setDragSeat, setTargetSeat } = seatSlice.actions;

export default seatSlice.reducer;
