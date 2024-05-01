/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$(document).ready(function() {
    console.log($('#toastr').length)
    if($('#toastr').length)
    {
        let toastr = $('#toastr').data('toastr');
        let message = $('#toastr').text();
        let title;
        switch (toastr) {
            case 'success':
                title = 'Yeay 🤩!';
                break;
            case 'error':
                title = 'Sorry 😔!';
                break;

            default:
                title = 'Hmmm 🤔!';
                break;
        }

        iziToast[toastr]({
            title: title,
            message: message,
            position: 'topRight'
        });
    }
});
