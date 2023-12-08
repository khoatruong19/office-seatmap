import { SubmitHandler, useForm } from "react-hook-form";
import { AddOfficeSchema, AddOfficeSchemaType } from "@schema/form";
import { zodResolver } from "@hookform/resolvers/zod";
import Button from "@components/Form/Button";
import FieldControl from "@components/Form/FieldControl";
import { useModalContext } from "@providers/ModalProvider";
import { Building } from "lucide-react";

type Props = {
  confirmHandler: (name: string) => void;
};

const AddOfficeModal = ({ confirmHandler }: Props) => {
  const { closeModal } = useModalContext();

  const {
    register,
    handleSubmit,
    formState: { errors, isValid },
  } = useForm<AddOfficeSchemaType>({
    resolver: zodResolver(AddOfficeSchema),
  });

  const onSubmit: SubmitHandler<AddOfficeSchemaType> = (
    values: AddOfficeSchemaType
  ) => {
    confirmHandler(values.name);
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="w-96 py-5 px-10">
      <h3 className="text-center font-semibold text-3xl mb-2">
        Add new office
      </h3>

      <FieldControl
        field="Office Name"
        errors={errors}
        name="name"
        placeholder="Office name..."
        register={register}
        icon={<Building />}
        containerClass="mt-5"
      />

      <div className="flex items-center gap-4 mt-5">
        <Button
          type="button"
          onClick={closeModal}
          className="mx-auto block rounded-lg text-primary hover:text-secondary w-fit"
        >
          Cancel
        </Button>
        <Button
          disabled={!isValid}
          type="submit"
          className="mx-auto block rounded-lg disabled:bg-primary bg-secondary disabled:cursor-default disabled:hover:opacity-100"
        >
          Create
        </Button>
      </div>
    </form>
  );
};

export default AddOfficeModal;
