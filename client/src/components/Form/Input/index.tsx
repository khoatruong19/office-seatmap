import { cn } from "../../../lib/clsx";

type Props = {
  placeholder?: string;
  className?: string;
  type?: string;
};

const Input = (props: Props) => {
  const { className, placeholder = "", type = "text" } = props;

  return (
    <input
      type={type}
      className={cn("px-2 py-1 outline-none flex-1", className)}
      placeholder={placeholder}
    />
  );
};

export default Input;
