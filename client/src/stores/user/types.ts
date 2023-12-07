import { BaseResponse } from "../../schema/response";
import { UserType } from "../../schema/types";

export type UploadAvatarRequest = {
  formData: FormData;
  userId: number;
};

export type UploadAvatarResponse = BaseResponse & {
  data: string;
};

export type GetAllResponse = BaseResponse & {
  data: UserType[];
};

export type CreateUserRequest = FormData;

export type CreateUserResponse = BaseResponse & {
  data: {
    id: number;
  };
};

export type UpdateUserRequest = {
  id: number;
  data: FormData;
};

export type UpdateUserResponse = BaseResponse & {
  data: {
    user: UserType;
  };
};

export type UpdateProfileRequest = {
  full_name: string;
  userId: number;
};

export type UpdateProfileResponse = BaseResponse & {
  data: UserType;
};

export type DeleteUserRequest = {
  userId: number;
};

export type DeleteUserResponse = BaseResponse;
