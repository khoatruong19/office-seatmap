import ReactDOM from "react-dom/client";
import App from "./App.tsx";
import "./styles/globals.css";
import { BrowserRouter } from "react-router-dom";
import AppProviders from "./providers/index.tsx";
import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import CheckIcon from "@assets/check.png";
import ErrorIcon from "@assets/remove.png";

ReactDOM.createRoot(document.getElementById("root")!).render(
  <BrowserRouter>
    <AppProviders>
      <App />
      <ToastContainer
        position="top-center"
        autoClose={2000}
        icon={({ type }) => {
          if (type === "success")
            return <img src={CheckIcon} className="w-10 h-auto object-cover" />;
          return <img src={ErrorIcon} className="w-10 h-auto object-cover" />;
        }}
      />
    </AppProviders>
  </BrowserRouter>
);
