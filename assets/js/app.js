/*
Template:  Skycall - Communication Admin Template
Author: iqonic.design
Design and Developed by: iqonic.design
NOTE: This file contains the styling for responsive Template.
*/

/*----------------------------------------------
Index Of Script
------------------------------------------------

:: Tooltip
:: Fixed Nav
:: Magnific Popup
:: Sidebar Widget
:: Page Loader
:: Counter
:: Progress Bar
:: Page Menu
:: Close  navbar Toggle
:: chatuser
:: chatuser main
:: Chat start
:: user toggle
:: Flatpicker
:: Scrollbar
:: checkout
:: image-upload
:: video
:: Button
:: sweet alert
::dropdown toggle

------------------------------------------------
Index Of Script
----------------------------------------------*/

(function (jQuery) {
  "use strict";

  /*---------------------------------------------------------------------
        Tooltip
        -----------------------------------------------------------------------*/
  jQuery('[data-toggle="popover"]').popover();
  jQuery('[data-toggle="tooltip"]').tooltip();
  $('.grid').hide();

  /*---------------------------------------------------------------------
        Fixed Nav
        -----------------------------------------------------------------------*/

  $(window).on("scroll", function () {
    if ($(window).scrollTop() > 0) {
      $(".iq-top-navbar").addClass("fixed");
    } else {
      $(".iq-top-navbar").removeClass("fixed");
    }
  });

  $(window).on("scroll", function () {
    if ($(window).scrollTop() > 0) {
      $(".white-bg-menu").addClass("sticky-menu");
    } else {
      $(".white-bg-menu").removeClass("sticky-menu");
    }
  });
/*---------------------------------------------------
upload images

-----------------------------*/
$(document).ready(function() {
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

        reader.onload = function (e) {
            $('.profile-pic').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


$(".file-upload").on('change', function(){
    readURL(this);
});

$(".upload-button").on('click', function() {
   $(".file-upload").click();
});
});
/*---------------------------------------------------------------------
        Magnific Popup
        -----------------------------------------------------------------------*/
  if (typeof $.fn.magnificPopup !== typeof undefined) {
    jQuery(".popup-gallery").magnificPopup({
      delegate: "a.popup-img",
      type: "image",
      tLoading: "Loading image #%curr%...",
      mainClass: "mfp-img-mobile",
      gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0, 1], // Will preload 0 - before current, and 1 after the current image
      },
      image: {
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        titleSrc: function (item) {
          return item.el.attr("title") + "<small>by Marsel Van Oosten</small>";
        },
      },
    });
    jQuery(".popup-youtube, .popup-vimeo, .popup-gmaps").magnificPopup({
      disableOn: 700,
      type: "iframe",
      mainClass: "mfp-fade",
      removalDelay: 160,
      preloader: false,
      fixedContentPos: false,
    });
  }


  /*---------------------------------------------------------------------
        Sidebar Widget
        -----------------------------------------------------------------------*/

  jQuery(document).on("click", ".iq-menu > li > a", function () {
    jQuery(".iq-menu > li > a").parent().removeClass("active");
    jQuery(this).parent().addClass("active");
  });

  // Active menu
  var parents = jQuery("li.active").parents(".iq-submenu.collapse");

  parents.addClass("show");

  parents.parents("li").addClass("active");
  jQuery('li.active > a[aria-expanded="false"]').attr("aria-expanded", "true");

  /*---------------------------------------------------------------------
        Page Loader
        -----------------------------------------------------------------------*/
  jQuery("#load").fadeOut();
  jQuery("#loading").delay().fadeOut("");

  /*---------------------------------------------------------------------
        Counter
        -----------------------------------------------------------------------*/
  if (window.counterUp !== undefined) {
    const counterUp = window.counterUp["default"];
    const $counters = $(".counter");
    $counters.each(function (ignore, counter) {
      var waypoint = new Waypoint({
        element: $(this),
        handler: function () {
          counterUp(counter, {
            duration: 1000,
            delay: 10,
          });
          this.destroy();
        },
        offset: "bottom-in-view",
      });
    });
  }

  /*---------------------------------------------------------------------
        Progress Bar
        -----------------------------------------------------------------------*/
  jQuery(".iq-progress-bar > span").each(function () {
    let progressBar = jQuery(this);
    let width = jQuery(this).data("percent");
    progressBar.css({
      transition: "width 2s",
    });

    setTimeout(function () {
      progressBar.appear(function () {
        progressBar.css("width", width + "%");
      });
    }, 100);
  });

  jQuery(".progress-bar-vertical > span").each(function () {
    let progressBar = jQuery(this);
    let height = jQuery(this).data("percent");
    progressBar.css({
      transition: "height 2s",
    });
    setTimeout(function () {
      progressBar.appear(function () {
        progressBar.css("height", height + "%");
      });
    }, 100);
  });

  /*---------------------------------------------------------------------
        Page Menu
        -----------------------------------------------------------------------*/
  jQuery(document).on("click", ".wrapper-menu", function () {
    jQuery(this).toggleClass("open");
  });

  jQuery(document).on("click", ".wrapper-menu", function () {
    jQuery("body").toggleClass("sidebar-main");
  });

  /*---------------------------------------------------------------------
       Close  navbar Toggle
       -----------------------------------------------------------------------*/

  jQuery(".close-toggle").on("click", function () {
    jQuery(".h-collapse.navbar-collapse").collapse("hide");
  });


  /*---------------------------------------------------------------------
        chatuser
  -----------------------------------------------------------------------*/
  jQuery(document).on("click", ".chat-head .chat-user-profile", function () {
    jQuery(this).parent().next().toggleClass("show");
  });
  jQuery(document).on("click", ".user-profile .close-popup", function () {
    jQuery(this).parent().parent().removeClass("show");
  });

  /*---------------------------------------------------------------------
        chatuser main
        -----------------------------------------------------------------------*/
  jQuery(document).on("click", ".chat-search .chat-profile", function () {
    jQuery(this).parent().next().toggleClass("show");
  });
  jQuery(document).on("click", ".user-profile .close-popup", function () {
    jQuery(this).parent().parent().removeClass("show");
  });

  /*---------------------------------------------------------------------
        Chat start
        -----------------------------------------------------------------------*/
  jQuery(document).on("click", "#chat-start", function () {
    jQuery(".chat-data-left").removeClass('animate__slideOutLeft').addClass("show").addClass('animate__slideInLeft');
  });
  jQuery(document).on("click", ".close-btn-res", function () {
    jQuery(".chat-data-left").addClass('animate__slideOutLeft');
    setTimeout(() => {
      jQuery(".chat-data-left").removeClass("show");
    }, 800);
  });
  jQuery(document).on("click", ".iq-chat-ui li", function () {
    jQuery(".chat-data-left").addClass('animate__slideOutLeft');
    setTimeout(() => {
      jQuery(".chat-data-left").removeClass("show");
    }, 800);
  });
  jQuery(document).on("click", ".sidebar-toggle", function () {
    jQuery(".chat-data-left").removeClass('animate__slideOutLeft').addClass("show").addClass('animate__slideInLeft');
  });
  /*---------------------------------------------------------------------
        user toggle
  -----------------------------------------------------------------------*/
  jQuery(document).on("click", ".iq-user-toggle", function () {
    jQuery(this).parent().addClass("show-data");
  });

  jQuery(document).on("click", ".close-data", function () {
    jQuery(".iq-user-toggle").parent().removeClass("show-data");
  });
  jQuery(document).on("click", function (event) {
    var $trigger = jQuery(".iq-user-toggle");
    if ($trigger !== event.target && !$trigger.has(event.target).length) {
      jQuery(".iq-user-toggle").parent().removeClass("show-data");
    }
  });
  /*-------hide profile when scrolling--------*/
  jQuery(window).scroll(function () {
    let scroll = jQuery(window).scrollTop();
    if (
      scroll >= 10 &&
      jQuery(".iq-user-toggle").parent().hasClass("show-data")
    ) {
      jQuery(".iq-user-toggle").parent().removeClass("show-data");
    }
  });
  let Scrollbar = window.Scrollbar;
  if (jQuery(".data-scrollbar").length) {
    Scrollbar.init(document.querySelector(".data-scrollbar"), {
      continuousScrolling: false,
    });
  }

  /*------------------------------------------------------------------
        Select 2 Selectpicker
        * -----------------------------------------------------------------*/

  if ($.fn.select2 !== undefined) {
    $("#single").select2({
      placeholder: "Select a Option",
      allowClear: true,
    });
    $("#multiple").select2({
      placeholder: "Select a Multiple Option",
      allowClear: true,
    });
    $("#multiple2").select2({
      placeholder: "Select a Multiple Option",
      allowClear: true,
    });
  }

  /*------------------------------------------------------------------
        Flatpicker
        * -----------------------------------------------------------------*/
  if (jQuery.fn.flatpickr !== undefined) {
    if (jQuery(".basicFlatpickr").length > 0) {
      jQuery(".basicFlatpickr").flatpickr();
    }

    if (jQuery("#inputTime").length > 0) {
      jQuery("#inputTime").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
      });
    }
    if (jQuery("#inputDatetime").length > 0) {
      jQuery("#inputDatetime").flatpickr({
        enableTime: true,
      });
    }
    if (jQuery("#inputWeek").length > 0) {
      jQuery("#inputWeek").flatpickr({
        weekNumbers: true,
      });
    }
    if (jQuery("#inline-date").length > 0) {
      jQuery("#inline-date").flatpickr({
        inline: true,
      });
    }
    if (jQuery("#inline-date1").length > 0) {
      jQuery("#inline-date1").flatpickr({
        inline: true,
      });
    }
  }

  /*---------------------------------------------------------------------
        Scrollbar
        -----------------------------------------------------------------------*/

  jQuery(window)
    .on("resize", function () {
      if (jQuery(this).width() <= 1299) {
        jQuery("#salon-scrollbar").addClass("data-scrollbar");
      } else {
        jQuery("#salon-scrollbar").removeClass("data-scrollbar");
      }
    })
    .trigger("resize");

  jQuery(".data-scrollbar").each(function () {
    var attr = $(this).attr("data-scroll");
    if (typeof attr !== typeof undefined && attr !== false) {
      let Scrollbar = window.Scrollbar;
      var a = jQuery(this).data("scroll");
      Scrollbar.init(document.querySelector('div[data-scroll= "' + a + '"]'));
    }
  });
/*-------------------------------------------------------
        Sweet alt Delete
        -----------------------------------------------------------------------*/
  $('[data-extra-toggle="delete"]').on("click", function (e) {
    const closestElem = $(this).attr("data-closest-elem");
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-outline-primary ml-2",
      },
      buttonsStyling: false,
    });

    swalWithBootstrapButtons
      .fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        showClass: {
          popup: "animate__animated animate__zoomIn",
        },
        hideClass: {
          popup: "animate__animated animate__zoomOut",
        },
      })
      .then((willDelete) => {
        if (willDelete.isConfirmed) {
          swalWithBootstrapButtons
            .fire({
              title: "Deleted!",
              text: "Your note has been deleted.",
              icon: "success",
              showClass: {
                popup: "animate__animated animate__zoomIn",
              },
              hideClass: {
                popup: "animate__animated animate__zoomOut",
              },
            })
            .then(() => {
              if (closestElem === ".card") {
                $(this).closest(closestElem).parent().remove();
              } else {
                $(this).closest(closestElem).remove();
              }
            });
        } else {
          swalWithBootstrapButtons.fire({
            title: "Your note is safe!",
            showClass: {
              popup: "animate__animated animate__zoomIn",
            },
            hideClass: {
              popup: "animate__animated animate__zoomOut",
            },
          });
        }
      });
  });

  if ($.fn.slick !== undefined && $(".top-product").length > 0) {
    jQuery(".top-product").slick({
      slidesToShow: 3,
      speed: 300,
      slidesToScroll: 1,
      focusOnSelect: true,
      autoplay: true,
      arrows: false,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            arrows: false,
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 480,
          settings: {
            arrows: false,
            autoplay: true,
            slidesToShow: 1,
          },
        },
      ],
    });
  }


  /*----------------------------------------------------------
  dropdown toggle
  ----------------------------------------------------------*/
  $('.dropdown-menu').on('click', function(event){
    if ($(this).find('[data-toggle]').length > 0) {
        event.stopPropagation();
        $($(event.target).attr('data-target')).modal('show')
    }
});
/*------------------------------------------------------
sweet alert
-------------------------------------------*/

const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
      confirmButton: 'btn btn-primary btn-lg',
      cancelButton: 'btn btn-outline-primary btn-lg ml-2'
  },
  buttonsStyling: false,
  showClass: {
      popup: 'animate__animated animate__zoomIn'
  },
  hideClass: {
      popup: 'animate__animated animate__zoomOut'
  }
})

$(document).on('click', '[data-toggle="alert"]', function() {
  swalWithBootstrapButtons.fire({
      title: $(this).attr('data-message')
  })
});
 /*---------------------------------------------------------------------
        List and Grid
        -----------------------------------------------------------------------*/
        $('#notification-toggle').on('change', function(e){
        if($(this).prop('checked')){
            $('.list').hide();
            $('.grid').show();
        } else {
          $('.list').show();
          $('.grid').hide();
        }
        })

        /*---------------------------------
        tab
        ------------------------------------*/
        $(document).on('click', '[data-toggle-extra="tab"]', function () {
          const target = $(this).attr('data-target-extra')
          $('.chat-footer').removeClass('d-none')
          $('.chat-name').text($(this).find('.chat-right').text())
          $('.chat-img').attr('src', $(this).find('.chat-profile img').attr('src'))
          $('#current-chat').find('.chat-profile img').attr('src', $(this).find('.chat-profile img').attr('src'))
          $('[data-toggle-extra="tab-content"]').removeClass('active')
          $(target).addClass('active')
          $(this).parent().find('.active').removeClass('active')
          $(this).addClass('active')
        })

        // Notification status Update 
        $(document).on("click", '[data-toggle="status"]', function () {
          $(this)
            .closest(".card")
            .find('[data-toggle="status"]')
            .removeClass("active");
          $(this).addClass("active");
          let alert = false
          let message;
          switch ($(this).attr("data-status")) {
            case "0":
              message = 'Disabled successfully';
              alert = true
              break;
            case "1":
              message = "Enabled successfully";
              alert = true;
              break;
          }
          if (alert) {
            Swal.fire({
              icon: "success",
              title: message,
              showConfirmButton: false,
            });
          }
        });

        $('.default-chat-btn').on('click', function () {
          $('.chat-data-left').removeClass('animate__slideOutLeft').addClass('animate__slideInLeft').addClass('show')
        })

        $('.modal').on('show.bs.modal', function() {
          $('.dropdown').removeClass('show')
          $('.dropdown-menu').removeClass('show')
        })

        const messages = [
          'Hi How are you?',
          'I\'m Fine what you want say any anything.',
          'What is your age?',
          'Good morning',
          'Check in iqonic design for more design!',
          'We are happy to help you.'
        ]

        $(document).on('submit', '#chat-message', function () {
          $('#current-chat').html()
          let _this = $(this);
          let currentChat = $(document).find('#other-chat').clone();
          currentChat.find('.d-none').removeClass('d-none')
          currentChat.find('.chat-msg').html(_this.serializeArray()[0].value);
          $('#chat-message')[0].reset()
          $('.chat-content.active').find('.chat-tab-box').append(currentChat.html())
          $('.chat-content').animate({ scrollTop: $(document).height() + 100 }, 1000);

          let otherChat = $(document).find('#current-chat').clone();
          otherChat.find('.d-none').removeClass('d-none')
          $('.chat-content.active').find('.chat-tab-box').append(otherChat.html())
          setTimeout(() => {
            $('.chat-content.active').find('.chat-tab-box').find('.current-user').last().remove()
            otherChat.find('.chating').removeClass('p-0')
            const randomElement = messages[Math.floor(Math.random() * messages.length)];
            otherChat.find('.chat-msg').html(randomElement);
            $('.chat-content.active').find('.chat-tab-box').append(otherChat.html())
            $('.chat-content').animate({ scrollTop: $(document).height() + 100 }, 1000);
          }, 2000);
        })


})(jQuery);
