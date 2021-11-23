module.exports = {
    purge: ['./src/**/*.{js,jsx,ts,tsx}', './public/index.html'],
    darkMode: 'class', // or 'media' or 'class'
    theme: {
        extend: {}
    },
    variants: {
        extend: {
            display: ['group-hover'],
            position: ['group-hover'],
            visibility: ['group-hover'],
        },
    },
    plugins: [],
}
