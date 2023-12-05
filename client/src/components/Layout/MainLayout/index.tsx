import React from "react";
import Header from "./Header";

type Props = {
  children: React.ReactNode;
};

function MainLayout({ children }: Props) {
  return (
    <div className="min-h-screen font-mono flex flex-col w-screen overflow-x-hidden ">
      <Header />
      {children}
    </div>
  );
}

export default MainLayout;
