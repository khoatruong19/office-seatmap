const BASE_URL = import.meta.env.VITE_BASE_API_URL;

const ENDPOINTS = {
  AUTH: "auth",
  USER: "users",
  OFFICE: "offices",
  SEAT: "seats",
};

const ACCESS_TOKEN_KEY = "accessToken";

export { BASE_URL, ENDPOINTS, ACCESS_TOKEN_KEY };
