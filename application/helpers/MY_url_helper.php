<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
function site_url($route='') {
	$CI =& get_instance();
	$route = empty($route) ? '' : $route.$CI->config->item('url_suffix');
	$uricode = empty($CI->uricode) ? '' : $CI->uricode;
	
	if (substr($route, 0, 1) != '/') $route = '/'.$route;

	$url = rtrim(base_url().$uricode.$route, '/');
	$url = str_replace('/.html', '.html', $url);

	return $url;
}
*/
function language_url($idioma) {
	$CI =& get_instance();
	$uricode = empty($CI->uricode) ? '' : $CI->uricode;
	$region = $CI->config->item('region');

	$url = current_url();
	$url = str_replace(base_url(), base_url().$uricode.'/', $url);
	$url = str_replace($uricode, $region.'-'.$idioma, $url);
	$url = str_replace('/index.php', '', $url);
	
	return $url;
}