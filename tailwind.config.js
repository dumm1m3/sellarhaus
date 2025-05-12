import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#D6671D',     // Burnt orange from logo
                secondary: '#01132b',   // Dark navy
                accent: '#FDE9D9',      // Light orange/cream
                background: '#FAF9F6',  // Page background
                darkText: '#111827',    // Deep text color
            },
        },
    },

    plugins: [forms],
};
