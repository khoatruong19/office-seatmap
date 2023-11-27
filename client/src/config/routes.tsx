import { RouteObject } from "react-router-dom";
import Login from "../pages/Login";
import Home from "../pages/Home";

const routesConfig: RouteObject[] = [
  {
    path: "",
    element: <Home />,
  },
  {
    path: "/login",
    element: <Login />,
  },
];

export default routesConfig;
