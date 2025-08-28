import $ from 'jquery';
window.$ = window.jQuery = $
import select2 from 'select2';
select2();
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});
