import { BaseResponse } from "@schema/response";
import { CellType, OfficeType } from "@schema/types";

export type CreateOfficeRequest = {
  name: string;
};

export type CreateOfficeResponse = BaseResponse & {
  data: number;
};

export type UpdateOfficeRequest = {
  id: number;
  name: string;
  visible: boolean;
  seats: CellType[];
  blocks: string;
};

export type UpdateOfficeResponse = BaseResponse & {
  data: number;
};

export type DeleteOfficeRequest = {
  id: number;
};

export type DeleteOfficeResponse = BaseResponse & {
  data: number;
};

export type GetAllOfficesResponse = BaseResponse & {
  data: OfficeType[];
};

export type GetOfficeResponse = BaseResponse & {
  data: OfficeType;
};
