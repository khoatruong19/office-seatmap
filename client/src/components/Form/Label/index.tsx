import { cn } from "@lib/clsx";

type Props = React.LabelHTMLAttributes<HTMLLabelElement> & {
  field: string;
};

const Label = ({ field, htmlFor = "", className = "" }: Props) => {
  return (
    <label
      htmlFor={htmlFor}
      className={cn("text-lg font-semibold text-white", className)}
    >
      {field}
    </label>
  );
};

export default Label;
