import Button from "@/components/Form/Button";
import { cn } from "@/lib/clsx";
import { ChevronLeft } from "lucide-react";
import { useNavigate } from "react-router-dom";

type Props = {
  className?: string;
};

const BackButton = ({ className = "" }: Props) => {
  const navigate = useNavigate();

  const handleNavigateBack = () => {
    navigate(-1);
  };
  return (
    <Button
      onClick={handleNavigateBack}
      className={cn(
        "absolute flex items-center gap-2.5 w-fit font-medium font-sans",
        className
      )}
    >
      <div className={"px-1.5 py-1 rounded-md bg-primary text-white "}>
        <ChevronLeft size={28} strokeWidth={2.5} />
      </div>
      <span className="hover-opacity">Back</span>
    </Button>
  );
};

export default BackButton;
