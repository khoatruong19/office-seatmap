import { SubmitHandler, useForm } from "react-hook-form";
import { LoginSchema, LoginSchemaType } from "../../../schema/form";
import { zodResolver } from "@hookform/resolvers/zod";
import Label from "../../Form/Label";
import { KeyRound, User } from "lucide-react";
import Input from "../../Form/Input";
import Button from "../../Form/Button";

type Props = {};

const ProfileModal = (props: Props) => {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginSchemaType>({
    resolver: zodResolver(LoginSchema),
  });

  const onSubmit: SubmitHandler<LoginSchemaType> = async (
    value: LoginSchemaType
  ) => {
    // const user = await login(value).unwrap();
    // if (user.data) {
    //   navigate("/");
    // }
  };

  return (
    <div className="w-[500px] py-2 font-mono">
      <h1 className="text-3xl font-semibold text-center">Profile</h1>
      <form
        onSubmit={handleSubmit(onSubmit)}
        className="max-w-md w-full bg-white rounded-md flex flex-col items-center justify-center"
      >
        <div className="flex flex-col gap-1">
          <Label field="Email" />
          <div className="flex items-center gap-2">
            <User />
            <Input
              disabled
              register={register}
              name="email"
              placeholder="Email..."
              type="email"
            />
          </div>
          {errors.email && (
            <span className="text-xs text-red-400 font-semibold">
              {errors.email.message}
            </span>
          )}
        </div>

        <div className="flex flex-col gap-1 mt-5">
          <Label field="Password" />
          <div className="flex items-center gap-2">
            <KeyRound />
            <Input
              register={register}
              name="password"
              placeholder="Password..."
              type="password"
            />
          </div>
          {errors.password && (
            <span className="text-xs text-red-400 font-semibold">
              {errors.password.message}
            </span>
          )}
        </div>

        <Button type="submit" className="mx-auto block mt-8 rounded-lg">
          Login
        </Button>
      </form>
    </div>
  );
};

export default ProfileModal;
