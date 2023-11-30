import React from "react";
import { cn } from "../../../lib/clsx";

type Props = React.InputHTMLAttributes<HTMLInputElement> & {
  register?: Function;
  name?: string;
};

const Input = (props: Props) => {
  const { className, name = "", register = () => {} } = props;

  return (
    <input
      {...props}
      {...register(name)}
      className={cn("px-2 py-1 outline-none flex-1", className)}
    />
  );
};

export default Input;
