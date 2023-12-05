import { Link } from "react-router-dom";
import { APP_ROUTES } from "../../../config/routes";
import useCheckAdmin from "../../../hooks/useCheckAdmin";
import Button from "../../Form/Button";
import { Pencil, Trash } from "lucide-react";

type Props = {
  office: any;
};

const OfficeCard = ({ office }: Props) => {
  const isAdmin = useCheckAdmin();
  return (
    <div
      key={office.slug}
      className="flex items-center justify-between w-full  bg-primary px-5 py-4 rounded-lg"
    >
      <Link
        to={APP_ROUTES.OFFICE.replace(":id", office.slug)}
        className="text-2xl text-secondary font-semibold underline hover-opacity"
      >
        {office.name}
      </Link>
      {isAdmin && (
        <div className="flex items-center gap-3">
          <Link to={APP_ROUTES.OFFICE_EDITING.replace(":id", office.slug)}>
            <Button className="p-0 pt-2 text-secondary">
              <Pencil />
            </Button>
          </Link>
          <Button className="p-0 text-tertiary">
            <Trash strokeWidth={3} />
          </Button>
        </div>
      )}
    </div>
  );
};

export default OfficeCard;
