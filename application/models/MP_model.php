<?php
class MP_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_mp_metodos_pago";
		$this->indexable = array();
		$this->fk = "id";
		$this->pk = "id";
	}
	
	function getMetodosPagoOficina(){		
		return $this->db->query("select * FROM bv_mp_metodos_pago where origen = 'backend' 
			order by tarjeta, banco, cuotas asc")->result();
	}

	function getMetodosPago($origen){		
		$q = "select * FROM ".$this->table." where origen = '".$origen."' group by banco order by orden asc, banco asc";
		$bancos = $this->db->query($q)->result();
		
		foreach($bancos as $b){
			$q = "select * FROM ".$this->table." where banco = '".$b->banco."' and origen = '".$origen."' group by tarjeta order by tarjeta asc";
			$b->tarjetas = $this->db->query($q)->result();
			
			foreach($b->tarjetas as $t){
				if($origen == 'backend'){
					$q = "select * FROM ".$this->table." where banco = '".$b->banco."' and tarjeta = '".$t->tarjeta."' and origen = '".$origen."' group by tarjeta, cuotas order by cuotas asc";
				}
				else{
					$q = "select * FROM ".$this->table." where banco = '".$b->banco."' and tarjeta = '".$t->tarjeta."' and origen = '".$origen."' group by tarjeta order by cuotas asc";
				}
				$t->cuotas = $this->db->query($q)->result();				
			}
		}
		
		return $bancos;
	}
	
}