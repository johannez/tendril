// Special detection for IE11.
const { detect } = require('detect-browser');
const browser = detect();

if (browser.name === 'ie' && browser.version.indexOf('11') !== -1) {
  document.body.classList.add('ie11');

  let pictureFill = document.createElement('script');
  pictureFill.src = 'https://cdn.rawgit.com/scottjehl/picturefill/3.0.2/dist/picturefill.min.js';
  document.head.appendChild(pictureFill);
}


// Setup VueJs
import Vue from 'vue';

// Load global contrib components.
// import vSelect from "vue-select";
// Vue.component("v-select", vSelect);

// Load custom components
import Pager from './components/Pager';
Vue.component('pager', Pager);

import Search from './components/Search';
Vue.component('search', Search);

import MenuMobile from './components/MenuMobile';
Vue.component('menu-mobile', MenuMobile);

window.Vue = Vue;
window.axios = require('axios');

// Initionalize VueJS
new Vue({
  el: '#app'
});


