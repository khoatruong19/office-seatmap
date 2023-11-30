import { cn } from "../../../lib/clsx";

type Props = React.ButtonHTMLAttributes<HTMLButtonElement> & {
  children?: React.ReactNode;
};

const Button = (props: Props) => {
  const { className = "", type = "button", children } = props;

  return (
    <button
      type={type}
      className={cn(
        "text-lg px-10 py-3 font-semibold bg-mainBlue hover-opacity",
        className
      )}
    >
      {children}
    </button>
  );
};

export default Button;
