module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js" // set up the path to the flowbite package
  ],
  theme: {
    extend: {
      colors:{
        col1 : '#f4fefd',
        col2 : '#0ef6cc',
        col3 : '#3a4f50',
        col4 : '#1b2223',

      }
    },
  },
  plugins: [
    require('flowbite/plugin') // add the flowbite plugin
  ],
}

