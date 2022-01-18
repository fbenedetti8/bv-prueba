<?php
class Enviomailing_model extends MY_Model {
	
	function __construct(){
		parent::__construct();
		$this->table = "bv_mailings_envios";
		$this->indexable = array('P.codigo','P.nombre');
		$this->fk = "id";
		$this->pk = "id";
	}
		
	function getAll($num=100000, $offset=0, $sort='', $type='', $keywords='',$restantes=''){
		$this->db->select("ME.id, ME.paquete_id, ME.mail_id, EST.estado_id, M.asunto, 
							P.nombre, P.fecha_inicio as fechaSalida,  
							ifnull(t.envios,0) as envios_hechos, 
							ifnull(t1.inscriptos,0) as inscriptos_paquete, 
							D.nombre as destino, GROUP_CONCAT(ESTA.nombre) as estado",false);

		//fixed 15-01-2015
		$this->db->join("bv_paquetes P","P.id = ME.paquete_id and (P.activo = 1 or (P.activo = 0 and P.fecha_fin > date(now())))");
		$this->db->join("bv_destinos D","D.id = P.destino_id");
		$this->db->join("bv_mailings M","M.id = ME.mail_id");
		$this->db->join("bv_mailings_envios_estados EST","EST.mailing_envio_id = ME.id");
		$this->db->join("bv_reservas_estados ESTA","EST.estado_id = ESTA.id");
		$this->db->join("(select ME.id, ME.paquete_id, count(*) as inscriptos 
							FROM bv_mailings_envios ME 
							JOIN bv_mailings M ON M.id = ME.mail_id 
							JOIN bv_mailings_envios_estados EST ON EST.mailing_envio_id = ME.id 
							JOIN bv_reservas_estados ESTA ON EST.estado_id = ESTA.id
							JOIN bv_reservas R on R.paquete_id = ME.paquete_id and R.estado_id = EST.estado_id
							group by ME.id, ME.paquete_id) t1","t1.paquete_id = ME.paquete_id and ME.id = t1.id","left");
		$this->db->join("(select envio_id, count(*) as envios from bv_mailings_enviados group by envio_id) t","t.envio_id = ME.id","left");
		$this->db->group_by("ME.id, ME.paquete_id, ME.mail_id, M.asunto, P.nombre");
		
		if ($keywords != '') {
			$q = "(1=0";
			foreach ($this->indexable as $index)
				$q .= " OR " . $index . " LIKE '%" . $keywords . "%'";
			
			$q .= ")";
			$this->db->where($q);
		}
		
		if($restantes){
			$this->db->having('inscriptos_paquete > envios_hechos');
		}

		if ($sort != '')
			$ret = $this->db->order_by($sort, $type)->get($this->table." ME", $num, $offset);	
		else 
			$ret = $this->db->get($this->table." ME", $num, $offset);	

		return $ret;
	}
	
	function count($keywords=''){
		//$this->db->join("paquetes P","P.id = ME.paquete_id and P.activo = 1");
		//fixed 15-01-2015
		$this->db->select("ME.*",false);
		$this->db->join("bv_paquetes P","P.id = ME.paquete_id and (P.activo = 1 or (P.activo = 0 and P.fecha_fin > date(now())))");
		$this->db->join("bv_destinos D","D.id = P.destino_id");
		$this->db->join("bv_mailings M","M.id = ME.mail_id");
		$this->db->join("(select paquete_id, count(*) as inscriptos from bv_reservas group by paquete_id) t1","t1.paquete_id = ME.paquete_id","left");
		$this->db->join("(select envio_id, count(*) as envios from bv_mailings_enviados group by envio_id) t","t.envio_id = ME.id","left");
		$this->db->group_by("ME.paquete_id, ME.mail_id, M.asunto, P.nombre");
		
		return $this->db->count_all_results($this->table." ME");
	}
	
	function getEnviosDePaquete($paquete_id){
		$this->db->select("ME.id, ME.paquete_id, ME.mail_id, ME.inscripto_id, ME.fecha, ME.estado_id, M.asunto, P.nombre, U.nombre, U.apellido, U.email");
		$this->db->join("bv_paquetes P","P.id = ME.paquete_id");
		$this->db->join("bv_mailings M","M.id = ME.mail_id");
		$this->db->join("bv_usuarios U","U.id = ME.inscripto_id");
		return $this->db->get_where($this->table." ME",array("paquete_id"=>$paquete_id));
	}
	
	/*
	function getMailsInscriptosRestantes($paquete_id,$mail_id,$estado_id=4){
		$q = "SELECT distinct U.id as usuario_id, U.email
				FROM (usuarios U) 
				INNER JOIN reservas R ON R.usuario_id = U.id and R.paquete_id = ".$paquete_id." and R.estado_id = ".$estado_id."
				INNER JOIN paquetes P ON P.id = R.paquete_id
				WHERE U.id NOT IN ( select distinct inscripto_id from mailings_enviados where mail_id = ".$mail_id." and paquete_id = ".$paquete_id." )";
		return $this->db->query($q);
	}
	*/
	
	//cambió, ahora es por id de envio (ID)		
	function getMailsInscriptosRestantes($envio_id){
		$q = "SELECT distinct R.usuario_id, PA.email, 0 as enviar_mail 
				FROM bv_mailings_envios ME 
                INNER JOIN bv_paquetes P ON P.id = ME.paquete_id 
                INNER JOIN bv_mailings_envios_estados EST ON EST.mailing_envio_id = ME.id 
				INNER JOIN bv_reservas R ON R.paquete_id = ME.paquete_id and R.estado_id = EST.estado_id
				INNER join bv_reservas_pasajeros RP on RP.reserva_id = R.id and RP.responsable = 1
				INNER JOIN bv_pasajeros PA ON PA.id = RP.pasajero_id 
                INNER JOIN bv_usuarios U ON U.id = R.usuario_id
				WHERE ME.id = ".$envio_id." and 
                	PA.id NOT IN ( select distinct inscripto_id from bv_mailings_enviados where enviado = 1 and envio_id = ".$envio_id." )";
		return $this->db->query($q);
	}
	
}
