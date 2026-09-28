(function(){
  $('.submit-prevent-form').on('submit', function(e){
    if ($(this).data('submitting') === true) {
      e.preventDefault();
      return false;
    }
    $(this).data('submitting', true);
    $('.submit-prevent-button').attr('disabled', 'true');

    $('.spinner').show();
  })
})();
//(function(){
  //$('.submit-prevent-buton').on('click', function(){
    //$('.submit-prevent-button').attr('disabled', 'true');

    //$('.spinner').show();
  //})
//})();
