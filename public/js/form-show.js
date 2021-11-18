$(document).ready(function() {
    initializeMagnificPopup();
});

function initializeMagnificPopup() {

    $(".galleryItem").magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        gallery: { enabled:true }
    });
}
