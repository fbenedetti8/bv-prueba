/* STICKY TICKET DESKTOP */



function stickyTicket(e) {

  var ticket = document.getElementById("ticket");
  var detalle = document.getElementById("detalle");
  var detalleOffset = $(detalle).offset().top;

  if( $('.module__destacados').length ){
    var otrosDestinos = document.getElementsByClassName("module__destacados")[0];
  }else{
    var otrosDestinos = document.getElementsByTagName("footer")[0];
  }

  var otrosDestinosOffset = otrosDestinos.offsetTop;
  var ticketDetailHeight = document.getElementById("ticket-detail").offsetHeight;
  var paqueteConsultasHeight = document.getElementById("paquete-consultas").offsetHeight;
  
  
  var ticketHeight = ticketDetailHeight + paqueteConsultasHeight;
  var limiteBottom = otrosDestinosOffset - ticketHeight;

  var breakpoint = limiteBottom - 70;

  requestAnimationFrame(function(){

    console.log( $('#detalle').offset().top );

    if ( (window.pageYOffset + 75) > detalleOffset){

        ticket.classList.add('ticket--sticky-dp-top');
        ticket.classList.remove('ticket--sticky-dp-bot');
        ticket.classList.remove('ticket--top');

        if(window.pageYOffset > breakpoint) {
          ticket.classList.remove('ticket--sticky-dp-top');
          ticket.classList.add('ticket--sticky-dp-bot');
        }
    }
    else{
        ticket.classList.remove('ticket--sticky-dp-top');
        ticket.classList.remove('ticket--sticky-dp-bot');
        ticket.classList.add('ticket--top');
    }
  })
}

window.addEventListener("scroll", function(e){
  if (window.innerWidth >= 992) {
    stickyTicket(e);
  } else {
    $('.ticket').show();
  }
});
