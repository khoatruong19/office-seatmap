import Button from "../../Form/Button";
import { Plus } from "lucide-react";
import { useModalContext } from "../../../providers/ModalProvider";
import { MODALS } from "../../../providers/ModalProvider/constants";
import useCheckAdmin from "../../../hooks/useCheckAdmin";

const AddOfficeButton = () => {
  const isAdmin = useCheckAdmin();
  const { showModal } = useModalContext();
  const handleOpenAddOfficeModal = () => showModal(MODALS.ADD_OFFICE, {});

  if (!isAdmin) return null;

  return (
    <Button
      onClick={handleOpenAddOfficeModal}
      className="flex items-center gap-3 ml-auto bg-secondary pl-2 pr-3 text-white rounded-md mb-5"
    >
      <Plus />
      <span>Add office</span>
    </Button>
  );
};

export default AddOfficeButton;
