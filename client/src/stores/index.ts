import { configureStore } from "@reduxjs/toolkit";
import { authApi } from "./auth/service";
import authReducer from "./auth/slice";
import { rtkQueryErrorLogger } from "./middlewares/rtkQueryErrorLogger";

export const store = configureStore({
  reducer: {
    [authApi.reducerPath]: authApi.reducer,
    auth: authReducer,
  },
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware()
      .concat(authApi.middleware)
      .concat(rtkQueryErrorLogger),
});

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;
