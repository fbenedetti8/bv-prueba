$(function() {

  var createTag = function(value) {
    return $('<a class="tags__tag"><div class="tags__tag__close fa fa-times"></div><div class="tags__tag__text">' + value + '</div></a>')
  }

  function applyFilters () {
    var $tags = $('.tags', '.filters');
    $tags.empty();
    $('.filters__option.is-active').each(function(i) {
      var $a = $('a', this);
      $tags.append(createTag($a.text()));

      /* ACA SE PUEDEN CAPTURAR LOS VALORES ELEGIDOS */
    })

    /* ACA SE PUEDE ENVIAR EL REQUEST AL SERVER */
  }

    $('[trigger-filter-modal-open]').on('click', function(e) {
      e.preventDefault();
      $('.filters').addClass('filters--modal--open')
    })
    $('[trigger-filter-modal-close]').on('click', function(e) {
      e.preventDefault();
      $('.filters').removeClass('filters--modal--open')
    })

    $('[trigger-filter-modal-apply]').on('click', function(e) {
      e.preventDefault();
      applyFilters();
      $('.filters').removeClass('filters--modal--open')
    })


    function selectOption($option) {
      $('.filters__option', $option.parents('.filters__options')).removeClass('is-active');
      $option.addClass('is-active')

      if(! window.matchMedia('(max-width: 991px)').matches) {
        applyFilters();
      }
    }
    $('.filters__option').on('click', function(e) {
      e.preventDefault();
      var $this = $(this);
      selectOption($this);
    })

    if(window.matchMedia('(max-width: 991px)').matches) {
      /*Slider intermedia*/
      var filterDatesOwl = $('[owl-filter-dates]')
        .addClass('owl-carousel')
        .owlCarousel({
          items: 1,
          loop: false,
          center: true,
          margin: 30,
        })

        $('[trigger-owl-filter-dates-left').on('click', function() {
          filterDatesOwl.trigger('prev.owl.carousel');
        })
        $('[trigger-owl-filter-dates-right]').on('click', function() {
          filterDatesOwl.trigger('next.owl.carousel');
        })

        filterDatesOwl.on('changed.owl.carousel', function(event) {
          var index = event.item.index;

          var option = $('.filters__option', event.relatedTarget.$element)[index]
          selectOption($(option));
        })
    }


});
