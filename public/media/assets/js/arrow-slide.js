// Categorías Arrow Click

$( document ).on('click', ".tours__categoria__header", (function() {
  $( this ).next('.categoria__content').slideToggle();
  transform($( this ));

}));

function transform(element) {
  if ($( element ).children('.tours__categoria__header__arrow').children().hasClass('tours__categoria__header__arrow--v-transform')) {
    $( element ).children('.tours__categoria__header__arrow').children().removeClass('tours__categoria__header__arrow--v-transform');
    } else {
      $( element ).children('.tours__categoria__header__arrow').children().addClass('tours__categoria__header__arrow--v-transform');
    }
}