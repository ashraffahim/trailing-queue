/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./views/**/*.php', './web/js/**/*.js'],
  theme: {
    extend: {
      fontFamily: {
        roboto: ['Roboto']
      }
    },
  },
  plugins: [
    function ({ addVariant }) {
        addVariant('child', '& > *');
        addVariant('child-hover', '& > *:hover');
    }
  ],
}

