import { UserRole } from "@schema/types";
import { useAuth } from "./useAuth";

const useCheckAdmin = () => {
  const { user } = useAuth();

  if (!user) return false;

  return user.role === UserRole.ADMIN;
};

export default useCheckAdmin;
