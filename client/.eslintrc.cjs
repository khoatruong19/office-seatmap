module.exports = {
  root: true,
  env: { browser: true, es2020: true },
  extends: [
    "eslint:recommended",
    "plugin:@typescript-eslint/recommended",
    "plugin:react-hooks/recommended",
  ],
  ignorePatterns: ["dist", ".eslintrc.cjs"],
  parser: "@typescript-eslint/parser",
  plugins: ["react-refresh"],
  rules: {
    "react-refresh/only-export-components": [
      "warn",
      { allowConstantExport: true },
    ],
    "@typescript-eslint/ban-types": "off",
    "@typescript-eslint/no-non-null-asserted-optional-chain": "off",
    "react-hooks/exhaustive-deps": "off",
    "no-extra-boolean-cast": "off",
    "react-refresh/only-export-components": "off",
  },
};
