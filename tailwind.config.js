const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            black: colors.black,
            white: colors.white,
            red: colors.red,
            orange: colors.orange,
            yellow: colors.yellow,
            green: colors.green,
            gray: colors.blueGray,
            indigo: {
              100: '#e6e8ff',
              300: '#b2b7ff',
              400: '#7886d7',
              500: '#6574cd',
              600: '#5661b3',
              700: '#40497d',
              800: '#2f365f',
              900: '#191e38',
            },
          },
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
