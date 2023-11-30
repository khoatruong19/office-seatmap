import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { RootState } from "..";
import cookieManagement from "../../lib/js-cookie";
import { BASE_URL, ENDPOINTS } from "../../config/api";
import { UpdateUserRequest, UpdateUserResponse } from "./types";
import { setUser } from "../auth/slice";

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
    update: builder.mutation<UpdateUserResponse, UpdateUserRequest>({
      query: ({ userId, formData }) => ({
        url: `/${userId}/upload`,
        method: "POST",
        body: formData,
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

export const { useUpdateMutation } = userApi;
