// Magnific poup expects <a href="url"><images src="url></a>
// But on a slow (or not so slow) connection network, we will click on the link before jquery has a chance
// to attach his click handler to override the default behavior to open a link
// So we do instead <a href="#" data-href="url"><images src="url></a>
// and when jquery is ready, we rewrite the url

$(window).load(function () {

    let items = [];

    $( "a[data-href]" ).each(function( i ) {
        let href = this.getAttribute("data-href");
        items.push({ 'src': href});
        if (href) {
            this.href = href;
        }
    });

    $('.parent-container').each(function() {

        $(this).find(".galleryItem").magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            gallery: { enabled:true }
        });
    });

    $("#all-images").magnificPopup({
        items: items,
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        gallery: { enabled:true }
    });
});
