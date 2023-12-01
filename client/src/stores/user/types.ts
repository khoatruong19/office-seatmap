import { BaseResponse } from "../../schema/response";
import { User } from "../../schema/types";

export type UploadRequest = {
  formData: FormData;
  userId: number;
};

export type UploadResponse = BaseResponse & {
  data: string;
};

export type UpdateProfileRequest = {
  full_name: string;
  userId: number;
};

export type UpdateProfileResponse = BaseResponse & {
  data: User;
};
