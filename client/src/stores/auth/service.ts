import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { RootState } from "..";
import { deleteCredentials, setCredentials } from "./slice";
import Cookies from "js-cookie";
import { BASE_URL, ENDPOINTS, ACCESS_TOKEN_KEY } from "../../config/api";
import {
  GetMeResponse,
  LoginRequest,
  LoginResponse,
  LogoutResponse,
} from "./types";

export const authApi = createApi({
  baseQuery: fetchBaseQuery({
    baseUrl: BASE_URL + ENDPOINTS.AUTH,
    prepareHeaders: (headers, { getState }) => {
      const token =
        (getState() as RootState).auth.accessToken ??
        Cookies.get(ACCESS_TOKEN_KEY);
      if (token) {
        headers.set("Authorization", `Bearer ${token}`);
      }
      return headers;
    },
  }),
  endpoints: (builder) => ({
    login: builder.mutation<LoginResponse, LoginRequest>({
      query: (credentials) => ({
        url: "login",
        method: "POST",
        body: credentials,
      }),
      onQueryStarted(_, { dispatch, queryFulfilled }) {
        queryFulfilled
          .then((data) => {
            const {
              data: { data: credentials },
            } = data;
            dispatch(setCredentials(credentials));
            Cookies.set(ACCESS_TOKEN_KEY, credentials.accessToken, {
              expires: 1,
            });
          })
          .catch(() => {});
      },
    }),
    me: builder.mutation<GetMeResponse, null>({
      query: () => ({
        url: "me",
        method: "GET",
      }),
      onQueryStarted(_, { dispatch, queryFulfilled, getState }) {
        queryFulfilled
          .then((data) => {
            const {
              data: {
                data: { user },
              },
            } = data;
            const accessToken =
              (getState() as RootState).auth.accessToken ??
              Cookies.get(ACCESS_TOKEN_KEY);
            if (accessToken) {
              dispatch(setCredentials({ user, accessToken }));
            }
          })
          .catch(() => {});
      },
    }),
    logout: builder.mutation<LogoutResponse, null>({
      query: () => ({
        url: "logout",
        method: "GET",
      }),
      onQueryStarted(_, { dispatch, queryFulfilled }) {
        queryFulfilled
          .then(() => {
            dispatch(deleteCredentials());
            Cookies.remove(ACCESS_TOKEN_KEY);
          })
          .catch(() => {});
      },
    }),
  }),
});

export const { useLoginMutation, useMeMutation, useLogoutMutation } = authApi;
