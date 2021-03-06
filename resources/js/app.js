window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

$('#btnSort').click(function() {
    $('#SortForm').toggleClass("d-none");
});

$('#SortMenuPosts').click(function() {
    $('#SortMenuPosts').addClass("mt-3").removeClass("mt-5");
    $('#SortMenuHelps, #SortMenuEvents, #SortMenuAll').addClass("mt-5").removeClass("mt-3");
    $('.allContentEventForm, .allContentHelpForm, .allContentEvent, .allContentHelp').css({"display": "none"});
    $('.allContentPost, .allContentPostForm').css({"display": "block"});
});

$('#SortMenuEvents').click(function() {
    $('#SortMenuEvents').addClass("mt-3").removeClass("mt-5");
    $('#SortMenuAll, #SortMenuHelps, #SortMenuPosts').addClass("mt-5").removeClass("mt-3");
    $('.allContentPostForm, .allContentHelpForm, .allContentPost, .allContentHelp').css({"display": "none"});
    $('.allContentEvent, .allContentEventForm').css({"display": "block"});
});

$('#SortMenuHelps').click(function() {
    $('#SortMenuHelps').addClass("mt-3").removeClass("mt-5");
    $('#SortMenuAll, #SortMenuEvents, #SortMenuPosts').addClass("mt-5").removeClass("mt-3");
    $('.allContentPostForm, .allContentEventForm, .allContentPost, .allContentEvent').css({"display": "none"});
    $('.allContentHelpForm, .allContentHelp').css({"display": "block"});
});

$('#SortMenuAll').click(function() {
    $('#SortMenuAll').addClass("mt-3").removeClass("mt-5");
    $('#SortMenuHelps, #SortMenuEvents, #SortMenuPosts').addClass("mt-5").removeClass("mt-3");
    $('.allContentEventForm, .allContentHelpForm').css({"display": "none"});
    $('.allContentEvent, .allContentHelp, .allContentPost, .allContentPostForm').css({"display": "block"});
});

$('.ShowComments').click(function() {
    $(this).addClass('d-none');
    $(this).next().removeClass('d-none');
    $(this).closest(".card-body").next(".card-body").find('.ContentsComments').removeClass("d-none");
});

$('.HideComments').click(function() {
    $(this).addClass('d-none');
    $(this).prev().removeClass('d-none');
    $(this).closest(".card-body").next(".card-body").find('.ContentsComments').addClass("d-none");
});

$('.ShowAnswer').click(function() {
    $(this).text(($(this).text() === 'Montrer la réponse la plus utile') ? 'Masquer la réponse la plus utile' : 'Montrer la réponse la plus utile');
    $(this).closest("div.d-flex").next(".BestAnswer").toggleClass("d-none");
});

$('.showCommentForm').click(function() {
    $(this).closest("div.col-10").find('.newcommentform').toggleClass("d-none");
});

$('.showAnswerForm').click(function() {
    $(this).closest("#newAnswer").find('.newcommentform').toggleClass("d-none");
});

$('.dropdownButtonPosts').click(function() {
    $(this).children().toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
    $(this).next().toggleClass('d-none');
});

$('#deleteCommentBlock div.position-relative').hover(function() {
    $('#deleteCommentBlock a.btn-outline-danger i').removeClass("text-danger").addClass("text-white");}, function() {$('#deleteCommentBlock a.btn-outline-danger i').removeClass("text-white").addClass("text-danger");}
);

if ($("section").hasClass("404")) {
    $("#header form").hide();
}

if (!$("section").hasClass("profile")) {
    $("#header").removeClass("d-none").addClass("d-flex");
}

if ($("section").hasClass("chatPage")) {
    let messageFeed = document.getElementById("messageFeed");
    messageFeed.scrollTop = messageFeed.scrollHeight;
}

$('.postAttachmentsImgur').click(function() {
    $(this).closest("#addPostForm").find('.inputLinkImgur').toggleClass("d-none");
    $(this).closest("#addPostForm").find('.inputLinkYoutube').addClass("d-none");
});

$('.postAttachmentsYoutube').click(function() {
    $(this).closest("#addPostForm").find('.inputLinkYoutube').toggleClass("d-none");
    $(this).closest("#addPostForm").find('.inputLinkImgur').addClass("d-none");
});







