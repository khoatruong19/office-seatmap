import { UserCircle } from "lucide-react";

const Header = () => {
  return (
    <header className="h-14 border-b-2 border-mainBlue">
      <nav className="h-full px-10 flex items-center justify-between">
        <h1 className="text-xl font-semibold text-mainBlue italic">
          Cybozu Seatmap
        </h1>
        <div className="flex items-center gap-2 hover-opacity py-2">
          <UserCircle size={30} />
          <h3 className="font-semibold text-lg">Admin</h3>
        </div>
      </nav>
    </header>
  );
};

export default Header;
