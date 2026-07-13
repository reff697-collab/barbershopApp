import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                coral: {
                    50: "#FFF3EF",
                    100: "#FFE1D6",
                    300: "#FFAF94",
                    400: "#FF9166",
                    500: "#FF6B4A",
                    600: "#E8532F",
                    700: "#C23F1F",
                },
                surface: "#F4F5F7",
            },
        },
    },

    plugins: [forms],
};