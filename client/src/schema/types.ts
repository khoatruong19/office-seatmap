export type SeatType = {
  id: string;
  row: string;
  order: number;
};

export type User = {
  id: number;
  email: string;
  full_name: string;
  role: "user" | "admin";
  avatar: string | null;
  created_at: Date;
  updated_at: Date;
};
