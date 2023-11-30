import { z } from "zod";

export const LoginSchema = z.object({
  email: z
    .string()
    .trim()
    .email()
    .min(8, "Email must contain at least 8 characters!"),
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
    .min(8, "Fullname must contain at least 8 characters!"),
});

export type ProfileSchemaType = z.infer<typeof ProfileSchema>;
