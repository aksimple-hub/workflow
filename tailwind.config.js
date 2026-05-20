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
                'brand-dark':       '#1E3A5F',
                'brand-dark-mid':   '#2C5282',
                'brand-green':      '#10B981',
                'brand-green-dark': '#059669',
            },
        },
    },

    plugins: [forms],
};
