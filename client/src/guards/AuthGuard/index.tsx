import { useEffect } from "react";
import { useMeMutation } from "../../stores/auth/service";
import { Outlet, useNavigate } from "react-router";
import { APP_ROUTES } from "../../config/routes";
import cookieManagement from "../../lib/js-cookie";
import ClipLoader from "react-spinners/ClipLoader";

const AuthGuard = () => {
  const [me, { isLoading }] = useMeMutation();
  const navigate = useNavigate();

  useEffect(() => {
    const accessToken = cookieManagement.getAccessToken();
    if (!accessToken) return navigate(APP_ROUTES.LOGIN);

    me(null)
      .then((data) => {
        if ("error" in data) navigate(APP_ROUTES.LOGIN);
      })
      .catch(() => {
        navigate(APP_ROUTES.LOGIN);
      });
  }, []);

  if (isLoading)
    return (
      <div className="w-screen h-screen flex items-center justify-center">
        <ClipLoader />
      </div>
    );

  return <Outlet />;
};

export default AuthGuard;
