<?php
class Region_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "ua_regiones";
		$this->indexable = array('nombre');
		$this->fk = "region_id";
		$this->pk = "id";
		$this->defaultSort = "orden";
		$this->defaultSortType = "asc";
	}

	function onGetAll() {
		$this->db->select('ua_regiones.id, ua_paises.iso2, ua_paises.nombre as pais, iua.nombre as idioma_ua, ita.nombre as idioma_ta, ua_regiones.orden');
		$this->db->join('ua_paises', 'ua_paises.iso2 = ua_regiones.pais');
		$this->db->join('ua_idiomas iua', 'iua.iso = ua_regiones.idioma_ua', 'left');
		$this->db->join('ua_idiomas ita', 'ita.iso = ua_regiones.idioma_ta', 'left');
	}

	function getRegiones($company='') {
		$this->db->join('ua_paises p', 'p.iso2 = r.pais');
		$this->db->order_by('r.orden');
		
		//El campo company puede venir vacio si se usa desde backend
		if ($company) {
			$field_idioma = 'r.idioma_'.$company;
			$field_web = 'r.web_'.$company;
		}
		else {
			$field_idioma = 'r.idioma_ua';
			$field_web = 'r.web_ua';
		}

		$this->db->select('r.id, p.nombre, r.pais, '.$field_idioma.' as idioma, '.$field_web);
		if ($company == 'ta') {
			$this->db->where('r.site_ta', 1);
		}
		elseif ($company == 'ua') {
			$this->db->where('r.site_ua', 1);			
		}
		$results = $this->db->get('ua_regiones r')->result();

		foreach ($results as $pais) {
			$idioma = explode("_", $pais->idioma);
			$pais->idioma = $idioma[0];
		}

		return $results;
	}

	function getPaisesAlias($region_id) {
		$this->db->select('p.iso2, p.Nombre');
		$this->db->join('ua_paises p', 'p.iso2 = rp.pais');
		return $this->db->get_where('ua_regiones_paises rp', array('rp.region_id' => $region_id))->result();
	}

	function clearAliasPais($region_id) {
		$this->db->where('region_id', $region_id);
		$this->db->delete('ua_regiones_paises');
	}

	function setAliasPais($region_id, $pais) {
		$this->db->insert('ua_regiones_paises', array(
			'region_id' => $region_id,
			'pais' => $pais
		));
	}

	function getByPais($pais) {
		$region = $this->db->get_where('ua_regiones', array('pais' => $pais))->row();
		if (!$region) {
			$alias = $this->db->get_where('ua_regiones_paises', array('pais' => $pais))->row();
			if ($alias) {
				$region = $this->db->get_where('ua_regiones', array('id' => $alias->region_id))->row();
			}
		}

		return $region;
	}
}