import { useModalContext } from "../../../providers/ModalProvider";
import Button from "../../Form/Button";

type Props = {
  text?: string;
  confirmHandler: () => void;
  cancelHandler?: () => void;
  isLoading?: boolean;
};

const ConfirmModal = (props: Props) => {
  const {
    text = "Are you sure you want to do this action?",
    confirmHandler,
    cancelHandler = () => {},
    isLoading = false,
  } = props;
  const { closeModal } = useModalContext();

  const handleCancel = () => {
    cancelHandler();
    closeModal();
  };

  return (
    <div className="w-96 py-8 px-5">
      <p className="text-center font-semibold text-lg">{text}</p>

      <div className="flex items-center justify-center mt-8 ">
        <Button
          type="button"
          onClick={handleCancel}
          className="rounded-lg text-primary hover:text-secondary w-fit"
        >
          No
        </Button>
        <Button
          disabled={isLoading}
          onClick={confirmHandler}
          type="submit"
          className="mx-auto block rounded-lg disabled:bg-primary bg-secondary disabled:cursor-default disabled:hover:opacity-100"
        >
          Yes
        </Button>
      </div>
    </div>
  );
};

export default ConfirmModal;
