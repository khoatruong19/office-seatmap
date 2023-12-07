import { useNavigate } from "react-router";
import Button from "../../../Form/Button";
import { ArrowBigRight, Check, Eye, EyeOff, Trash } from "lucide-react";
import { APP_ROUTES } from "../../../../config/routes";

type Props = {
  handleDeleteOffice: () => void;
  handleToggleVisible: () => void;
  handleSaveSeatmap: () => void;
  visible: boolean;
  officeId: number;
};

const Toolbar = ({
  handleDeleteOffice,
  handleSaveSeatmap,
  handleToggleVisible,
  visible,
  officeId,
}: Props) => {
  const navigate = useNavigate();

  const handleNavigateToOffice = () =>
    navigate(APP_ROUTES.OFFICE.replace(":id", `${officeId}`));

  return (
    <div className="flex flex-col items-center gap-4 justify-end mb-5">
      <Button onClick={handleNavigateToOffice} className="px-0 text-sky-400">
        <ArrowBigRight size={30} strokeWidth={2.5} />
      </Button>
      <Button onClick={handleDeleteOffice} className="px-0 text-secondary">
        <Trash strokeWidth={2.5} className="text-danger" />
      </Button>
      <Button onClick={handleToggleVisible} className="px-0 text-secondary">
        {visible ? <EyeOff /> : <Eye />}
      </Button>
      <Button onClick={handleSaveSeatmap} className="px-0 text-green-500">
        <Check strokeWidth={3} />
      </Button>
    </div>
  );
};

export default Toolbar;
