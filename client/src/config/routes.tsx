import { RouteObject } from "react-router-dom";
import Login from "../pages/Login";
import Home from "../pages/Home";
import AuthGuard from "../guards/AuthGuard";
import Office from "../pages/Office";
import OfficeEditing from "../pages/OfficeEditing";

const APP_ROUTES = {
  APP: "/",
  HOME: "/",
  OFFICE: "/offices/:id",
  OFFICE_EDITING: "/offices/:id/edit",
  LOGIN: "/login",
};

const ROUTES_CONFIG: RouteObject[] = [
  {
    path: APP_ROUTES.APP,
    element: <AuthGuard />,
    children: [
      { path: APP_ROUTES.HOME, element: <Home /> },
      { path: APP_ROUTES.OFFICE, element: <Office /> },
      { path: APP_ROUTES.OFFICE_EDITING, element: <OfficeEditing /> },
      {
        path: APP_ROUTES.LOGIN,
        element: <Login />,
      },
    ],
  },
];

export { APP_ROUTES, ROUTES_CONFIG };
