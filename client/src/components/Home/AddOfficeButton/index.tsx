import Button from "../../Form/Button";
import { Plus } from "lucide-react";
import { useModalContext } from "../../../providers/ModalProvider";
import { MODALS } from "../../../providers/ModalProvider/constants";
import useCheckAdmin from "../../../hooks/useCheckAdmin";
import { useCreateOfficeMutation } from "../../../stores/office/service";
import { useNavigate } from "react-router";
import { APP_ROUTES } from "../../../config/routes";

const AddOfficeButton = () => {
  const isAdmin = useCheckAdmin();
  const navigate = useNavigate();

  const { showModal, closeModal } = useModalContext();
  const [createOffice] = useCreateOfficeMutation();

  const handleOpenAddOfficeModal = () => {
    const confirmHandler = (name: string) =>
      createOffice({ name })
        .then((data) => {
          if ("data" in data) {
            navigate(
              APP_ROUTES.OFFICE_EDITING.replace(":id", `${data.data.data}`)
            );
            closeModal();
          }
        })
        .catch(() => {});
    showModal(MODALS.ADD_OFFICE, { confirmHandler });
  };

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
