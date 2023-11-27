import { KeyRound, User } from "lucide-react";
import Input from "../../Form/Input";
import Label from "../../Form/Label";
import Button from "../../Form/Button";

type Props = {};

const LoginForm = (props: Props) => {
  return (
    <form className="max-w-md w-full px-10 pt-5 pb-10 bg-white rounded-md shadow-md">
      <h1 className="text-3xl font-semibold mb-10 text-center">Login</h1>

      <div className="flex flex-col gap-1">
        <Label field="Email" />
        <div className="flex items-center gap-2">
          <User />
          <Input placeholder="Email..." type="email" />
        </div>
      </div>

      <div className="flex flex-col gap-1 mt-5">
        <Label field="Password" />
        <div className="flex items-center gap-2">
          <KeyRound />
          <Input placeholder="Password..." type="password" />
        </div>
      </div>

      <Button type="submit" className="mx-auto block mt-8 rounded-sm">
        Login
      </Button>
    </form>
  );
};

export default LoginForm;
