$(document).ready(function() {

  $('.top_menu i').click(function(){
      $('#toolbar').toggle();
      $('#wrapper').toggleClass("toggled");
      $('#block').toggleClass("toggled");
  });
    $('body').on('click','#block.toggled',function(){
        $('#toolbar').toggle();
        $('#wrapper').toggleClass("toggled");
        $('#block').toggleClass("toggled");
    })

    $('#module_cart').click(function () {
       // window.location.href = "./cart/";
    })
    $('#category_tree .top').on('click','.glyphicon-plus',function(){
        $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
        $('#category_tree #category').toggle(500);
    });
    $('#category_tree .top').on('click', '.glyphicon-minus', function () {
        $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
        $('#category_tree #category').toggle(500);
    })


});
