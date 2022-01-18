<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['admin'] = 'admin/panel';

$route['default_controller'] = 'home';

//require_once( BASEPATH .'database/DB.php' );
//$db =& DB();

//rutas nuevas para los proximos viajes, buscador y filtros
$route['proximos-viajes'] = 'proximosviajes/buscar';
$route['proximos-viajes/(:any)'] = 'proximosviajes/buscar/$1';
$route['proximos-viajes/(:any)/(:any)'] = 'proximosviajes/buscar/$1/$2';
$route['proximos-viajes/(:any)/(:any)/(:any)'] = 'proximosviajes/buscar/$1/$2/$3';

//url viajes grupales
//15-07-19 la ruteamos a proximos-viajes 
$route['viajes-grupales'] = 'proximosviajes/buscar';

include_once APPPATH.'config/routes_dynamic.php';

$route['404_override'] = 'not_found';
$route['translate_uri_dashes'] = FALSE;

//viajes-solos-y-solas
$route['viajes-solas-y-solos'] = "estaticas/viajes_solas_y_solos";
//quienes-somos
$route['^quienes-somos'] = "estaticas/quienes_somos";
//contacto
$route['^contacto'] = "estaticas/contacto";
//como-reservar
$route['^como-reservar'] = "estaticas/como_reservar";
//preguntas-frecuentes
$route['^preguntas-frecuentes'] = "estaticas/faqs";
//politicas-de-privacidad
$route['^politicas-de-privacidad'] = "estaticas/privacidad";

$route['^reserva2095'] = "cron/verreserva";

/*
//link terminos y condiciones
$route['^terminos_y_condiciones'] = "home/terminos_y_condiciones";
$route['^mailing_pre_viaje'] = "home/mailing_pre_viaje";

//viajes-grupales
$route['^viajes-grupales'] = "conoce_mas";
//viajes-solos-y-solas
$route['^viajes-solos-y-solas'] = "conoce_mas/solos_y_solas";
//viajes-solos-y-solas
$route['^quienes-somos'] = "conoce_mas/quienes_somos";
//como-reservar
$route['^como-reservar'] = "conoce_mas/como_reservar";
//contacto
$route['^contacto'] = "conoce_mas/contacto";
*/