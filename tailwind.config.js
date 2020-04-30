module.exports = {
  theme: {
    extend: {
    colors: {
      green: {
        '100': '#F0FFF4',
        '200': '#C6F6D5',
        '300': '#9AE6B4',
        '400': '#68D391',
        '500': '#32ff7e',
        '600': '#38A169',
        '700': '#2F855A',
        '800': '#276749',
        '900': '#22543D',
      }
    },
    }
  },
  variants: {},
  plugins: [
    require('@tailwindcss/custom-forms')
  ]
}
