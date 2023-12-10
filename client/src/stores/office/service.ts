import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { RootState } from "..";
import cookieManagement from "@lib/js-cookie";
import { BASE_URL, ENDPOINTS } from "@config/api";
import {
  CreateOfficeRequest,
  CreateOfficeResponse,
  GetAllOfficesResponse,
  GetOfficeResponse,
  UpdateOfficeRequest,
  UpdateOfficeResponse,
  DeleteOfficeRequest,
  DeleteOfficeResponse,
} from "./types";

const TAGS = {
  OFFICES: "offices",
  OFFICE: "office",
};

export const officeApi = createApi({
  reducerPath: "office-api",
  baseQuery: fetchBaseQuery({
    baseUrl: BASE_URL + ENDPOINTS.OFFICE,
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
  tagTypes: [...Object.values(TAGS)],
  endpoints: (builder) => ({
    createOffice: builder.mutation<CreateOfficeResponse, CreateOfficeRequest>({
      query: (data) => ({
        url: ``,
        method: "POST",
        body: data,
      }),
      invalidatesTags: [TAGS.OFFICES],
      onQueryStarted(_, { queryFulfilled }) {
        queryFulfilled.then(() => {}).catch(() => null);
      },
    }),
    updateOffice: builder.mutation<UpdateOfficeResponse, UpdateOfficeRequest>({
      query: ({ id, ...rest }) => ({
        url: `/${id}`,
        method: "PATCH",
        body: rest,
      }),
      invalidatesTags: [TAGS.OFFICE, TAGS.OFFICES],
      onQueryStarted(_, { queryFulfilled }) {
        queryFulfilled.then(() => {}).catch(() => null);
      },
    }),
    deleteOffice: builder.mutation<DeleteOfficeResponse, DeleteOfficeRequest>({
      query: ({ id }) => ({
        url: `/${id}`,
        method: "DELETE",
      }),
      invalidatesTags: [TAGS.OFFICES],
      onQueryStarted(_, { queryFulfilled }) {
        queryFulfilled.then(() => {}).catch(() => null);
      },
    }),
    getAllOffices: builder.query<GetAllOfficesResponse, void>({
      query: () => ({ url: "" }),
      providesTags: [TAGS.OFFICES],
    }),
    getOffice: builder.query<GetOfficeResponse, string>({
      query: (officeId) => ({ url: `/${officeId}` }),
      providesTags: [TAGS.OFFICE],
    }),
  }),
});

export const {
  useCreateOfficeMutation,
  useUpdateOfficeMutation,
  useDeleteOfficeMutation,
  useGetAllOfficesQuery,
  useGetOfficeQuery,
} = officeApi;
