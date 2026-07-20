import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Livewire/**/*.php',
    ],
    theme: {
        extend: {
            colors: { brand: { DEFAULT: '#0E7C86', dark: '#0F5E5A', accent: '#F5A623' } },
            fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
        },
    },
    plugins: [forms, typography],
};
