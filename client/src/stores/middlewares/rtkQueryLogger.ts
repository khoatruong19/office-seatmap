import { isFulfilled, isRejectedWithValue } from "@reduxjs/toolkit";
import type { MiddlewareAPI, Middleware } from "@reduxjs/toolkit";
import { toast } from "react-toastify";

/**
 * Log a warning and show a toast!
 */
export const rtkQueryLogger: Middleware =
  (_: MiddlewareAPI) => (next) => (action) => {
    if (isFulfilled(action)) {
      toast.success(action.payload.messages);
    }
    if (isRejectedWithValue(action)) {
      toast.error("👺 " + action.payload.data.messages);
    }
    return next(action);
  };
