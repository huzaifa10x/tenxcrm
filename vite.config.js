import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/remixicon.css',
                'resources/css/lib/bootstrap.min.css',
                'resources/css/lib/apexcharts.css',
                'resources/css/lib/dataTables.min.css',
                'resources/css/lib/editor-katex.min.css',
                'resources/css/lib/editor.atom-one-dark.min.css',
                'resources/css/lib/editor.quill.snow.css',
                'resources/css/lib/flatpickr.min.css',
                'resources/css/lib/full-calendar.css',
                'resources/css/lib/jquery-jvectormap-2.0.5.css',
                'resources/css/lib/magnific-popup.css',
                'resources/css/lib/slick.css',
                'resources/css/lib/prism.css',
                'resources/css/lib/file-upload.css',
                'resources/css/lib/audioplayer.css',
                'resources/css/style.css',
                'resources/js/lib/jquery-3.7.1.min.js',
                'resources/js/lib/bootstrap.bundle.min.js',
                'resources/js/lib/apexcharts.min.js',
                'resources/js/lib/dataTables.min.js',
                'resources/js/lib/iconify-icon.min.js',
                'resources/js/lib/jquery-ui.min.js',
                'resources/js/lib/jquery-jvectormap-2.0.5.min.js',
                'resources/js/lib/jquery-jvectormap-world-mill-en.js',
                'resources/js/lib/magnifc-popup.min.js',
                'resources/js/lib/slick.min.js',
                'resources/js/lib/prism.js',
                'resources/js/lib/file-upload.js',
                'resources/js/lib/audioplayer.js',
                'resources/js/app.js',
                'resources/js/homeOneChart.js',
            ],
            refresh: true,
        }),
    ],
});
