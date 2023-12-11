import { isFulfilled, isRejectedWithValue } from "@reduxjs/toolkit";
import type { Middleware } from "@reduxjs/toolkit";
import { toast } from "react-toastify";

const blockToastFulfilledEndpoints = [
  "getAllUsers",
  "logout",
  "getAllOffices",
  "getOffice",
  "me",
  "removeUser",
];

const blockToastErrorEndpoints = ["getOffice"];

export const rtkQueryLogger: Middleware = () => (next) => (action) => {
  if (
    isFulfilled(action) &&
    !blockToastFulfilledEndpoints.includes(action.meta.arg.endpointName)
  ) {
    toast.success(` ${action.payload.messages}`, {
      theme: "colored",
      style: {
        fontWeight: 600,
        backgroundColor: "#fff",
        color: "#164863",
      },
    });
  }

  if (
    isRejectedWithValue(action) &&
    !blockToastErrorEndpoints.includes(action.meta.arg.endpointName)
  ) {
    toast.error(`${action.payload.data.messages}`, {
      theme: "colored",
      style: {
        fontWeight: 600,
        backgroundColor: "#FF8080",
        color: "#fff",
      },
    });
  }

  return next(action);
};
