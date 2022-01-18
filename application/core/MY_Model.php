<?php
class MY_Model extends CI_Model {
	var $table;					//Nombre de la tabla primaria
	var $indexable = array();	//Array de campos sobre los que se indexa para las busquedas
	var $langTable;				//Nombre de la tabla de idioma, en caso de que corresponda
	var $langFields = array();	//Array de campos que son multiidioma
	var $langDefault = 'es';	//Idioma por defecto de los campos multiidioma de la tabla
	var $fk;					//Nombre de la foreign key en tablas que referencian a la tabla primaria
	var $filters = '';			//Array de filtros a usar para obtener los listados
	var $pk;
	var $allowed_fields = '';
	var $defaultSort = 'id';
	
	function __construct(){
		parent::__construct();
	}

	function getAll($num=100000, $offset=0, $sort='', $type='DESC', $keywords='') {
		if ($sort == '') $sort=$this->pk;
		
		$this->onGetAll();
		
		if ($keywords != '') {
			$q = "(1=0";
			foreach ($this->indexable as $index)
				$q .= " OR " .(strpos($index, ".")?$index:($this->table.".".$index)) . " LIKE '%" . $keywords . "%'";
			//$this->db->or_like($index, $keywords);
			$q .= ")";
			$this->db->where($q);
		}
		
		if ($this->filters != '')
			$this->db->where($this->filters);
				
		if ($sort != '')
			$query = $this->db->order_by($sort, $type)->get($this->table, $num, $offset);	
		else 
			$query = $this->db->get($this->table, $num, $offset);	
		
		return $query;
	}

	/*
	function get($id) {
		$query = $this->db->get_where($this->table, array('id' => $id));
		return $query;
	}
	*/
	function get($id) {
		$this->db->select($this->table . '.* ');
		$this->onGet();
		$query = $this->db->get_where($this->table, array($this->table . '.' . $this->pk => $id));
		return $query;
	}	

	/*
	function update($id, $data) {
		foreach ($data as $key=>$value) {
			if ($this->db->field_exists($key, $this->table))
				$finalData[$key] = $value;
		}
				
		$this->db->where('id', $id);
		$this->db->update($this->table, $finalData); 
	}
	*/
	
	function onGetAll() {
	}
	function onGetAll_export() {
	}
	function onGet() {
	}
	
	function update($id, $data) {
		$this->db->query("SET time_zone = '-03:00'"); //Set timezone de Mexico

		//Actualizar los datos del artista
		$finalData = array();
		foreach ($data as $key=>$value) {
			if ($this->db->field_exists($key, $this->table))
				$finalData[$key] = $value;
		}
		//Insertar los campos localizados pero para el idioma por default
		/*
		foreach ($this->langFields as $langField) {
			$finalData[$langField] = $data[$langField . '_' . $this->langDefault];
		}
		*/
		$this->db->where($this->pk, $id);
		if(count($finalData)){
			$this->db->update($this->table, $finalData);
		}
		
		//Actualizar datos de idioma
		if (count($this->langFields)) {
			foreach ($this->config->item('languages') as $lang=>$language) {
				$langsave = FALSE;
				$langData['idioma'] = $lang;
				foreach ($this->langFields as $langField) {
					if (isset($data[$langField.'_'.$lang])) {
						$langData[$langField] = $data[$langField . '_' . $lang];
						$langsave = TRUE;
					}
				}
				
				if ($langsave) {
					$this->db->where('id', $id);
					$this->db->where('idioma', $lang);
					$this->db->update($this->langTable, $langData);
				}
			}
		}		
	}	

	/*
	function insert($data) {
		foreach ($data as $key=>$value) {
			if ($this->db->field_exists($key, $this->table))
				$finalData[$key] = $value;
		}
				
		$this->db->insert($this->table, $finalData); 
		return $this->db->insert_id();
	}
	*/
	function insert($data) {	
		//$this->db->query("SET time_zone = '-06:00'"); //Set timezone de Mexico
		foreach ($data as $key=>$value) {
			if ($this->db->field_exists($key, $this->table))
				$finalData[$key] = $value;
		}
		
		//Insertar los campos localizados pero para el idioma por default
		/*
		foreach ($this->langFields as $langField) {
			$finalData[$langField] = $data[$langField . '_' . $this->langDefault];
		}
		*/
		$this->db->insert($this->table, $finalData); 
		$id = !empty($data['id']) ? $data['id'] : $this->db->insert_id();

		$data[$this->pk] = $id;
		
		//Insertar datos de idioma
		if (count($this->langFields)) {
			foreach ($this->config->item('languages') as $lang=>$language) {
				$langData['id'] = $id;
				$langData['idioma'] = $lang;
				foreach ($this->langFields as $langField)
					$langData[$langField] = $data[$langField . '_' . $lang];
				$this->db->insert($this->langTable, $langData);
			}			
		}
		
		return $id;
	}
	

	function delete($id) {
		if ($this->langTable) {
			$this->db->delete($this->langTable, array($this->pk => $id));
		}
		$this->db->delete($this->table, array($this->pk => $id)); 
	}
	
	function deleteWhere($where) {
		$this->db->where($where); 
		$this->db->delete($this->table); 
	}
	
	function updateWhere($where,$data) {
		$this->db->where($where); 
		$this->db->update($this->table,$data); 
	}

	function count($keywords = '') {
		$this->onGetAll();
		
		if ($keywords != '') {
			$q = "(1=0";
			foreach ($this->indexable as $index)
				$q .= " OR " . $index . " LIKE '%" . $keywords . "%'";
			//$this->db->or_like($index, $keywords);
			$q .= ")";
			$this->db->where($q);
		}

		if ($this->filters != '')
			$this->db->where($this->filters);

		return $this->db->count_all_results($this->table);
	}

	function countWhere($arr_fields){
		$this->db->where($arr_fields);
		return $this->db->count_all_results($this->table);
	}
	
	function getWhere($arr_fields){
		$this->db->where($arr_fields);
		return $this->db->get($this->table);
	}
	
	function getByName($name){
		$q = "select * from ".$this->table." where nombre like '%" . $name . "%'  COLLATE 'utf8_general_ci'";
		$data = $this->db->query($q);
		return $data;
	}	
    
    //funcion que modifica la url concatenandole el id al final
    function modificarURL($id){
        $this->db->select('url');
        $row = $this->db->get_where($this->table,array('id'=>$id))->row();
        
        $info = pathinfo($row->url);
        if(isset($info['extension']) && $info['extension']){
            $extension = '.' .$info['extension'];
        }else{
            $extension = '';
        }
        $row->url =  basename($row->url,$extension);
        
        //si la url ya tiene el id no hace el update
        if(strstr($row->url, '-'.$id)){
            
        }else{
            $this->db->where('id', $id);
            $this->db->update($this->table, array('url' => $row->url . '-' . $id . $extension));     
        }
        
    }
	
	function getList($filters='', $orderby='', $pager='') {
		parse_str($filters, $parameters);
		parse_str($pager, $pagerinfo);
		if ($orderby) {
			$this->db->order_by($orderby);
		}
		if ($pagerinfo) {
			if ($pagerinfo['limit']) $this->db->limit($pagerinfo['limit']);
			if ($pagerinfo['offset']) $this->db->offset($pagerinfo['offset']);
		}
		return $this->db->get_where($this->table, $parameters)->result();
	}
	
	function getBy($filters) {
		parse_str($filters, $parameters);
		return $this->db->get_where($this->table, $parameters)->row();
	}

	function countBy($filters) {
		parse_str($filters, $parameters);
		$this->db->where($parameters);
		return $this->db->count_all_results($this->table);
	}	
	
	function getAll_export($num=100000, $offset=0, $sort='', $type='DESC', $keywords='') {
		if ($sort == '') $sort=$this->table.'.'.$this->pk;
		
		$this->onGetAll_export();
		
		if ($keywords != '') {
			$q = "(1=0";
			foreach ($this->indexable as $index)
				$q .= " OR " .(strpos($index, ".")?$index:($this->table.".".$index)) . " LIKE '%" . $keywords . "%'";
			//$this->db->or_like($index, $keywords);
			$q .= ")";
			$this->db->where($q);
		}
		
		if ($this->filters != '')
			$this->db->where($this->filters);
				
		if ($sort != '')
			$query = $this->db->order_by($sort, $type)->get($this->table, $num, $offset);	
		else 
			$query = $this->db->get($this->table, $num, $offset);	
		
		return $query;
	}

}