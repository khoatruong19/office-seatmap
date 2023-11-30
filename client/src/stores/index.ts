import { combineReducers, configureStore } from "@reduxjs/toolkit";
import { authApi } from "./auth/service";
import authReducer from "./auth/slice";
import userReducer from "./user/slice";
import { rtkQueryErrorLogger } from "./middlewares/rtkQueryErrorLogger";
import { userApi } from "./user/service";

export const store = configureStore({
  reducer: {
    [authApi.reducerPath]: authApi.reducer,
    [userApi.reducerPath]: userApi.reducer,
    auth: authReducer,
    user: userReducer,
  },
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware()
      .concat(authApi.middleware)
      .concat(userApi.middleware)
      .concat(rtkQueryErrorLogger),
});

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;
