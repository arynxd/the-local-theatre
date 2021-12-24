module.exports = {
    mode: 'jit',
    darkMode: 'class',
    content: ['./src/**/*.{ts,tsx}'],
    theme: {
        extend: {},
    },
    plugins: [],
    variants: {
        extend: {
            display: ['group-hover'],
            position: ['group-hover'],
            visibility: ['group-hover'],
        },
    },
}
