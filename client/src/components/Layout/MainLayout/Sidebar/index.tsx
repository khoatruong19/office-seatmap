import { Contact2, UserPlus, X } from "lucide-react";
import { useState } from "react";
import { cn } from "../../../../lib/clsx";
import { useModalContext } from "../../../../providers/ModalProvider";
import Button from "../../../Form/Button";
import UsersList from "./UsersList";
import { MODALS } from "../../../../providers/ModalProvider/constants";

const Sidebar = () => {
  const [open, setOpen] = useState(false);
  const { showModal } = useModalContext();

  const handleToggleOpen = () => setOpen((prev) => !prev);

  const handleOpenCreateModal = () =>
    showModal(MODALS.CREATE_USER, { type: "create" });

  return (
    <aside className="absolute right-0 top-0 gap-2 font-poppins">
      <div
        className={cn(
          "w-96 bg-primary h-[calc(100vh-4rem)] overflow-y-auto flex items-start transition-all duration-200 translate-x-0 border-l-2 border-primary",
          {
            "translate-x-[100%] w-0": !open,
          }
        )}
      >
        <div className="flex flex-col w-full pb-5 pt-8 ">
          <h1 className="text-3xl font-semibold text-center text-black">
            Users
          </h1>

          <UsersList />
        </div>

        <Button
          onClick={handleOpenCreateModal}
          className="absolute right-2 p-0 top-2 text-black"
        >
          <UserPlus />
        </Button>
        <Button
          onClick={handleToggleOpen}
          className={cn(
            `absolute top-1 w-fit px-3 py-1 transition-all
         duration-200 text-black`,
            {
              "right-0": !open,
              "left-[-18px]": open,
            }
          )}
        >
          <X size={30} className="mt-[-5px] ml-1.5" />
        </Button>
      </div>
      {!open && (
        <Button
          onClick={handleToggleOpen}
          className={cn(
            `absolute top-1 w-fit px-3 py-1 transition-all
         duration-200 text-black`,
            {
              "right-0": !open,
              "left-[-18px]": open,
            }
          )}
        >
          <Contact2 size={30} />
        </Button>
      )}
    </aside>
  );
};

export default Sidebar;
