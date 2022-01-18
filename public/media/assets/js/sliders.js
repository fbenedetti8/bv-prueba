/*Sliders Destacados*/
$('.owl-carousel-cards').owlCarousel({
	onChanged: function(ev){

		if( ev.page.index == (ev.page.count - 1) ){
			$('.controls .next_btn').addClass('disabled')
		}else{
			$('.controls .next_btn').removeClass('disabled')
		}

		if( ev.item.index == 0 ){
			$('.controls .prev_btn').addClass('disabled')
			$('.controls .next_btn').removeClass('disabled')
		}else{
			$('.controls .prev_btn').removeClass('disabled')
		}

	},
	responsive: {
		0: { 
			stagePadding: 20,
			items: 1
		},
		320: {
			stagePadding: 20,
			items: 1
		},
		330: {
			stagePadding: 30,
			items: 1
		},
		350: {
			stagePadding: 40,
			items: 1
		},
		370: {
			stagePadding: 50,
			items: 1
		},
		450: {
			stagePadding: 90,
			items: 1
		},
		600: {
			stagePadding: 25,
			items: 2
		},
		650: {
			stagePadding: 50,
			items: 2
		},
		760: {
			stagePadding: 105,
			items: 2
		},
		992: {
			stagePadding: 60,
			items: 2
		},
		1040: {
			stagePadding: 90,
			items: 2
		},
		1150: {
			stagePadding: 140,
			items: 2
		},
		1200: {
			stagePadding: 150,
			items: 2
		},
		1290: {
			stagePadding: 180,
			items: 2
		},
		1340: {
			stagePadding: 230,
			items: 2
		},
		1340: {
			stagePadding: 20,
			items: 3
		},
		1400: {
			stagePadding: 40,
			items: 3
		},
		1500: {
			stagePadding: 80,
			items: 3
		},
		1550: {
			stagePadding: 110,
			items: 3
		},
		1660: {
			stagePadding: 170,
			items: 3
		},
		1880: {
			stagePadding: 50,
			items: 4
		},
		2100: {
			stagePadding: 150,
			items: 4
		},
		2300: {
			stagePadding: 20,
			items: 5
		},
		2400: {
			stagePadding: 80,
			items: 5
		},
		2700: {
			stagePadding: 150,
			items: 5
		},
		2850: {
			stagePadding: 80,
			items: 6
		},
		3150: {
			stagePadding: 180,
			items: 6
		}
	}
})


$('.controls .prev_btn').click(function() {
    $('.owl-carousel-cards').trigger('prev.owl.carousel');
});

$('.controls .next_btn').click(function() {
    $('.owl-carousel-cards').trigger('next.owl.carousel');
})


/* Slider Viajeros */
$('.owl-carousel-viajeros').owlCarousel({
	items: 1,
	loop: true,
	dots: true
})

/* // Slider Viajeros */

/*Slider Interna: Proximos Viajes*/
$('.owl-popup').owlCarousel({
		items: 1,
		loop: true,
		margin: 30,
		nav: true,
		navText: ["<i class='fa fa-chevron-left'></i>",
			"<i class='fa fa-chevron-right'></i>"
		]
	})
	/*Slider Interna: Proximos Viajes*/

/*Slider Interna Paquete*/
/* Slider Viajeros */
$('.owl-carousel-paquetes').owlCarousel({
		loop: true,
		margin: 30,
		dots: true,
		nav: false,
		responsive: {
			0: {
				items: 1
			}
		}
	})
	/* Fin Slider Interna Paquete*/



/*Slider intermedia*/
$('[owl-otros-destinos]').owlCarousel({
  items: 2,
  loop: false,
  center: true,
  margin: 30,
  responsive: {
    0: {

      items: 1
    },
    600: {
      items: 2
    },
    1000: {
      items: 2.5
    }
  }
})

/* Galería Interna de Paquete */


$('#viajes_solas_y_solos .carousel .owl-carousel').owlCarousel({
	items: 1,
	nav: true,
	navText: ['<span class="icon-left_2"></span>', '<span class="icon-right_2"></span>'],
	autoplay: true,
	autoplayTimeout: 7000,
	autoplaySpeed: 2000,
	loop: true
})






function enableGaleriaPaquete() {
  var galeriaOwl = $('[galeria-owl]').owlCarousel({
    items: 1,
    loop: false,
    URLhashListener:true,
    autoPlay: false,
    startPosition: 'URLHash',
    dots: true,
  })

  $('[galeria-owl-bullet]').on('click', function(e) {
    e.preventDefault();
    galeriaOwl.trigger('to.owl.carousel', [$(this).index()]);

  })
}

enableGaleriaPaquete();
