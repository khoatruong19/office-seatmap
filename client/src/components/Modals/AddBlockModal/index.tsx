import { SubmitHandler, useForm } from "react-hook-form";
import { AddBlockSchema, AddBlockSchemaType } from "@schema/form";
import { zodResolver } from "@hookform/resolvers/zod";
import Button from "@components/Form/Button";
import { useModalContext } from "@providers/ModalProvider";
import FieldControl from "@components/Form/FieldControl";
import { Building } from "lucide-react";
import { useClickOutside } from "@/hooks/useClickOutside";
import { useRef } from "react";

type Props = {
  confirmHandler: (blockName: string) => void;
  cancelHandler?: () => void;
};

const AddBlockModal = ({ confirmHandler, cancelHandler = () => {} }: Props) => {
  const { closeModal } = useModalContext();

  const modalRef = useRef<HTMLFormElement | null>(null);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<AddBlockSchemaType>({
    resolver: zodResolver(AddBlockSchema),
  });

  const onSubmit: SubmitHandler<AddBlockSchemaType> = (
    values: AddBlockSchemaType
  ) => {
    confirmHandler(values.name);
  };

  const handleCancel = () => {
    cancelHandler();
    closeModal();
  };

  useClickOutside(modalRef, cancelHandler);

  return (
    <form
      ref={modalRef}
      onSubmit={handleSubmit(onSubmit)}
      className="w-96 py-5 px-10"
    >
      <h3 className="text-center font-semibold text-3xl mb-2">Add new block</h3>

      <FieldControl
        field="Block Name"
        errors={errors}
        name="name"
        placeholder="Block name..."
        register={register}
        icon={<Building />}
        containerClass="mt-5"
      />

      <div className="flex items-center gap-4 mt-5">
        <Button
          type="button"
          onClick={handleCancel}
          className="mx-auto block rounded-lg text-primary hover:text-secondary w-fit"
        >
          Cancel
        </Button>
        <Button
          type="submit"
          className="mx-auto block rounded-lg disabled:bg-primary bg-secondary disabled:cursor-default disabled:hover:opacity-100"
        >
          Create
        </Button>
      </div>
    </form>
  );
};

export default AddBlockModal;
