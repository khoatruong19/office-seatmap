import { isFulfilled, isRejectedWithValue } from "@reduxjs/toolkit";
import type { MiddlewareAPI, Middleware } from "@reduxjs/toolkit";
import { toast } from "react-toastify";

const blockToastFulfilledEndpoints = [
  "getAllUsers",
  "logout",
  "getAllOffices",
  "getOffice",
];

const blockToastErrorEndpoints = ["getOffice"];

export const rtkQueryLogger: Middleware =
  (_: MiddlewareAPI) => (next) => (action) => {
    if (
      isFulfilled(action) &&
      !blockToastFulfilledEndpoints.includes(action.meta.arg.endpointName)
    ) {
      toast.success(action.payload.messages);
    }

    if (
      isRejectedWithValue(action) &&
      !blockToastErrorEndpoints.includes(action.meta.arg.endpointName)
    ) {
      toast.error("👺 " + action.payload.data.messages);
    }

    return next(action);
  };
