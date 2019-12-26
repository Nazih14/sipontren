
var wow = new WOW(
{
    boxClass:     'wow',      // animated element css class (default is wow)
    animateClass: 'animated', // animation css class (default is animated)
    offset:       0,          // distance to the element when triggering the animation (default is 0)
    mobile:       true,       // trigger animations on mobile devices (default is true)
    live:         true,       // act on asynchronously loaded content (default is true)
    callback:     function(box) {
      // the callback is fired every time an animation is started
      // the argument that is passed in is the DOM node being animated
    },
    scrollContainer: null,    // optional scroll container selector, otherwise use window,
    resetAnimation: true,     // reset animation on end (default is true)
  }
  );
wow.init();



$(window).scroll(function() {
  if ($(".navbar").offset().top > 50) {
    $('#custom-nav').addClass('affix');
    $(".navbar-fixed-top").addClass("top-nav-collapse");
  } else {
    $('#custom-nav').removeClass('affix');
    $(".navbar-fixed-top").removeClass("top-nav-collapse");
  }   
});

/*------ Sidebar menu collapse ------*/

$(document).ready(function () {
  $('.leftmenutrigger').on('click', function (e) {
    $('.side-nav').toggleClass("open");
    $('#wrapper').toggleClass("open");
    e.preventDefault();
  });
});


// (function () {
//   "use strict";

//   var treeviewMenu = $('.leftmenutrigger');

//   // Toggle Sidebar
//   $('[data-toggle="sidebar"]').click(function(event) {
//     event.preventDefault();
//     $('.side-nav').toggleClass('sidenav-toggled');
//   });

//   // Activate sidebar treeview toggle
//   $("[data-toggle='treeview']").click(function(event) {
//     event.preventDefault();
//     if(!$(this).parent().hasClass('is-expanded')) {
//       treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
//     }
//     $(this).parent().toggleClass('is-expanded');
//   });

//   // Set initial active toggle
//   $("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

//   //Activate bootstrip tooltips
//   $("[data-toggle='tooltip']").tooltip();

// })();


/*------ Input Number Qty Plugin JS ------*/
// $("input[type='number']").inputSpinner()

/*------ Input Toogle swicth Plugin JS ------*/
// $(function() {
//     $('#toggle-two').bootstrapToggle({
//       on: 'Enabled',
//       off: 'Disabled'
//     });
//   })


//*------------------lock modal --------------------*/
$('#myModal').modal({backdrop: 'static', keyboard: false}) 


  
