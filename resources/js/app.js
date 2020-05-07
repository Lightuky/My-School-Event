window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

$('#btnSort').click(function() {
    $('#SortForm').css({"display": "block"});
});

$('#SortMenuPosts').click(function() {
    $('#SortMenuPosts').addClass("mt-3").removeClass("mt-5");
    $('#SortMenuHelps, #SortMenuEvents, #SortMenuAll').addClass("mt-5").removeClass("mt-3");
    $('#allHelps #ContentPosts').css({"display": "none"});
    $('#allPosts').css({"display": "block"});
    $('#allEvents, #allHelps').css({"display": "none"});
});

$('#SortMenuEvents').click(function() {
    $('#SortMenuEvents').addClass("mt-3").removeClass("mt-5");
    $('#SortMenuAll, #SortMenuHelps, #SortMenuPosts').addClass("mt-5").removeClass("mt-3");
    $('#allHelps #ContentPosts').css({"display": "none"});
    $('#allEvents').css({"display": "block"});
    $('#allPosts, #allHelps').css({"display": "none"});
});

$('#SortMenuHelps').click(function() {
    $('#SortMenuHelps').addClass("mt-3").removeClass("mt-5");
    $('#SortMenuAll, #SortMenuEvents, #SortMenuPosts').addClass("mt-5").removeClass("mt-3");
    $('#allHelps #ContentPosts').css({"display": "block"});
    $('#allHelps').css({"display": "block"});
    $('#allEvents, #allPosts').css({"display": "none"});
});

$('#SortMenuAll').click(function() {
    $('#SortMenuAll').addClass("mt-3").removeClass("mt-5");
    $('#SortMenuHelps, #SortMenuEvents, #SortMenuPosts').addClass("mt-5").removeClass("mt-3");
    $('#allHelps #ContentPosts').css({"display": "none"});
    $('#allHelps, #allPosts, #allEvents').css({"display": "block"});
});

$('.ShowComments').click(function() {
    $(this).text(($(this).text() === 'Montrer les commentaires') ? 'Masquer les commentaires' : 'Montrer les commentaires');
    $(this).closest("div.d-flex").next(".ContentsComments").toggleClass("d-none");
});

$('.ShowAnswer').click(function() {
    $(this).text(($(this).text() === 'Montrer la réponse la plus utile') ? 'Masquer la réponse la plus utile' : 'Montrer la réponse la plus utile');
    $(this).closest("div.d-flex").next(".BestAnswer").toggleClass("d-none");
});

$('.showCommentForm').click(function() {
    $(this).closest("div.col-10").find('.newcommentform').toggleClass("d-none");
});







