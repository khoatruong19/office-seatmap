import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { RootState } from "..";
import cookieManagement from "@lib/js-cookie";
import { BASE_URL, ENDPOINTS } from "@config/api";
import {
  RemoveUserRequest,
  RemoveUserResponse,
  SetUserRequest,
  SetUserResponse,
} from "./types";
import { officeApi } from "../office/service";

const TAGS = {
  OFFICE: "office",
};

export const seatApi = createApi({
  reducerPath: "seat-api",
  baseQuery: fetchBaseQuery({
    baseUrl: BASE_URL + ENDPOINTS.SEAT,
    prepareHeaders: (headers, { getState }) => {
      const token =
        (getState() as RootState).auth.accessToken ??
        cookieManagement.getAccessToken();
      if (token) {
        headers.set("Authorization", `Bearer ${token}`);
      }
      return headers;
    },
  }),
  endpoints: (builder) => ({
    setUser: builder.mutation<SetUserResponse, SetUserRequest>({
      query: ({ id, ...data }) => ({
        url: `/${id}/set-user`,
        method: "PATCH",
        body: data,
      }),
      onQueryStarted(_, { queryFulfilled, dispatch }) {
        queryFulfilled
          .then(() => {
            dispatch(officeApi.util.invalidateTags([TAGS.OFFICE]));
          })
          .catch(() => null);
      },
    }),
    removeUser: builder.mutation<RemoveUserResponse, RemoveUserRequest>({
      query: ({ id }) => ({
        url: `/${id}/remove-user`,
        method: "PATCH",
      }),
      onQueryStarted(_, { queryFulfilled, dispatch }) {
        queryFulfilled
          .then(() => {
            dispatch(officeApi.util.invalidateTags([TAGS.OFFICE]));
          })
          .catch(() => null);
      },
    }),
  }),
});

export const { useSetUserMutation, useRemoveUserMutation } = seatApi;
