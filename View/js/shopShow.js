$( document ).ready(function(){
  $('.scrollspy').scrollSpy();
  $('#menuCategories').pushpin({
      top: $('#menuCategories').offset().top,
      offset:70
  });
  $('#filterHeader').pushpin({
      top: $('#filterHeader').offset().top,
  });
  $('#sidebarShop').pushpin({
      top: $('#sidebarShop').offset().top,
      offset:70
  });
  $('.carousel').carousel({fullWidth: true,indicators:true});
});
