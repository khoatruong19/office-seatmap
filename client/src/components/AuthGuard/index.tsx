import { useEffect } from "react";
import { useMeMutation } from "../../stores/auth/service";
import { Outlet, useNavigate } from "react-router";
import { APP_ROUTES } from "../../config/routes";
import Cookies from "js-cookie";
import { ACCESS_TOKEN_KEY } from "../../config/api";

const AuthGuard = () => {
  const [me, { isLoading }] = useMeMutation();
  const navigate = useNavigate();

  useEffect(() => {
    const handleGetMe = async () => {
      const accessToken = Cookies.get(ACCESS_TOKEN_KEY);
      if (!accessToken) return navigate(APP_ROUTES.LOGIN);

      me(null)
        .then(() => navigate(APP_ROUTES.HOME))
        .catch(() => navigate(APP_ROUTES.LOGIN));
    };
    handleGetMe();
  }, []);

  if (isLoading) return "Loading...";

  return <Outlet />;
};

export default AuthGuard;
