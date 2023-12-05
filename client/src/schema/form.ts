import { z } from "zod";
import { UserRole } from "./types";

export const LoginSchema = z.object({
  email: z
    .string()
    .trim()
    .email()
    .min(8, "Email must contain at least 8 characters!")
    .max(100, "Email must be no more than 100 characters!"),
  password: z
    .string()
    .trim()
    .min(8, "Password must contain at least 8 characters!"),
});

export type LoginSchemaType = z.infer<typeof LoginSchema>;

export const ProfileSchema = z.object({
  full_name: z
    .string()
    .trim()
    .min(8, "Fullname must contain at least 8 characters!")
    .max(100, "Fullname must be no more than 100 characters!"),
});

export type ProfileSchemaType = z.infer<typeof ProfileSchema>;

export const UserSchema = z.object({
  email: z
    .string()
    .trim()
    .email()
    .min(8, "Email must contain at least 8 characters!")
    .max(100, "Email must be no more than 100 characters!"),
  full_name: z
    .string()
    .trim()
    .min(8, "Fullname must contain at least 8 characters!")
    .max(100, "Fullname must be no more than 100 characters!"),
  password: z
    .string()
    .trim()
    .min(8, "Password must contain at least 8 characters!")
    .optional(),
  role: z.enum([UserRole.ADMIN, UserRole.USER]),
});

export type UserSchemaType = z.infer<typeof UserSchema>;

export const AddOfficeSchema = z.object({
  name: z
    .string()
    .trim()
    .min(4, "Office's name must contain at least 8 characters!")
    .max(60, "Office's name must be no more than 60 characters!"),
});

export type AddOfficeSchemaType = z.infer<typeof AddOfficeSchema>;

export const AddBlockSchema = z.object({
  name: z
    .string()
    .trim()
    .min(1, "Block's name must contain at least 1 characters!")
    .max(12, "Block's name must be no more than 12 characters!"),
});

export type AddBlockSchemaType = z.infer<typeof AddBlockSchema>;
