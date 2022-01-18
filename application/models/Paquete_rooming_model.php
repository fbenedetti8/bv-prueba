<?php
class Paquete_rooming_model extends MY_Model {
  
 	 function __construct(){
	    parent::__construct();
	    $this->table = "bv_paquetes_rooming";
	    $this->indexable = array();
	    $this->fk = "id";
	    $this->pk = "id";
	    $this->defaultSort = '';
  	}

 	 function clearPaquete($id){
		$this->db->where('paquete_id = '.$id);
		$this->db->delete($this->table);
		return true;
 	 }

 	 function getRooming($id){
 	 	$this->db->select('distinct a.nombre as alojamiento, 
							CONCAT(a.nombre," (",(case when afc.habitacion_id != 99 then afc.cupo_total else 0 end)," lugares)"," - Del ",DATE_FORMAT(af.fecha_checkin,"%d/%m/%Y")," al ",DATE_FORMAT(af.fecha_checkout,"%d/%m/%Y")) as alojamientocompleto, 
							h.nombre as habitacion, h.pax, afc.cantidad, afc.habitacion_id, ifnull(co.coordinadores,0) as coordinadores, r.*',false);
 	 	$this->db->join('bv_paquetes_alojamientos ad','ad.paquete_id = r.paquete_id');
 	 	$this->db->join('bv_alojamientos_fechas af','af.id = ad.fecha_alojamiento_id');
	    $this->db->join('bv_alojamientos a','a.id = af.alojamiento_id');
		$this->db->join('bv_alojamientos_fechas_cupos afc','afc.fecha_id = af.id and r.alojamiento_fecha_cupo_id = afc.id');
	    $this->db->join('bv_habitaciones h','h.id = afc.habitacion_id');
	    $this->db->join('(select c.paquete_id, GROUP_CONCAT(CONCAT(v.nombre," ",v.apellido," ",v.telefono) separator ", " ) as coordinadores
							from bv_vendedores v
							join bv_paquetes_coordinadores c on c.vendedor_id = v.id
							group by c.paquete_id) co','co.paquete_id = r.paquete_id','left');

		$this->db->where('r.paquete_id = '.$id);
		$this->db->order_by('2 asc,afc.habitacion_id,r.nro_habitacion asc');

		return $this->db->get($this->table.' r')->result();
 	}

 	function getByHabitacion($paquete_id, $habitacion_id) {
 		$this->db->join('bv_alojamientos_fechas_cupos c', 'c.id = r.alojamiento_fecha_cupo_id');
 		return $this->db->get_where('bv_paquetes_rooming r', array('r.paquete_id' => $paquete_id, 'c.habitacion_id' => $habitacion_id))->result();
 	}

 	function getDataReserva($id){
 		$this->db->select('h.*',false);
 		$this->db->join('bv_alojamientos_fechas_cupos afc','pr.alojamiento_fecha_cupo_id = afc.id');
	    $this->db->join('bv_habitaciones h','h.id = afc.habitacion_id');
 		return $this->db->get_where($this->table.' pr',array('reserva_id'=>$id))->row();
 	}
 
 	function getRoomingPorHabitacion($id,$comp=false){
 		$where = "r.habitacion_id != 99";
 		if($comp){
 			$where = "r.habitacion_id = 99";
 		}

 			$this->db->query("SET SESSION group_concat_max_len = 1000000;");
	 		return $this->db->query("SELECT r.id, r.estado_id, r.code, r.habitacion_id, sum(r.pasajeros) as pax, IFNULL(group_concat(pp.pasajero SEPARATOR ','),'') as lista_pax, h.nombre as habitacion, h.pax as hab_pax, pr.alojamiento_fecha_cupo_id as rooming_cupo_id, pr.nro_habitacion, a.nombre as alojamiento, pr.observaciones
				from bv_reservas r
				left join bv_reservas_grupos gr on gr.reserva_id = r.id
				left join bv_paquetes_rooming pr on pr.paquete_id = r.paquete_id and pr.reserva_id = r.id

				join bv_paquetes_alojamientos ad on ad.paquete_id = r.paquete_id
				join bv_alojamientos_fechas af on af.id = ad.fecha_alojamiento_id
			   	join bv_alojamientos a on a.id = af.alojamiento_id

				left join (
					select rr.id, GROUP_CONCAT( CONCAT(pax.nombre, ' ', pax.apellido) SEPARATOR ', ') as pasajero
					from bv_reservas rr
					inner join bv_reservas_pasajeros rp on rp.reserva_id = rr.id
					inner join bv_pasajeros pax on pax.id = rp.pasajero_id
					group by rr.id
				) pp ON pp.id =r.id

				inner join bv_habitaciones h on h.id = r.habitacion_id

				where r.paquete_id = ".$id." and r.estado_id = 4  and ".$where."
				group by  r.habitacion_id, nro_habitacion")->result();
 		
 	 	/*$this->db->select('distinct a.nombre as alojamiento, 
							CONCAT(a.nombre," (",(case when afc.habitacion_id != 99 then afc.cupo_total else 0 end)," lugares)"," - Del ",DATE_FORMAT(af.fecha_checkin,"%d/%m/%Y")," al ",DATE_FORMAT(af.fecha_checkout,"%d/%m/%Y")) as alojamientocompleto, 
							h.nombre as habitacion, h.pax, afc.cantidad, afc.habitacion_id, r.*,
							group_concat(concat(pa.nombre," ",pa.apellido) separator ", ") as lista_pax',false);
 	 	$this->db->join('bv_paquetes_alojamientos ad','ad.paquete_id = r.paquete_id');
 	 	$this->db->join('bv_alojamientos_fechas af','af.id = ad.fecha_alojamiento_id');
	    $this->db->join('bv_alojamientos a','a.id = af.alojamiento_id');
		$this->db->join('bv_alojamientos_fechas_cupos afc','afc.fecha_id = af.id and r.alojamiento_fecha_cupo_id = afc.id');
	    $this->db->join('bv_habitaciones h','h.id = afc.habitacion_id');
	    $this->db->join('bv_reservas re','re.id = r.reserva_id');
	    $this->db->join('bv_reservas_pasajeros rp','rp.reserva_id = re.id');
	    $this->db->join('bv_pasajeros pa','pa.id = rp.pasajero_id');

		$this->db->where('r.paquete_id = '.$id);
		$this->db->order_by('2 asc,afc.habitacion_id,r.nro_habitacion asc');
		$this->db->group_by('r.nro_habitacion');

		return $this->db->get($this->table.' r')->result();*/
 	}
	

 	function getHabsUsadas($filters=array()){
 		if(isset($filters['paquete_id']) && $filters['paquete_id']){
 			$this->db->where('pr.paquete_id',$filters['paquete_id']);
 		}
		if(isset($filters['alojamiento_fecha_cupo_id']) && $filters['alojamiento_fecha_cupo_id']){
 			$this->db->where('pr.alojamiento_fecha_cupo_id',$filters['alojamiento_fecha_cupo_id']);
 		}

 		$this->db->select('h.*',false);
 		$this->db->join('bv_alojamientos_fechas_cupos afc','pr.alojamiento_fecha_cupo_id = afc.id');
	    $this->db->join('bv_habitaciones h','h.id = afc.habitacion_id');
 		return $this->db->get($this->table.' pr')->row();
 	}
	
}