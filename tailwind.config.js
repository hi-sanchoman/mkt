const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    purge: [
        // prettier-ignore
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: false, // or 'media' or 'class'
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
            gray: colors.gray,
            indigo: {
                100: '#e6e8ff',
                300: '#b2b7ff',
                400: '#7886d7',
                500: '#6574cd',
                600: '#5661b3',
                800: '#2f365f',
                900: '#191e38',
            },
            skyblue: {
                100: '#f3faff',
                400: '#85ddff',
                500: '#6ad5ff',
                600: '#2196f3',
            },
            blue: {
                400: '#4A32E3',
                500: '#4F58DB',
            }
        },
        extend: {
            borderColor: theme => ({
                DEFAULT: theme('colors.gray.200', 'currentColor'),
            }),
            fontFamily: {
                sans: ['Cerebri Sans', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: theme => ({
                outline: '0 0 0 2px ' + theme('colors.indigo.500'),
            }),
            fill: theme => theme('colors'),
            fontSize: {
                '2xs': '0.65rem',
                '3xs': '0.6rem',
                '4xs': '0.55rem',
                '5xs': '0.5rem',
            },
        },
    },
    variants: {
        extend: {
            fill: ['focus', 'group-hover'],
        },
    },
    plugins: [],
}