/** @type {import('tailwindcss').Config} */

import defaultTheme from "tailwindcss/defaultTheme";

export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  theme: {
    extend: {
      backgroundImage: {
        "login-background": "url('/src/assets/login-background.jpg')",
      },
      colors: {
        primary: "#97DEFF",
        secondary: "#164863",
        tertiary: "#427D9D",
        danger: "#EB455F",
        room: "#B99470",
      },
      fontFamily: {
        poppins: ["Poppins", ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [],
};
