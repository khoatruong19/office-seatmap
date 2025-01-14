import { useEffect } from "react";
import { useMeMutation } from "@stores/auth/service";
import { Outlet, useNavigate, useLocation } from "react-router";
import { APP_ROUTES } from "@config/routes";
import cookieManagement from "@lib/js-cookie";
import ClipLoader from "react-spinners/ClipLoader";
import { useAuth } from "@/hooks/useAuth";

const AuthGuard = () => {
  const [me, { isLoading }] = useMeMutation();
  const { user } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  const handleGetCurrentUser = async () => {
    try {
      const data = await me(null);
      if ("error" in data) {
        navigate(APP_ROUTES.LOGIN);
        return;
      }
      if (location.pathname === APP_ROUTES.LOGIN) navigate(APP_ROUTES.HOME);
    } catch (error) {
      navigate(APP_ROUTES.LOGIN);
    }
  };

  useEffect(() => {
    const accessToken = cookieManagement.getAccessToken();
    if (!accessToken) {
      navigate(APP_ROUTES.LOGIN);
      return;
    }

    handleGetCurrentUser();
  }, []);

  if (isLoading)
    return (
      <div className="w-screen h-screen flex items-center justify-center">
        <ClipLoader color="#376380" />
      </div>
    );

  if (location.pathname !== APP_ROUTES.LOGIN && !user) return null;

  return <Outlet />;
};

export default AuthGuard;
