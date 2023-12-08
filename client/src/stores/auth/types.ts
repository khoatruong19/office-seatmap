import { BaseResponse } from "@schema/response";
import { UserType } from "@schema/types";

export type LoginResponse = BaseResponse & {
  data: {
    user: UserType;
    accessToken: string;
  };
};

export type GetMeResponse = BaseResponse & {
  data: {
    user: UserType;
  };
};

export type LogoutResponse = BaseResponse & {
  data: boolean;
};

export type LoginRequest = {
  email: string;
  password: string;
};
