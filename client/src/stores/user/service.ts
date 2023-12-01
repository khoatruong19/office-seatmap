import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { RootState } from "..";
import cookieManagement from "../../lib/js-cookie";
import { BASE_URL, ENDPOINTS } from "../../config/api";
import { setUser } from "../auth/slice";
import {
  UpdateProfileResponse,
  UpdateProfileRequest,
  UploadRequest,
  UploadResponse,
} from "./types";

export const userApi = createApi({
  reducerPath: "user-api",
  baseQuery: fetchBaseQuery({
    baseUrl: BASE_URL + ENDPOINTS.USER,
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
    upload: builder.mutation<UploadResponse, UploadRequest>({
      query: ({ userId, formData }) => ({
        url: `/${userId}/upload`,
        method: "POST",
        body: formData,
      }),
      onQueryStarted(_, { dispatch, queryFulfilled, getState }) {
        queryFulfilled
          .then((data) => {
            const {
              data: { data: url },
            } = data;
            dispatch(
              setUser({
                user: { ...(getState() as RootState).auth.user!, avatar: url },
              })
            );
          })
          .catch(() => {});
      },
    }),
    updateProfile: builder.mutation<
      UpdateProfileResponse,
      UpdateProfileRequest
    >({
      query: ({ userId, full_name }) => ({
        url: `/profile/${userId}`,
        method: "PATCH",
        body: { full_name },
      }),
      onQueryStarted(_, { dispatch, queryFulfilled }) {
        queryFulfilled
          .then((data) => {
            const {
              data: { data: user },
            } = data;
            dispatch(setUser({ user }));
          })
          .catch(() => {});
      },
    }),
  }),
});

export const { useUpdateProfileMutation, useUploadMutation } = userApi;
