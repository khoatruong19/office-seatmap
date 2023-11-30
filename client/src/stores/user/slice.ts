import { createSlice } from "@reduxjs/toolkit";
import type { PayloadAction } from "@reduxjs/toolkit";
import { RootState } from "..";
import { User } from "../../schema/types";

type userState = {
  user: User | null;
  accessToken: string | null;
};

const USER_SLICE_NAME = "user";

const userSlice = createSlice({
  name: USER_SLICE_NAME,
  initialState: { user: null, accessToken: null } as userState,
  reducers: {
    setCredentials: (
      state,
      {
        payload: { user, accessToken },
      }: PayloadAction<{ user: User; accessToken: string }>
    ) => {
      state.user = user;
      state.accessToken = accessToken;
    },
    deleteCredentials: (state) => {
      state.user = null;
      state.accessToken = null;
    },
  },
});

export const { setCredentials, deleteCredentials } = userSlice.actions;

export default userSlice.reducer;

export const selectCurrentUser = (state: RootState) => state.user.user;
