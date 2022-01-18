<?php
class Preguntas_frecuentes_model extends MY_Model {
  
  function __construct(){
    parent::__construct();
    $this->table = "bv_preguntas_frecuentes";
    $this->indexable = array('pregunta');
    $this->fk = "id";
    $this->pk = "id";
    $this->defaultSort = 'c.orden';
  }

  function onGetAll(){
  	$this->db->select($this->table.'.*, c.nombre as categoria',false);
  	$this->db->join('bv_preguntas_categorias c','c.id = '.$this->table.'.categoria_id','left');
  }
  function onGet(){
  	$this->db->select($this->table.'.*, c.nombre as categoria',false);
  	$this->db->join('bv_preguntas_categorias c','c.id = '.$this->table.'.categoria_id','left');
  }

  function getPreguntas(){
    $this->db->select($this->table.'.*, c.nombre as categoria',false);
    $this->db->join('bv_preguntas_categorias c','c.id = '.$this->table.'.categoria_id');    
    $this->db->order_by("c.orden asc, ".$this->table.".orden asc");
    return $this->db->get($this->table)->result();
  }

}