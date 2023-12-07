import { configureStore } from "@reduxjs/toolkit";
import { authApi } from "./auth/service";
import { userApi } from "./user/service";
import { officeApi } from "./office/service";
import authReducer from "./auth/slice";
import userReducer from "./user/slice";
import { rtkQueryLogger } from "./middlewares/rtkQueryLogger";

export const store = configureStore({
  reducer: {
    [authApi.reducerPath]: authApi.reducer,
    [userApi.reducerPath]: userApi.reducer,
    [officeApi.reducerPath]: officeApi.reducer,
    auth: authReducer,
    user: userReducer,
  },
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware()
      .concat(authApi.middleware)
      .concat(userApi.middleware)
      .concat(officeApi.middleware)
      .concat(rtkQueryLogger),
});

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;
