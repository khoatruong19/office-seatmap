export type SeatType = {
  id: number;
  label: string;
  position: number;
  available: boolean;
  userId?: number;
  avatar?: string;
  full_name?: string;
  role?: string;
};

export enum UserRole {
  ADMIN = "admin",
  USER = "user",
}

export type UserType = {
  id: number;
  email: string;
  full_name: string;
  role: UserRole;
  avatar: string | null;
  created_at: Date;
  updated_at: Date;
};

export type CellType = {
  label: string;
  position: number;
};

export type BlockType = {
  id: string;
  name: string;
  cells: CellType[];
};

export type OfficeType = {
  id: number;
  name: string;
  visible: boolean;
  blocks: string;
  seats: SeatType[];
};

export enum UserEditingModalType {
  CREATE = "create",
  UPDATE = "update",
}
