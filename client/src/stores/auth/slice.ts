import { createSlice } from "@reduxjs/toolkit";
import type { PayloadAction } from "@reduxjs/toolkit";
import { RootState } from "..";
import { User } from "./types";

type AuthState = {
  user: User | null;
  accessToken: string | null;
};

const AUTH_SLICE_NAME = "auth";

const authSlice = createSlice({
  name: AUTH_SLICE_NAME,
  initialState: { user: null, accessToken: null } as AuthState,
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

export const { setCredentials, deleteCredentials } = authSlice.actions;

export default authSlice.reducer;

export const selectCurrentUser = (state: RootState) => state.auth.user;
