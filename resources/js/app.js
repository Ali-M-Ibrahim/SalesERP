import './bootstrap';

import $ from 'jquery';

window.$ = $;
window.jQuery = $;

// Load Select2 after jQuery is global
import select2 from 'select2';
select2(window, $);

// Select2 CSS
import 'select2/dist/css/select2.min.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { registerSW } from 'virtual:pwa-register';


const updateSW = registerSW({
    immediate: true,

    onNeedRefresh() {
        console.log('New application version available.');

        updateSW(true);
    },

    onOfflineReady() {
        console.log('Application is ready to work offline.');
    },
});
