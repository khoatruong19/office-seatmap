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
  // .regex(new RegExp('.*[A-Z].*'), 'One uppercase character')
  // .regex(
  //   new RegExp('.*[`~<>?,./!@#$%^&*()\\-_+="\'|{}\\[\\];:\\\\].*'),
  //   'One symbol',
  // ),
});

export type LoginSchemaType = z.infer<typeof LoginSchema>;
