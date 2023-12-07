import { createSlice } from "@reduxjs/toolkit";
import type { PayloadAction } from "@reduxjs/toolkit";
import { RootState } from "..";
import { UserType } from "../../schema/types";

type UserState = {
  users: UserType[];
};

const USER_SLICE_NAME = "user";

const userSlice = createSlice({
  name: USER_SLICE_NAME,
  initialState: { users: [] } as UserState,
  reducers: {
    setUsers: (
      state,
      { payload: { users } }: PayloadAction<{ users: UserType[] }>
    ) => {
      state.users = users;
    },
  },
});

export const { setUsers } = userSlice.actions;

export default userSlice.reducer;

export const selectUsers = (state: RootState) => state.user.users;
