import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import glob from 'glob';

// Function to get all JS and CSS files dynamically
const getCssFiles = () => glob.sync('resources/css/**/*.css');
const getJsFiles = () => glob.sync('resources/js/**/*.js');

export default defineConfig({
  plugins: [
    laravel({
      input: [...getCssFiles(), ...getJsFiles()],
      refresh: true,
    }),
  ],
});
