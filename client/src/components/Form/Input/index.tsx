import React, { LegacyRef } from "react";
import { cn } from "../../../lib/clsx";

type Props = React.InputHTMLAttributes<HTMLInputElement> & {
  register?: any;
  name?: string;
};

const Input = (props: Props) => {
  const {
    className,
    placeholder = "",
    type = "text",
    name = "",
    register = () => {},
  } = props;

  return (
    <input
      {...register(name)}
      type={type}
      className={cn("px-2 py-1 outline-none flex-1", className)}
      placeholder={placeholder}
    />
  );
};

export default Input;
