/**
 * @author Indra
 * javascript file tambahan buatan PT Batra
 */

$(document).ready(function(){
    function hideFlashMessage() {
        $('.flash-message').fadeOut(500);
    }
    function hideFlashSuccess(){
        $('.flash-success').fadeOut(500);
    }
    function hideFlashError(){
        $('.flash-error').fadeOut(500);
    }

    setTimeout(hideFlashSuccess, 8000);//Dalam 8 detik, flash message success akan hilang
    setTimeout(hideFlashError, 15000);//Dalam 15 detik, flash message error akan hilang

    //Jika button close pada flash message diklik, message akan hilang
    $('#flash-close-btn').click(function(){
        hideFlashMessage();
    });
});