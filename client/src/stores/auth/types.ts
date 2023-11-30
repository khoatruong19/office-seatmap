import { BaseResponse } from "../../schema/response";

export type User = {
  id: number;
  email: string;
  full_name: string;
  role: "user" | "admin";
  avatar: string | null;
  created_at: Date;
  updated_at: Date;
};

export type LoginResponse = BaseResponse & {
  data: {
    user: User;
    accessToken: string;
  };
};

export type GetMeResponse = BaseResponse & {
  data: {
    user: User;
  };
};

export type LogoutResponse = BaseResponse & {
  data: boolean;
};

export type LoginRequest = {
  email: string;
  password: string;
};
