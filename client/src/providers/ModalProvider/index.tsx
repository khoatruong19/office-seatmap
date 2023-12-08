import {
  createContext,
  useState,
  cloneElement,
  useContext,
  ReactElement,
  ReactNode,
  useMemo,
} from "react";
import ModalLayout from "@components/Modals/ModalLayout";
import { MODALS, MODAL_ELEMENTS } from "./constants";

type ModalContextValues = {
  showModal: <T extends object>(name: MODALS, props: T) => void;
  showModalComponent: (element: ReactElement) => void;
  closeModal: () => void;
};

const defaultModalContextValues: ModalContextValues = {
  showModal: () => {},
  showModalComponent: () => {},
  closeModal: () => {},
};

export const ModalContext = createContext(defaultModalContextValues);

const ModalProvider = ({ children }: { children: ReactNode }) => {
  const [open, setOpen] = useState(false);
  const [component, setComponent] = useState<ReactElement | null>(null);

  const showModal = (name: MODALS, props = {}) => {
    setOpen(true);
    setComponent(cloneElement(MODAL_ELEMENTS[name], props));
  };

  const showModalComponent = (element: ReactElement) => {
    setOpen(true);
    setComponent(element);
  };

  const closeModal = () => {
    setOpen(false);
    setComponent(null);
  };

  const value: ModalContextValues = useMemo(
    () => ({
      showModal,
      showModalComponent,
      closeModal,
    }),
    []
  );

  return (
    <ModalContext.Provider value={value}>
      <ModalLayout isOpen={open} setIsOpen={setOpen}>
        {component}
      </ModalLayout>
      {children}
    </ModalContext.Provider>
  );
};
const useModalContext = () => useContext(ModalContext);

export { useModalContext };
export default ModalProvider;
