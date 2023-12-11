import Button from "@/components/Form/Button";
import { APP_ROUTES } from "@/config/routes";
import { cn } from "@/lib/clsx";
import { ChevronLeft } from "lucide-react";
import { Link } from "react-router-dom";

type Props = {
  className?: string;
};

const BackToHomeButton = ({ className = "" }: Props) => {
  return (
    <Link
      to={APP_ROUTES.HOME}
      className={cn(
        "absolute flex items-center gap-2.5 w-fit font-medium font-sans",
        className
      )}
    >
      <Button className={"px-1.5 py-1 rounded-md bg-primary text-white "}>
        <ChevronLeft size={28} strokeWidth={2.5} />
      </Button>
      <span className="hover-opacity">Back to home</span>
    </Link>
  );
};

export default BackToHomeButton;
