import { BaseResponse } from "@schema/response";

export type SetUserRequest = {
  id: number;
  user_id: number;
  office_id: number;
};

export type SetUserResponse = BaseResponse & {
  data: number;
};

export type RemoveUserRequest = {
  id: number;
};

export type RemoveUserResponse = BaseResponse & {
  data: number;
};

export type SwapUsersRequest = {
  officeId: number;
  firstSeatId: number;
  firstUserId: number;
  secondSeatId: number;
  secondUserId: number;
};

export type SwapUsersResponse = BaseResponse;
