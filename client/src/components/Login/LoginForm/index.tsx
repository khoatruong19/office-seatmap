import { KeyRound, User } from "lucide-react";
import Input from "../../Form/Input";
import Label from "../../Form/Label";
import Button from "../../Form/Button";
import { useLoginMutation } from "../../../stores/auth/service";
import { SubmitHandler, useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { LoginSchema, LoginSchemaType } from "../../../schema/form";
import { useNavigate } from "react-router";
import { cn } from "../../../lib/clsx";
import { APP_ROUTES } from "../../../config/routes";

const LoginForm = () => {
  const navigate = useNavigate();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginSchemaType>({
    resolver: zodResolver(LoginSchema),
  });
  const [login, { isLoading }] = useLoginMutation();

  const onSubmit: SubmitHandler<LoginSchemaType> = (value: LoginSchemaType) => {
    login(value)
      .unwrap()
      .then(() => {
        navigate(APP_ROUTES.HOME);
      })
      .catch(() => {});
  };

  return (
    <form
      onSubmit={handleSubmit(onSubmit)}
      className="max-w-md w-full bg-transparent border-2 border-white backdrop-blur-[20px] shadow-lg text-white rounded-xl py-8 px-10"
    >
      <h1 className="text-4xl font-semibold text-center mb-8">Login</h1>

      <div className="relative flex flex-col gap-1">
        <Label field="Email" />
        <div
          className={cn(
            "flex items-center px-4 h-12 border-2 border-white rounded-3xl",
            {
              "border-danger ": errors.email,
            }
          )}
        >
          <Input
            register={register}
            name="email"
            placeholder="Email..."
            type="email"
            className="bg-transparent placeholder:text-white font-semibold text-[16px]"
          />
          <User strokeWidth={2.5} />
        </div>
        {errors.email && (
          <span className="absolute top-20 mt-1 text-xs text-danger font-semibold">
            {errors.email.message}
          </span>
        )}
      </div>

      <div className="relative flex flex-col gap-1 mt-5">
        <Label field="Password" />
        <div
          className={cn(
            "flex items-center px-4 h-12 border-2 border-white rounded-3xl",
            {
              "border-danger": errors.password,
            }
          )}
        >
          <Input
            register={register}
            name="password"
            placeholder="Password..."
            type="password"
            className="bg-transparent placeholder:text-white font-semibold text-[16px]"
          />
          <KeyRound strokeWidth={2.5} />
        </div>
        {errors.password && (
          <span className="absolute top-20 mt-1 text-xs text-danger font-semibold">
            {errors.password.message}
          </span>
        )}
      </div>

      <Button
        disabled={isLoading}
        type="submit"
        className="w-full h-12 bg-white text-black rounded-3xl my-6 text-lg font-bold hover-opacity"
      >
        Login
      </Button>
    </form>
  );
};

export default LoginForm;
