/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        mainBlue: "#6DB9EF",
        room: "#B99470",
      },
    },
  },
  plugins: [],
};
