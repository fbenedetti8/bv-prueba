$(".option-emails .title").click(function() {
	$(".option-emails").toggleClass('open');
	$(".listado.mail").toggle("slow");
});

//Proximos viajes

$(".type-multiple").click(function() {
	$(this).toggleClass("collapsed");
	$(this).next().toggle();
});

$(".bt-faq-toogle").click(function() {
	$(this).toggleClass("collapsed");
	$(this).parent().parent().next().toggle();
});


//Modal Popup
$('.button-filter').click(function(e) {
	$('.module__popup').show()
})
$('.btn-back').click(function(e) {

	$('.module__popup').fadeOut()

})

//Cards Mobile Collapsed
$(".date.card-mob").click(function() {
	$(this).toggleClass("collapsed");
	$(this).next().toggle();
});

$(".card-mob-ademas").click(function() {
	$(this).toggleClass("collapsed");
	$(this).next().toggle();
});




//selectric
$('select.selectric').selectric({
	onInit: function(element){
		var title = $(element).data('title');
		$(element).parents('.selectric-wrapper').find('.selectric .selectric-label').html(title);
		$(element).parents('.selectric-wrapper').find('.selectric-items ul li:eq(0)').hide();
	}
});


$('.selectric-place_selector').click(function(ev){

	if( $(window).width() < 992 ){
		ev.preventDefault()

		show_popup('popup_lugares')
	}
});

$('.selectric-date_selector').click(function(ev){

	if( $(window).width() < 992 ){
		ev.preventDefault()

		show_popup('popup_fechas')
	}
});



//Info detalle
$(".btn-detalle").click(function() {
	$(this).toggleClass("open");
	$(".info-detalle").slideToggle();
});


$(".ticket-mobile .box-head .btn-detalle-mobile").click(function() {
	$(this).toggleClass("open");
	$(".box__ticket").slideDown()
		.css({
			bottom: -250,
			position: 'fixed'
		})
		.animate({
			bottom: 0
		}, 800, function() {
			//callback
		});
});





function show_popup(clase_popup, offset){

	if( offset == undefined ){
		offset = false;
	}

	$('.popup').fadeOut();
	$('.popup').removeClass('open');
	var nombre_popup = '.popup.' + clase_popup;

	if(offset == false){
		$(nombre_popup).fadeIn();
	}else{
		$(nombre_popup).fadeIn();
		$(nombre_popup).addClass('open');
	}

	
	$('body').css('overflow', 'hidden');
}


function close_popup(offset){

	if( offset == undefined ){
		offset = false;
	}

	if( offset == false ){
		$('.popup').fadeOut();
	}else{
		$('.popup').fadeOut();
		$('.popup').removeClass('open');
	}

	$('body').css('overflow', 'auto');
}

$('.popup .wrapper').click(function(ev){
	ev.stopPropagation()
})


$('.popup, .btn_close').click(function(ev){
	ev.stopPropagation()

	close_popup();
});


$('.popup.offet_popup, .btn_close').click(function(ev){
	ev.stopPropagation()

	close_popup(true);
});



$('.gallery .owl-carousel').owlCarousel({
	items: 1,
	dots: true
});




var nav_header = $('.nav_header').clone().removeClass('fixed').addClass('sticky');

$(nav_header).insertAfter( $('.nav_header') );


$(window).scroll(function(){

	var nav_header_top = $('.nav_header.fixed').length ? $('.nav_header.fixed').offset().top : 0;
	var nav_header_bottom = nav_header_top + $('.nav_header.fixed').height();


	if( $(window).scrollTop() > nav_header_bottom ){
		$('.nav_header.sticky').addClass('visible')
	}else{
		$('.nav_header.sticky').removeClass('visible')
		$('.dropdown_tel').fadeOut()
	}
});



$('.button_tel').click(function(ev){
	ev.stopPropagation();

	if( $(window).width() > 991 ){
		var tel_box_wrapper = $(this).parents('.tel_box_wrapper');
		var dropdown_tel = $(tel_box_wrapper).find('.dropdown_tel');

		if( $(tel_box_wrapper).hasClass('active') ){
			$(tel_box_wrapper).removeClass('active')
			$(dropdown_tel).fadeOut('fast')
		}else{
			$(tel_box_wrapper).addClass('active');
			$(dropdown_tel).fadeIn('fast')
		}
	}else{
		show_popup('popup_telefonos')
	}
});

$('.dropdown_tel').click(function(ev){
	ev.stopPropagation();
})


$('.btn_menu').click(function(){
	show_popup('popup_proximos_viajes', true);
});



$('.module__Menuhelp .button, .module__Menuhelp .btn-arrow').click(function(ev){
	ev.stopPropagation();

	if( $(window).width() > 991 ){
		var parent_module = $(this).parents('.module__Menuhelp');
		var nav_help = parent_module.find('.nav.help')

		if( nav_help.is(':hidden') ){
			nav_help.fadeIn()
			parent_module.addClass('open')
		}else{
			nav_help.fadeOut()
			parent_module.removeClass('open')
		}

	}
	else{
		show_popup('popup_ayuda');
	}
});



function add_options(array, select_element){

	var options = '';

	$.each(array, function(i, option){
		options += '<option>' + option + '</option>';
	});

	$(select_element).html(options);

	$(select_element).selectpicker('refresh');
}

$('body').on('click','.aside_content .btn_detalle, .desktop_static .btn_detalle',function(){

	if( $(this).parents('.detalle_aside').find('.info_detalle').is(':hidden') ){
		$(this).parents('.detalle_aside').find('.info_detalle').slideDown();
		$('#detalle_visible').val(1);

		$(this).html('OCULTAR DETALLE');
	}else{
		show_popup('popup_ayuda')
	}
});


$('.module__Menuhelp').click(function(ev){
	ev.stopPropagation();
});


$(document).click(function(ev){
	ev.stopPropagation();

	$('.dropdown_tel').fadeOut('fast');
	$('.tel_box_wrapper').removeClass('active');


	$('.module__Menuhelp .nav.help').fadeOut();
	$('.module__Menuhelp').removeClass('open');
});

$('.paquete-principal__info__left__top__share > a, .heading__share__item .heading__share__item__label, .heading__share__item .heading__share__item__link').click(function(ev){
	ev.preventDefault();

	console.log( $(this).siblings('.share_buttons') );
	if( $(this).siblings('.share_buttons').is(':hidden') ){
		$(this).siblings('.share_buttons').fadeIn()
	}else{
		$(this).siblings('.share_buttons').fadeOut()
	}
});
