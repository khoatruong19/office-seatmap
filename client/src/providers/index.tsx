import React from "react";
import ModalProvider from "./ModalProvider";

import { Provider } from "react-redux";
import { store } from "../stores";

type Props = {
  children: React.ReactNode;
};

const AppProviders = ({ children }: Props) => {
  return (
    <>
      <Provider store={store}>
        <ModalProvider>{children}</ModalProvider>
      </Provider>
    </>
  );
};

export default AppProviders;
