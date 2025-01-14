import { useAuth } from "@hooks/useAuth";
import UserDropdown from "./UserDropdown";
import { useRef, useState } from "react";
import { useClickOutside } from "@hooks/useClickOutside";
import Logo from "@assets/logo.jpg";
import DefaultAvatar from "@assets/default-avatar.png";
import { Link } from "react-router-dom";
import { APP_ROUTES } from "@config/routes";

const Header = () => {
  const { user } = useAuth();
  const dropdownRef = useRef<HTMLDivElement | null>(null);

  const [openDropdown, setOpenDropdown] = useState(false);

  const handleToggleDropdown = () => setOpenDropdown((prev) => !prev);

  const handleCloseDropdown = () => setOpenDropdown(false);

  useClickOutside(dropdownRef, handleCloseDropdown);

  return (
    <header className="h-16 bg-primary ">
      <nav className="w-full h-full px-6 flex items-center justify-between text-secondary border-b-[1px] border-tertiary ">
        <Link
          to={APP_ROUTES.HOME}
          className="flex items-center gap-3 hover-opacity"
        >
          <img
            src={Logo}
            className="w-11 h-11 rounded-full object-cover shadow-sm"
            alt=""
          />
          <h1 className="text-2xl font-semibold italic font-poppins ">
            Cybozu Seatmap
          </h1>
        </Link>
        <div ref={dropdownRef} className="relative">
          <div
            onClick={handleToggleDropdown}
            className="flex items-center gap-2 hover-opacity py-2"
          >
            <img
              src={user?.avatar ?? DefaultAvatar}
              className="w-10 h-10 object-contain rounded-full"
              alt=""
            />
            <div>
              <h3 className="font-semibold text-lg mb-[-5px] max-w-[200px] truncate">
                {user?.full_name}
              </h3>
              <span className="text-sm">{user?.role}</span>
            </div>
          </div>
          {openDropdown && <UserDropdown close={handleCloseDropdown} />}
        </div>
      </nav>
    </header>
  );
};

export default Header;
