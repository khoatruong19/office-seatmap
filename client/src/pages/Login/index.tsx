import LoginForm from "../../components/Login/LoginForm";

const Login = () => {
  return (
    <main className="w-screen h-screen bg-mainBlue flex items-center justify-center">
      <LoginForm />
    </main>
  );
};

export default Login;

// const isAuth = true;
//   if (!isAuth) return <p>Not authenticated</p>;

//   return <Outlet />;
