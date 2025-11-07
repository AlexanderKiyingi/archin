// --------------------- home 1 --------------------

$(function () {

    // ------------ team images width same height -----------
    var images = $(".tc-team-style1 .team-card .img, .img_sm_h");
    images.each(function () {
        var width = $(this).width();
        $(this).height(width);
    });
});


// ------------ swiper sliders -----------
$(document).ready(function () {

    // ------------ tc-header-slider1 -----------
    var swiper = new Swiper(".tc-header-style1 .header-slider", {
        slidesPerView: 1,
        spaceBetween: 0,
        centeredSlides: true,
        speed: 1500,
        parallax: true,
        pagination: {
            el: ".header-slider .swiper-pagination",
            type: "fraction",
        },
        navigation: {
            nextEl: ".header-slider .swiper-button-next",
            prevEl: ".header-slider .swiper-button-prev",
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
        },
        loop: true,
        on: {
            init: function () {
                var swiper = this;
                for (var i = 0; i < swiper.slides.length; i++) {
                    $(swiper.slides[i])
                        .find(".img")
                        .attr({
                            "data-swiper-parallax": 0.75 * swiper.width,
                        });
                }
            },
            resize: function () {
                this.update();
            },
        },
    });

    // ------------ tc-testimonials-slider1 (Individual Sliders) -----------
    // Initialize each testimonial slider separately
    $(".testimonial-individual-slider .clients-slider1").each(function(index) {
        var $slider = $(this);
        var sliderId = $slider.attr('class').match(/testimonial-slider-\d+/);
        var sliderClass = sliderId ? sliderId[0] : 'testimonial-slider-' + (index + 1);
        
        var swiper = new Swiper($slider[0], {
            slidesPerView: 1,
            spaceBetween: 0,
            centeredSlides: true,
            speed: 1000,
            pagination: {
                el: $slider.find(".swiper-pagination")[0],
                type: "fraction",
            },
            navigation: {
                nextEl: $slider.find(".swiper-button-next")[0],
                prevEl: $slider.find(".swiper-button-prev")[0],
            },
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 6000,
            },
            loop: true,
        });
    });
    
    // Fallback for single slider (if no individual sliders found)
    if ($(".testimonial-individual-slider").length === 0) {
        var swiper = new Swiper(".tc-testimonials-style1 .clients-slider1", {
            slidesPerView: 1,
            spaceBetween: 0,
            centeredSlides: true,
            speed: 1000,
            pagination: {
                el: ".tc-clients-style1 .swiper-pagination",
                type: "fraction",
            },
            navigation: {
                nextEl: ".tc-clients-style1 .swiper-button-next",
                prevEl: ".tc-clients-style1 .swiper-button-prev",
            },
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 6000,
            },
            loop: true,
        });
    }

    // ------------ tc-projects-slider1 -----------
    var swiper = new Swiper(".tc-projects-style1 .projects-slider", {
        slidesPerView: 1,
        spaceBetween: 30,
        // centeredSlides: true,
        speed: 1000,
        pagination: false,
        navigation: false,
        mousewheel: false,
        keyboard: true,
        loop: false,
        autoplay: false,
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 1,
            },
            787: {
                slidesPerView: 1.5,
            },
            991: {
                slidesPerView: 2,
            },
            1200: {
                slidesPerView: 2,
            },
        },
    });

    // ------------ tc-testimonials-style1 -----------
    var swiper = new Swiper(".tc-testimonials-style1 .marq-slider", {
        slidesPerView: "auto",
        spaceBetween: 140,
        centeredSlides: true,
        slidesPerView: "auto",
        speed: 10000,
        autoplay: {
            delay: 1,
        },
        loop: true,
        //   allowTouchMove: false,
        disableOnInteraction: true,
    });

    // ------------ tc-blog-slider1 -----------
    var swiper = new Swiper(".tc-blog-style1 .blog-slider", {
        slidesPerView: 1,
        spaceBetween: 100,
        // centeredSlides: true,
        speed: 1000,
        pagination: false,
        navigation: {
            nextEl: '.tc-blog-style1 .swiper-button-next',
            prevEl: '.tc-blog-style1 .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        loop: false,
        autoplay: {
            delay: 6000,
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 1,
            },
            787: {
                slidesPerView: 1.5,
            },
            991: {
                slidesPerView: 2,
            },
            1200: {
                slidesPerView: 2,
            },
        },
    });

});


// --------------------- home 2 --------------------
// ------------ swiper sliders -----------
$(document).ready(function() {

    // head-slider
    var swiper = new Swiper('.tc-header-style2 .head-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        centeredSlides: true,
        speed: 1000,
        pagination: {
            el: '.tc-header-style2 .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.tc-header-style2 .swiper-button-next',
            prevEl: '.tc-header-style2 .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
        },
        loop: true,
    });

    // projects-style2
    var swiper = new Swiper('.tc-projects-style2 .projects-slider', {
        slidesPerView: 3,
        spaceBetween: 30,
        centeredSlides: true,
        speed: 1000,
        pagination: {
            el: '.tc-projects-style2 .swiper-pagination',
            clickable: true,
        },
        navigation: false,
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 7000,
        },
        loop: true,
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            787: {
                slidesPerView: 2,
            },
            991: {
                slidesPerView: 2,
            },
            1200: {
                slidesPerView: 3,
            }
        }
    });

    // testimonials img slider
    var swiper = new Swiper('.tc-testimonials-style2 .img-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        // centeredSlides: true,
        speed: 1000,
        pagination: false,
        navigation: {
            nextEl: '.tc-testimonials-style2 .img-slider .swiper-button-next',
            prevEl: '.tc-testimonials-style2 .img-slider .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
        },
        loop: true,
    });

    // ------------ tc-testimonials-style2 -----------
    var swiper = new Swiper(".tc-testimonials-style2 .text-slider", {
        slidesPerView: 1,
        spaceBetween: 30,
        centeredSlides: true,
        speed: 1000,
        pagination: {
        el: ".text-side .swiper-pagination",
        type: "fraction",
        },
        navigation: {
        nextEl: ".text-side .swiper-button-next",
        prevEl: ".text-side .swiper-button-prev",
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
        delay: 6000,
        },
        loop: true,
    });

});


// ------------ scripts -----------
$(document).ready(function(){
    // ------------  File upload BEGIN ------------
    $('.file__input--file').on('change', function(event) {
        var files = event.target.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            $("<div class='file__value'> <div class='file__value--text'>" + file.name + "</div><div class='file__value--remove' data-id='" + file.name + "' > <i class='fa fa-times'></i> </div></div>").appendTo('.file__value_content');
        }
    });

    //Click to remove item
    $('body').on('click', '.file__value', function() {
        $(this).remove();
    });
    // ------------ File upload END ------------ 
});


// --------------------- home 3 --------------------
// ------------ swiper sliders -----------
$(document).ready(function() {

    // ------------ tc-header-slider1 -----------
    var swiper = new Swiper(".tc-header-style3 .header-slider", {
        slidesPerView: 1,
        spaceBetween: 0,
        centeredSlides: true,
        speed: 1500,
        parallax: true,
        pagination: {
            el: ".header-slider .swiper-pagination",
            type: "fraction",
        },
        navigation: {
            nextEl: ".header-slider .swiper-button-next",
            prevEl: ".header-slider .swiper-button-prev",
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        loop: true,
        on: {
            init: function () {
                var swiper = this;
                for (var i = 0; i < swiper.slides.length; i++) {
                    $(swiper.slides[i])
                        .find(".img")
                        .attr({
                            "data-swiper-parallax": 0.75 * swiper.width,
                        });
                }
            },
            resize: function () {
                this.update();
            },
        },
    });

    // ------------ tc-portfolio-style3 -----------
    var swiper = new Swiper(".tc-portfolio-style3 .projects-slider", {
        slidesPerView: 1,
        spaceBetween: 50,
        centeredSlides: true,
        effect: "fade",
        speed: 1500,
        parallax: true,
        pagination: false,
        navigation: {
            nextEl: ".tc-portfolio-style3 .swiper-button-next",
            prevEl: ".tc-portfolio-style3 .swiper-button-prev",
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        loop: true,
    });

});

// ------------  File upload BEGIN ------------
$(document).ready(function() {
    
    $('.file__input--file').on('change', function(event) {
        var files = event.target.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            $("<div class='file__value'> <div class='file__value--text'>" + file.name + "</div><div class='file__value--remove' data-id='" + file.name + "' > <i class='la la-close'></i> </div></div>").appendTo('.file__value_content');
        }
    });

    //Click to remove item
    $('body').on('click', '.file__value', function() {
        $(this).remove();
    });
    
});


// --------------------- home 4 --------------------

// ------------ swiper sliders -----------
$(document).ready(function() {

    // clients slider 
    var swiper = new Swiper('.tc-clients-style4 .clients-slider', {
        slidesPerView: 3,
        spaceBetween: 50,
        speed: 1000,
        pagination: false,
        navigation: {
            nextEl: '.tc-clients-style4 .swiper-button-next',
            prevEl: '.tc-clients-style4 .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: false,
        loop: false,
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            787: {
                slidesPerView: 2,
            },
            991: {
                slidesPerView: 3,
            },
            1200: {
                slidesPerView: 3,
            }
        }
    });

});


// --------------------- home 5 --------------------
$( function() {


    // h-accordion__panel 
    var panel = $('.acc-li');
        panel.click( function(){
        // console.log('click');
        panel.removeClass('is-open');

        $(this).addClass('is-open');
    });


});


// ------------ swiper sliders -----------
$(document).ready(function() {

    // tc-location-style5
    var swiper = new Swiper('.tc-location-style5 .location-slider', {
        slidesPerView: 0,
        spaceBetween: 30,
        effect: "fade",
        centeredSlides: true,
        speed: 1000,
        pagination: false,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
        },
        loop: true,
    });

});


// --------------------- home 6 --------------------
// ------------ swiper sliders -----------
$(document).ready(function() {

    // ------------ tc-top-nav-style6  -----------
    var swiper = new Swiper('.tc-top-nav-style6 .text-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        centeredSlides: true,
        speed: 1000,
        pagination: false,
        navigation: {
            nextEl: '.text-slider .swiper-button-next',
            prevEl: '.text-slider .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
        },
        loop: true,
    });


    // ------------ tc-header-style6  -----------
    var swiper = new Swiper('.tc-header-style6 .header-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        effect: "fade",
        centeredSlides: true,
        speed: 1000,
        pagination: false,
        navigation: {
            nextEl: '.tc-header-style6 .swiper-button-next',
            prevEl: '.tc-header-style6 .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
        },
        loop: false,
    });


    // ------------ products-slider  -----------
    var swiper = new Swiper('.tc-featured-style6 .products-slider', {
        slidesPerView: 5,
        spaceBetween: 30,
        centeredSlides: true,
        speed: 1000,
        pagination: {
            el: '.products-slider .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.products-slider .swiper-button-next',
            prevEl: '.products-slider .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 5000,
        },
        loop: true,
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            787: {
                slidesPerView: 3,
            },
            991: {
                slidesPerView: 3,
            },
            1200: {
                slidesPerView: 3,
            }
        }
    });


    // ------------ tc-by-style-style6  -----------
    var swiper = new Swiper('.tc-by-style-style6 .style-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        centeredSlides: true,
        speed: 1000,
        pagination: false,
        navigation: {
            prevEl: '.tc-by-style-style6 .swiper-button-prev',
            nextEl: '.tc-by-style-style6 .swiper-button-next',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
        },
        loop: true,
    });
    

});


// ------------ scripts -----------
$(document).ready(function(){

    // -------- parralax img mouse move -------
    $(function () {
        
        var b = document.getElementsByTagName("BODY")[0];  

        b.addEventListener("mousemove", function(event) {
        parallaxed(event);

        });

        function parallaxed(e) {
            var amountMovedX = (e.clientX * -0.3 / 8);
            var amountMovedY = (e.clientY * -0.3 / 8);
            var x = document.getElementsByClassName("parallaxed");
            var i;
            for (i = 0; i < x.length; i++) {
                x[i].style.transform='translate(' + amountMovedX + 'px,' + amountMovedY + 'px)'
            }
        }
    });
    
});


// --------------------- home 7 --------------------
$( function() {
    

    // projects change background 
    $('.tc-projects-style7 .swiper-slide').on('mouseenter click', function () {
        var tab_id = $(this).attr('data-tab');
        $('.tc-projects-style7 .swiper-slide').removeClass('current');
        $(this).addClass('current');

        $('.glry-img .tab-img ').removeClass('current');
        $("#" + tab_id).addClass('current');

        if ($(this).hasClass('current')) {
            return false;
        }
    });

    $(".tc-projects-style7 .swiper-button-next").on('click' ,function(){
        $(".tc-projects-style7 .swiper-slide-next").click();
    })
    $(".tc-projects-style7 .swiper-button-prev").on('click' ,function(){
        $(".tc-projects-style7 .swiper-slide-prev").click();
    })
    // end projects change background 



    // proccess change img 
    $('.tc-proccess-style7 .accordion-item').on('click', function () {
        var tab_id = $(this).attr('data-tab');
        $('.tc-proccess-style7 .accordion-item').removeClass('current');
        $(this).addClass('current');

        $('.tc-proccess-style7 .prc-img').removeClass('current');
        $("#" + tab_id).addClass('current');

        if ($(this).hasClass('current')) {
            return false;
        }
    });

});


// ------------ swiper sliders -----------
$(document).ready(function() {

    var swiper = new Swiper('.tc-projects-style7 .projects-slider', {
        spaceBetween: 0,
        speed: 1000,
        pagination: false,
        navigation: {
            nextEl: '.tc-projects-style7 .swiper-button-next',
            prevEl: '.tc-projects-style7 .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: false,
        loop: false,
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            787: {
                slidesPerView: 3,
            },
            991: {
                slidesPerView: 4,
            },
            1200: {
                slidesPerView: 4,
            }
        }
    });


    var swiper = new Swiper('.tc-testimonials-style7 .testi-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        centeredSlides: true,
        speed: 1000,
        pagination: false,
        navigation: {
            nextEl: '.tc-testimonials-style7 .swiper-button-next',
            prevEl: '.tc-testimonials-style7 .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 5000,
        },
        loop: true,
    });


    // ------------ tc-awards-slider1 -----------
    var swiper = new Swiper('.tc-awards-slider1', {
        spaceBetween: 60,
        centeredSlides: true,
        slidesPerView: "auto",
        speed: 10000,
        autoplay: {
            delay: 1,
        },
        loop: true,
    //   allowTouchMove: false,
        disableOnInteraction: true,
    });

});



// --------------------- home 8 --------------------
$( function() {
    

    // projects change background 
    $('.tc-portfolio-style8 .nav-pills .nav-item').on('mouseenter', function () {
        var tab_id = $(this).attr('data-tab');
        $('.tc-portfolio-style8 .nav-pills .nav-item').removeClass('current');
        $(this).addClass('current');

        $('.tc-portfolio-style8 .imgs .tab-img').removeClass('current');
        $("#" + tab_id).addClass('current');

        if ($(this).hasClass('current')) {
            return false;
        }
    });

    $('.tc-portfolio-style8 .nav-link').mouseenter(function(){
        $(this).click();
    });

    // ------ sidemenu 1 ------
    $(".menu-icon").on("click", function() {
        $(this).toggleClass("active");
        $("#side_menu").toggleClass("show");
        $(".side_overlay").toggleClass("show");
    })

    $(".side_menu_cls").on("click", function() {
        $("#side_menu").removeClass("show");
        $(".side_overlay").removeClass("show");
    })

    $(".side_overlay").on("click", function() {
        $("#side_menu").removeClass("show");
        $(this).removeClass("show");
    })

});


// ------------ swiper sliders -----------
$(document).ready(function() {


    // ------------ fullpage-slider8 -----------
    var Vswiper = new Swiper('.fullpage-slider8', {
        slidesPerView: 1,
        spaceBetween: 0,
        // centeredSlides: true,
        speed: 1000,
        direction: 'vertical',
        pagination: {
            el: '.full-page-pagination .swiper-pagination',
            clickable: true,
        },
        navigation: false,
        mousewheel: false,
        keyboard: true,
        autoplay: false,
        loop: false,
        mousewheel: {
            releaseOnEdges: true,
        },
        breakpoints: {
            0: {
                mousewheel: false,
                direction: 'horizontal',
            },
            480: {
                mousewheel: false,
                direction: 'horizontal',
            },
            787: {
                mousewheel: false,
                direction: 'horizontal',
            },
            991: {
                mousewheel: false,
                direction: 'horizontal',
            },
            1200: {
                // slidesPerView: 5,
            }
        },
        on: {
            slideChange: function () {
              // Check if the active slide has the specified classes and add 'active' to body
              if ($(this.slides[this.activeIndex]).hasClass('reverse-color')) {
                $('body').addClass('reverse-color');
              } else {
                $('body').removeClass('reverse-color');
              }
            },
        },
    });

    // ------------ clients-slider -----------
    var swiper = new Swiper('.clients-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        centeredSlides: true,
        speed: 1000,
        pagination: {
            el: '.clients-slider .swiper-pagination',
            type: 'fraction',
        },
        navigation: {
            nextEl: '.clients-arrows .swiper-button-next',
            prevEl: '.clients-arrows .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 5000,
        },
        loop: true,
    });

});






// --------------------- home 9 --------------------

$( function() {

    // ------------ team images width same height -----------
    var images = $(".tc-team-style10 .team-card .img");
    images.each(function() {
      var width = $(this).width();
      $(this).height(width);
    });
    

});


// ------------ swiper sliders -----------
$(document).ready(function() {

  // ------------ tc-header-slider1 -----------
    var swiper = new Swiper('.tc-header-style10 .header-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        centeredSlides: true,
        speed: 1500,
        parallax: true,
        pagination: {
            el: '.header-slider .swiper-pagination',
            type: 'fraction',
          },
          navigation: {
            nextEl: '.header-slider .swiper-button-next',
            prevEl: '.header-slider .swiper-button-prev',
          },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 6000,
        },
        loop: true,
        on: {
          init: function () {
              var swiper = this;
              for (var i = 0; i < swiper.slides.length; i++) {
                  $(swiper.slides[i])
                      .find('.img')
                      .attr({
                          'data-swiper-parallax': 0.75 * swiper.width
                      });
              }
          },
          resize: function () {
              this.update();
          }
      },
    });

     // ------------ tc-awards-slider1 -----------
     var swiper = new Swiper('.tc-about-style10 .about-maquee', {
      slidesPerView: "auto",
      spaceBetween: 0,
      centeredSlides: true,
      slidesPerView: "auto",
      speed: 10000,
      autoplay: {
          delay: 1,
      },
      loop: true,
  //   allowTouchMove: false,
      disableOnInteraction: true,
  });

  // ------------ tc-testimonials-slider1 -----------
  var swiper = new Swiper('.tc-clients-style10 .clients-slider1', {
    slidesPerView: 1,
    spaceBetween: 0,
    centeredSlides: true,
    speed: 1000,
    pagination: {
        el: '.tc-clients-style10 .swiper-pagination',
        clickable: true ,
      },
      navigation: {
        nextEl: '.tc-clients-style10 .swiper-button-next',
        prevEl: '.tc-clients-style10 .swiper-button-prev',
      },
    mousewheel: false,
    keyboard: true,
    autoplay: {
        delay: 6000,
    },
    loop: true,
});

});



// ============================================
// PROJECT SHOWCASES VIDEO PLAYBACK
// ============================================

$(document).ready(function() {
    // Create video modal if it doesn't exist
    if ($('.video-modal').length === 0) {
        $('body').append(`
            <div class="video-modal" id="videoModal">
                <div class="video-modal-content">
                    <span class="video-modal-close">&times;</span>
                    <iframe class="video-iframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        `);
    }

    // Handle play button click
    $('.play-button-overlay').on('click', function() {
        const videoId = $(this).data('video-id');
        const platform = $(this).data('platform') || 'youtube';
        
        let videoUrl = '';
        
        if (platform === 'youtube') {
            videoUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
        } else if (platform === 'vimeo') {
            videoUrl = `https://player.vimeo.com/video/${videoId}?autoplay=1`;
        }
        
        // Set iframe source and show modal
        $('#videoModal .video-iframe').attr('src', videoUrl);
        $('#videoModal').addClass('active');
        $('body').css('overflow', 'hidden'); // Prevent body scroll
    });

    // Close modal
    $('.video-modal-close, .video-modal').on('click', function(e) {
        if (e.target === this || $(e.target).hasClass('video-modal-close')) {
            $('#videoModal').removeClass('active');
            $('#videoModal .video-iframe').attr('src', ''); // Stop video
            $('body').css('overflow', ''); // Restore body scroll
        }
    });

    // Close on Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#videoModal').hasClass('active')) {
            $('#videoModal').removeClass('active');
            $('#videoModal .video-iframe').attr('src', '');
            $('body').css('overflow', '');
        }
    });
});



