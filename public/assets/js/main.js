// Header Scrolled
$(document).on('scroll', function() {
    $(window).scroll(function(){
        var scroll = $(window).scrollTop();
        if (scroll > 50) {
            $("header").addClass("scrolled");
        }
        else{
            $("header").removeClass("scrolled");
        }
    });
});
// Owl
$(document).ready(function() {
    $('.owl-carousel.clients-slider').owlCarousel({
        loop: false,
        nav: false,
        margin: 30,
        stagePadding: 0,
        responsive:{
            0:{
                dots: true,
                items:3,
            },
            992:{
                dots: true,
                items:4,
            },
            1200:{
                dots: false,
                items:6,
            },
        },
        autoplay:true,
        autoplayTimeout:4000,
        autoplayHoverPause:true
    });
})
$(document).ready(function () {
    $(".toggle-book").click(function (e) {
        e.preventDefault();
        $(this).toggleClass("active");
        $(".book-btns").toggleClass("active");
    })
})
// Time slots
$("ul.time-slots li button:not(:disabled)").click(function () {
    $("ul.time-slots li button").removeClass("active")
    $(this).addClass("active")
})
// WOW =====================
$(document).ready(function(){
    wow = new WOW
    (
        {
            boxClass: 'wow',            // default
            animateClass: 'animated',   // default
            offset: 1,                  // default
            mobile: false,               // default
            live: true                  // default
        }
    );
    wow.init()
});
