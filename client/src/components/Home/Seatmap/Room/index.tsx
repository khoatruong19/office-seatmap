import { cn } from "../../../../lib/clsx";

type Props = {
  title?: string;
  className?: string;
};

const Room = ({ title = "Room", className = "" }: Props) => {
  return (
    <div
      className={cn(
        "absolute top-0 bg-room flex items-center justify-center rounded-md",
        className
      )}
    >
      {title}
    </div>
  );
};

export default Room;
