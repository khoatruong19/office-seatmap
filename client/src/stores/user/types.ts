import { BaseResponse } from "../../schema/response";
import { User } from "../../schema/types";

export type UpdateUserRequest = {
  formData: FormData;
  userId: number;
};

export type UpdateUserResponse = BaseResponse & {
  data: User;
};
