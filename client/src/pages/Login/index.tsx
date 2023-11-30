import LoginForm from "../../components/Login/LoginForm";

const Login = () => {
  return (
    <div className="w-screen h-screen bg-login-background bg-center bg-cover flex items-center justify-center">
      <LoginForm />
    </div>
  );
};

export default Login;

// const isAuth = true;
//   if (!isAuth) return <p>Not authenticated</p>;

//   return <Outlet />;
