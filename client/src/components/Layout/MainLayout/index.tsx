import React from "react";
import Header from "./Header";
import useCheckAdmin from "../../../hooks/useCheckAdmin";
import Sidebar from "./Sidebar";

type Props = {
  children: React.ReactNode;
};

function MainLayout({ children }: Props) {
  const isAdmin = useCheckAdmin();
  return (
    <div className="min-h-screen font-mono flex flex-col w-screen overflow-x-hidden ">
      <Header />
      <div className="relative w-full pt-10 flex-1">
        {children}

        {isAdmin && <Sidebar />}
      </div>
    </div>
  );
}

export default MainLayout;
