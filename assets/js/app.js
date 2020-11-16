// app.js
import Vue from 'vue';
import Ddown from './components/Ddown'

/**
* Create a fresh Vue Application instance
*/
new Vue({
  el: '#app',
  components: {Ddown},
  
}
);

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything

// create global $ and jQuery variables
global.$ = global.jQuery = $;

require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});



