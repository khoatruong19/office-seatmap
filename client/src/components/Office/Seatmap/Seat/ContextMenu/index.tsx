import { useClickOutside } from "@/hooks/useClickOutside";
import { useRemoveUserMutation } from "@/stores/seat/service";
import { useRef } from "react";

type Props = {
  seatId: number;
  setShowContext: (value: boolean) => void;
};

const ContextMenu = ({ seatId, setShowContext }: Props) => {
  const ref = useRef<HTMLDivElement | null>(null);

  const [removeUser] = useRemoveUserMutation();

  useClickOutside(ref, () => setShowContext(false));

  const handleRemoveUser = async () => {
    try {
      await removeUser({ id: seatId });
      setShowContext(false);
    } catch (error) {
      return;
    }
  };

  return (
    <div
      ref={ref}
      onClick={handleRemoveUser}
      className="absolute left-4 bottom-8 text-white z-50 bg-danger p-1.5 text-xs text-center rounded-md leading-3 hover:opacity-90 cursor-pointer"
    >
      Remove
    </div>
  );
};

export default ContextMenu;
