import { Link } from "react-router-dom";
import { APP_ROUTES } from "@config/routes";
import useCheckAdmin from "@hooks/useCheckAdmin";
import Button from "@components/Form/Button";
import { Pencil, Trash } from "lucide-react";
import { useModalContext } from "@providers/ModalProvider";
import { MODALS } from "@providers/ModalProvider/constants";
import { OfficeType } from "@schema/types";
import { useDeleteOfficeMutation } from "@stores/office/service";

type Props = {
  office: OfficeType;
};

const OfficeCard = ({ office }: Props) => {
  const isAdmin = useCheckAdmin();

  const { showModal, closeModal } = useModalContext();
  const [deleteOffice] = useDeleteOfficeMutation();

  const handleDeleteOffice = () => {
    const confirmHandler = () => {
      deleteOffice({ id: office.id })
        .then(() => closeModal())
        .catch(() => {});
    };

    showModal(MODALS.CONFIRM, {
      text: "Are you sure you want to delete this office?",
      confirmHandler,
    });
  };

  return (
    <div className="flex items-center justify-between w-full  bg-primary px-5 py-4 rounded-lg">
      <Link
        to={APP_ROUTES.OFFICE.replace(":id", `${office.id}`)}
        className="text-2xl text-secondary font-semibold underline hover-opacity"
      >
        {office.name}
      </Link>
      {isAdmin && (
        <div className="flex items-center gap-3">
          <Link to={APP_ROUTES.OFFICE_EDITING.replace(":id", `${office.id}`)}>
            <Button className="p-0 pt-2 text-secondary">
              <Pencil />
            </Button>
          </Link>
          <Button onClick={handleDeleteOffice} className="p-0 text-danger">
            <Trash strokeWidth={2.5} />
          </Button>
        </div>
      )}
    </div>
  );
};

export default OfficeCard;
