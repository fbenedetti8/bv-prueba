<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mercado_Pago extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->page = "mercado_pago";
		$this->load->model('MP_model', 'MP');
	}
	
	function index(){
		echo "index";
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		pre($mp);
		$p_methods = $mp->get_payment_methods();    
		pre($p_methods);
	}
	
	function obtener_medios(){
		extract($_POST);
				
		if(isset($origen) && $origen){				
			$ret['medios'] = $this->MP->getMetodosPago($origen);
			echo json_encode($ret);
		}
	}
	
	function obtener_cuotas(){
		extract($_POST);
		
		if(!isset($origen)){//para mobile
			$origen = 'mercadopago';
		}
		
		if(isset($banco) && $banco && isset($tarjeta) && $tarjeta && isset($origen) && $origen){
			$data['banco'] = $banco;
			$data['tarjeta'] = $tarjeta;
			$this->MP->filters = "banco = '".$banco."' and tarjeta = '".$tarjeta."' and origen = '".$origen."'";
			$ret['cuotas'] = $this->MP->getAll(99,0,'cuotas','asc')->result();
			
			echo json_encode($ret);
		}
	}
	
	
	
	
	
	
	
	
	//09-10-2014
	function get_all_payments(){
		/*
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		
		$filters = array (
			"status"=>'approved',
			"status_detail"=>'accredited',
			"payment_type"=>'ticket'
		);

		$searchResult = $mp->search_payment($filters,0,20);
		
		echo "<pre>";
		print_r($searchResult);
		*/
		
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		$p_methods = $mp->get_payment_methods();    
		
		$p_methods = isset($p_methods['response']) ? $p_methods['response'] : array();
		
		echo "<pre>";
		//print_r($p_methods);

		foreach($p_methods as $p){
			$p_method = $mp->get_payment_methods($p['id']);
			
			$p_method = isset($p_method['response']) ? $p_method['response'] : array();
			
			print_r($p_method);
		
		}
	}
	
	/*17-12-14
	Devuelve los medios de pago segun selccion MERCADOPAGO u oficina
	*/
	
	
	/*
	Devuelve las cuotas disponibles por banco y por tarjeta.
	Para completar en caja de reserva en interna de paquete.
	*/
	
	
	/*
	Proceso que consulta a MercadoPago por los metodos de pago disponibles y actualiza tabla interna del sistema.
	*/
	
	function metodos_pago(){		
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		$p_methods = $mp->get_payment_methods();    
		
		$p_methods = isset($p_methods['response']) ? $p_methods['response'] : array();
	
		$this->data['tarjetas'] = array();
		
		//borro todos los metodos de pago ya generados en db
		//$q = "truncate table mp_metodos_pago";
		//17-12-14 borro solo los de origen mercadopago asi no elimina los dados de alta por backend
		$q = "delete from bv_mp_metodos_pago where origen = 'mercadopago'";
		$this->db->query($q);
		
		//se usa para fix de 08-09-2014
		$medio_visa = false;
		$medio_master = false;
		$medio_amex = false;
						
		foreach($p_methods as $p){
			$p_method = $mp->get_payment_methods($p['id']);
			
			$p_method = isset($p_method['response']) ? $p_method['response'] : array();
			
			//solo visa american y mastercard SIN BANCO
			if( (!$medio_visa && isset($p_method['card_issuer']) && isset($p_method['card_issuer']['name']) && $p_method['card_issuer']['name'] == 'Visa Argentina S.A.') 
				 || (!$medio_master && isset($p_method['card_issuer']) && isset($p_method['card_issuer']['name']) && $p_method['card_issuer']['name'] == 'Mastercard') 
				 || (!$medio_amex && isset($p_method['card_issuer']) && isset($p_method['card_issuer']['name']) && $p_method['card_issuer']['name'] == 'American Express') ){
					
					$banco = array();
					$banco['tarjeta'] = $p_method['name'];
					$banco['banco'] = $p_method['name'];
					
					//veo sus cuotas
					$b_cuotas = isset($p_method['payer_costs']) ? $p_method['payer_costs'] : array();
					
					if($p_method['card_issuer']['name'] == 'Visa Argentina S.A.'){ 
						$banco['orden'] = 1;
						$medio_visa = true;
					}
					if($p_method['card_issuer']['name'] == 'Mastercard'){
						$banco['orden'] = 2;
						$medio_master = true;
					}
					if($p_method['card_issuer']['name'] == 'American Express'){
						$banco['orden'] = 3;
						$medio_amex = true;
					}							
					
					//cada cuota/banco
					foreach($b_cuotas as $c){
						$banco['cuotas'] = $c['installments'];
						$banco['origen'] = 'mercadopago';
						
						$metodo = $this->MP->getWhere($banco)->row();
						
						$banco['interes'] = $c['installment_rate'];
						
						//cft y tea
						if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
							list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
							
							if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
								//si viene en esta primera posicion, la uso
								$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
								$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
							}
							else{
								//si no,me fijo en la segunda posicion
								if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
									list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
								
									if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
										//si viene en esta primera posicion, la uso
										$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
										$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
									}
								}
							}
						}
						
						//pre($banco);
						
						//insertaria siempre
						if(isset($metodo->id) && $metodo->id)
							$this->MP->update($metodo->id,$banco);
						else
							$this->MP->insert($banco);
						
					}
				}
			
			
			//obtengo los bancos emisores de tarjeta
			$pm_bancos = isset($p_method['exceptions_by_card_issuer']) ? $p_method['exceptions_by_card_issuer'] : array();
		
			if(count($pm_bancos) > 0){
				foreach($pm_bancos as $b){
					$banco = array();
					//08-09-2014
					//por defecto orden 4 ya que los primeros seran VISA MASTERCARD Y AMEX
					$banco['orden'] = 4;
					
					//fix 22-08-2014 -> si el Bco es Nativa Mastercard lo subdivido en Bco Nativa y Tarj Nativa y Mastercard
					//(segun mail Lisandro)
					if( $b['card_issuer']['name'] == 'Nativa Mastercard' ){
						//subdivido Nativa
						$banco['tarjeta'] = 'Nativa';
						$banco['banco'] = 'Nativa';
						
						$b_cuotas = isset($b['payer_costs']) ? $b['payer_costs'] : array();
					
						//cada cuota/banco
						foreach($b_cuotas as $c){
							$banco['cuotas'] = $c['installments'];
							
							$metodo = $this->MP->getWhere($banco)->row();
							
							$banco['interes'] = $c['installment_rate'];
							$banco['origen'] = 'mercadopago';							
							
							//cft y tea
							if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
								list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
								
								if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
									//si viene en esta primera posicion, la uso
									$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
									$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
								}
								else{
									//si no,me fijo en la segunda posicion
									if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
										list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
									
										if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
											//si viene en esta primera posicion, la uso
											$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
											$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
										}
									}
								}
							}
							
						
							//insertaria siempre
							if(isset($metodo->id) && $metodo->id)
								$this->MP->update($metodo->id,$banco);
							else
								$this->MP->insert($banco);
						}
						//-----------------------------------------------------------------------------
						
						//subdivido Mastercard
						$banco['tarjeta'] = 'Mastercard';
						$banco['banco'] = 'Nativa';
						
						$b_cuotas = isset($b['payer_costs']) ? $b['payer_costs'] : array();
					
						//cada cuota/banco
						foreach($b_cuotas as $c){
							$banco['cuotas'] = $c['installments'];
							
							$metodo = $this->MP->getWhere($banco)->row();
							
							$banco['interes'] = $c['installment_rate'];
							$banco['origen'] = 'mercadopago';
														
							//cft y tea
							if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
								list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
								
								if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
									//si viene en esta primera posicion, la uso
									$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
									$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
								}
								else{
									//si no,me fijo en la segunda posicion
									if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
										list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
									
										if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
											//si viene en esta primera posicion, la uso
											$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
											$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
										}
									}
								}
							}
						
							//insertaria siempre
							if(isset($metodo->id) && $metodo->id)
								$this->MP->update($metodo->id,$banco);
							else
								$this->MP->insert($banco);
						}
					}
					else{
						//Otros que no sean Nativa Mastercard
						
						/*
						//16-12-14
						//SE REEMPLAZA POR IF() arriba
						
						//08-09-2014
						//Si es VISA, MASTERCARD o AMERICAN EXPRESS -> tambien los doy de alta separados de los Bancos
						//solo 1 vez
						if( (!$medio_visa && $p_method['name'] == 'Visa') 
						 || (!$medio_master && $p_method['name'] == 'Mastercard') 
						 || (!$medio_amex && $p_method['name'] == 'American Express') ){
							$banco['tarjeta'] = $p_method['name'];
							$banco['banco'] = $p_method['name'];
							
							$b_cuotas = isset($b['payer_costs']) ? $b['payer_costs'] : array();
							
							if($p_method['name'] == 'Visa'){ 
								$banco['orden'] = 1;
								$medio_visa = true;
							}
							if($p_method['name'] == 'Mastercard'){
								$banco['orden'] = 2;
								$medio_master = true;
							}
							if($p_method['name'] == 'American Express'){
								$banco['orden'] = 3;
								$medio_amex = true;
							}							
							
							//cada cuota/banco
							foreach($b_cuotas as $c){
								$banco['cuotas'] = $c['installments'];
								
								$metodo = $this->MP->getWhere($banco)->row();
								
								$banco['interes'] = $c['installment_rate'];
								
								//insertaria siempre
								if(isset($metodo->id) && $metodo->id)
									$this->MP->update($metodo->id,$banco);
								else
									$this->MP->insert($banco);
							}
						}
						*/
						
						$banco['tarjeta'] = $p_method['name'];
						$banco['banco'] = $b['card_issuer']['name'];
						
						if($b['card_issuer']['name'] == 'Provencred')
							$banco['orden'] = 5;
						else
							$banco['orden'] = 4;
						
						$b_cuotas = isset($b['payer_costs']) ? $b['payer_costs'] : array();
					
						//cada cuota/banco
						foreach($b_cuotas as $c){
							$banco['cuotas'] = $c['installments'];
							
							$metodo = $this->MP->getWhere($banco)->row();
							
							$banco['interes'] = $c['installment_rate'];
							$banco['origen'] = 'mercadopago';							
							
							//cft y tea
							if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
								list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
								
								if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
									//si viene en esta primera posicion, la uso
									$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
									$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
								}
								else{
									//si no,me fijo en la segunda posicion
									if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
										list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
									
										if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
											//si viene en esta primera posicion, la uso
											$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
											$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
										}
									}
								}
							}
						
							//insertaria siempre
							if(isset($metodo->id) && $metodo->id)
								$this->MP->update($metodo->id,$banco);
							else
								$this->MP->insert($banco);
						}
					}
				}
			}
			else{
				//me fijo si viene otro metodo de pago como PAGO FACIL, RAPIPAGO, etc.
				//ó si el metodo es tarjeta de credito (pero el emisor no es ningun banco)
				
				//22-08-2014 la tarjeta "Nativa Mastercard" acá no la tengo en cuenta (segun mail Lisandro)
				if( $p_method['name'] != 'Nativa Mastercard'){
					$b_cuotas = isset($p_method['payer_costs']) ? $p_method['payer_costs'] : array();
					
					$banco = array();
					
					//en este caso el nombre no seria de la tarjeta en sí, sino del metodo de pago, ej: RAPIPAGO, AMERICAN EXPRESS
					
					//fix 22-08-2014 -> si el nombre de tarjeta es Cencosud, hago este fix para que aparezca junto con Cencosud->Mastercard en front
					//(segun mail Lisandro)
					if($p_method['name'] == 'Cencosud'){
						$banco['tarjeta'] = 'Cencosud';
						$banco['banco'] = 'Cencosud';
					}
					else{
						$banco['tarjeta'] = $p_method['name'];
						$banco['banco'] = 'Más formas de pago';
					}
					
					if($banco['banco'] == 'Más formas de pago'){
						$banco['orden'] = 6;
					}
					else{
						$banco['orden'] = 4;
					}
					
					//cada cuota/metodo de pago
					foreach($b_cuotas as $c){
						$banco['cuotas'] = $c['installments'];
						
						$metodo = $this->MP->getWhere($banco)->row();
						
						$banco['interes'] = $c['installment_rate'];
						$banco['origen'] = 'mercadopago';
						
						//cft y tea
						if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
							list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
							
							if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
								//si viene en esta primera posicion, la uso
								$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
								$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
							}
							else{
								//si no,me fijo en la segunda posicion
								if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
									list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
								
									if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
										//si viene en esta primera posicion, la uso
										$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
										$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
									}
								}
							}
						}
						
						//insertaria siempre
						if(isset($metodo->id) && $metodo->id)
							$this->MP->update($metodo->id,$banco);
						else
							$this->MP->insert($banco);
					}				
				}
			}
		}
		
		
		//$this->metodos_sin_banco();
		
		$ret['msg'] = "Los medios de pago han sido actualizados.";
		echo json_encode($ret);
	}
	
	
	//16-12-14
	//no se usa
	//este obtiene las cuotas para visar mastercard y american sin banco
	function metodos_sin_banco(){
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		$p_methods = $mp->get_payment_methods();    
		
		$p_methods = isset($p_methods['response']) ? $p_methods['response'] : array();
		//echo "<pre>";
		//print_r($p_methods);
		
		foreach($p_methods as $p){
			$p_method = $mp->get_payment_methods($p['id']);
			//print_r($p_method);
			
			//se usa para fix de 08-09-2014
			$medio_visa = false;
			$medio_master = false;
			$medio_amex = false;
		
			//visa master y american por separado sin banco
		
			$p_method = isset($p_method['response']) ? $p_method['response'] : array();
		
			
						
						
		}
	}
	
	function test(){
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		$p_methods = $mp->get_payment_methods();    
		
		$p_methods = isset($p_methods['response']) ? $p_methods['response'] : array();
		
		pre($p_methods);
		
		foreach($p_methods as $p){
			$p_method = $mp->get_payment_methods($p['id']);
			
			pre($p_method);
		}
			
	}

	function vermp(){		
		$this->load->library('mercadopago');
		$mp = $this->mercadopago->init();
		$p_methods = $mp->get_payment_methods();    
		
		$p_methods = isset($p_methods['response']) ? $p_methods['response'] : array();
	
		$this->data['tarjetas'] = array();
		
		//se usa para fix de 08-09-2014
		$medio_visa = false;
		$medio_master = false;
		$medio_amex = false;
						
		foreach($p_methods as $p){
			$p_method = $mp->get_payment_methods($p['id']);
			
			$p_method = isset($p_method['response']) ? $p_method['response'] : array();
			
			//solo visa american y mastercard SIN BANCO
			if( (!$medio_visa && isset($p_method['card_issuer']) && isset($p_method['card_issuer']['name']) && $p_method['card_issuer']['name'] == 'Visa Argentina S.A.') 
				 || (!$medio_master && isset($p_method['card_issuer']) && isset($p_method['card_issuer']['name']) && $p_method['card_issuer']['name'] == 'Mastercard') 
				 || (!$medio_amex && isset($p_method['card_issuer']) && isset($p_method['card_issuer']['name']) && $p_method['card_issuer']['name'] == 'American Express') ){
					
					$banco = array();
					$banco['tarjeta'] = $p_method['name'];
					$banco['banco'] = $p_method['name'];
					
					//veo sus cuotas
					$b_cuotas = isset($p_method['payer_costs']) ? $p_method['payer_costs'] : array();
					
					if($p_method['card_issuer']['name'] == 'Visa Argentina S.A.'){ 
						$banco['orden'] = 1;
						$medio_visa = true;
					}
					if($p_method['card_issuer']['name'] == 'Mastercard'){
						$banco['orden'] = 2;
						$medio_master = true;
					}
					if($p_method['card_issuer']['name'] == 'American Express'){
						$banco['orden'] = 3;
						$medio_amex = true;
					}							
					
					//cada cuota/banco
					foreach($b_cuotas as $c){
						$banco['cuotas'] = $c['installments'];
						$banco['origen'] = 'mercadopago';
						
						$metodo = $this->MP->getWhere($banco)->row();
						
						$banco['interes'] = $c['installment_rate'];
						
						//cft y tea
						if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
							list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
							
							if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
								//si viene en esta primera posicion, la uso
								$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
								$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
							}
							else{
								//si no,me fijo en la segunda posicion
								if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
									list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
								
									if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
										//si viene en esta primera posicion, la uso
										$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
										$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
									}
								}
							}
						}
						
						pre($banco);
						
						
					}
				}
			
			
			//obtengo los bancos emisores de tarjeta
			$pm_bancos = isset($p_method['exceptions_by_card_issuer']) ? $p_method['exceptions_by_card_issuer'] : array();
		
			if(count($pm_bancos) > 0){
				foreach($pm_bancos as $b){
					$banco = array();
					//08-09-2014
					//por defecto orden 4 ya que los primeros seran VISA MASTERCARD Y AMEX
					$banco['orden'] = 4;
					
					//fix 22-08-2014 -> si el Bco es Nativa Mastercard lo subdivido en Bco Nativa y Tarj Nativa y Mastercard
					//(segun mail Lisandro)
					if( $b['card_issuer']['name'] == 'Nativa Mastercard' ){
						//subdivido Nativa
						$banco['tarjeta'] = 'Nativa';
						$banco['banco'] = 'Nativa';
						
						$b_cuotas = isset($b['payer_costs']) ? $b['payer_costs'] : array();
					
						//cada cuota/banco
						foreach($b_cuotas as $c){
							$banco['cuotas'] = $c['installments'];
							
							$metodo = $this->MP->getWhere($banco)->row();
							
							$banco['interes'] = $c['installment_rate'];
							$banco['origen'] = 'mercadopago';							
							
							//cft y tea
							if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
								list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
								
								if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
									//si viene en esta primera posicion, la uso
									$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
									$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
								}
								else{
									//si no,me fijo en la segunda posicion
									if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
										list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
									
										if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
											//si viene en esta primera posicion, la uso
											$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
											$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
										}
									}
								}
							}
							
						pre($banco);
						
							
						}
						//-----------------------------------------------------------------------------
						
						//subdivido Mastercard
						$banco['tarjeta'] = 'Mastercard';
						$banco['banco'] = 'Nativa';
						
						$b_cuotas = isset($b['payer_costs']) ? $b['payer_costs'] : array();
					
						//cada cuota/banco
						foreach($b_cuotas as $c){
							$banco['cuotas'] = $c['installments'];
							
							$metodo = $this->MP->getWhere($banco)->row();
							
							$banco['interes'] = $c['installment_rate'];
							$banco['origen'] = 'mercadopago';
														
							//cft y tea
							if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
								list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
								
								if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
									//si viene en esta primera posicion, la uso
									$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
									$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
								}
								else{
									//si no,me fijo en la segunda posicion
									if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
										list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
									
										if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
											//si viene en esta primera posicion, la uso
											$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
											$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
										}
									}
								}
							}
							
							pre($banco);
						
						}
					}
					else{
						//Otros que no sean Nativa Mastercard
						
						/*
						//16-12-14
						//SE REEMPLAZA POR IF() arriba
						
						//08-09-2014
						//Si es VISA, MASTERCARD o AMERICAN EXPRESS -> tambien los doy de alta separados de los Bancos
						//solo 1 vez
						if( (!$medio_visa && $p_method['name'] == 'Visa') 
						 || (!$medio_master && $p_method['name'] == 'Mastercard') 
						 || (!$medio_amex && $p_method['name'] == 'American Express') ){
							$banco['tarjeta'] = $p_method['name'];
							$banco['banco'] = $p_method['name'];
							
							$b_cuotas = isset($b['payer_costs']) ? $b['payer_costs'] : array();
							
							if($p_method['name'] == 'Visa'){ 
								$banco['orden'] = 1;
								$medio_visa = true;
							}
							if($p_method['name'] == 'Mastercard'){
								$banco['orden'] = 2;
								$medio_master = true;
							}
							if($p_method['name'] == 'American Express'){
								$banco['orden'] = 3;
								$medio_amex = true;
							}							
							
							//cada cuota/banco
							foreach($b_cuotas as $c){
								$banco['cuotas'] = $c['installments'];
								
								$metodo = $this->MP->getWhere($banco)->row();
								
								$banco['interes'] = $c['installment_rate'];
								
								//insertaria siempre
								if(isset($metodo->id) && $metodo->id)
									$this->MP->update($metodo->id,$banco);
								else
									$this->MP->insert($banco);
							}
						}
						*/
						
						$banco['tarjeta'] = $p_method['name'];
						$banco['banco'] = $b['card_issuer']['name'];
						
						if($b['card_issuer']['name'] == 'Provencred')
							$banco['orden'] = 5;
						else
							$banco['orden'] = 4;
						
						$b_cuotas = isset($b['payer_costs']) ? $b['payer_costs'] : array();
					
						//cada cuota/banco
						foreach($b_cuotas as $c){
							$banco['cuotas'] = $c['installments'];
							
							$metodo = $this->MP->getWhere($banco)->row();
							
							$banco['interes'] = $c['installment_rate'];
							$banco['origen'] = 'mercadopago';							
							
							//cft y tea
							if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
								list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
								
								if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
									//si viene en esta primera posicion, la uso
									$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
									$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
								}
								else{
									//si no,me fijo en la segunda posicion
									if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
										list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
									
										if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
											//si viene en esta primera posicion, la uso
											$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
											$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
										}
									}
								}
							}
						
						pre($banco);
						
						}
					}
				}
			}
			else{
				//me fijo si viene otro metodo de pago como PAGO FACIL, RAPIPAGO, etc.
				//ó si el metodo es tarjeta de credito (pero el emisor no es ningun banco)
				
				//22-08-2014 la tarjeta "Nativa Mastercard" acá no la tengo en cuenta (segun mail Lisandro)
				if( $p_method['name'] != 'Nativa Mastercard'){
					$b_cuotas = isset($p_method['payer_costs']) ? $p_method['payer_costs'] : array();
					
					$banco = array();
					
					//en este caso el nombre no seria de la tarjeta en sí, sino del metodo de pago, ej: RAPIPAGO, AMERICAN EXPRESS
					
					//fix 22-08-2014 -> si el nombre de tarjeta es Cencosud, hago este fix para que aparezca junto con Cencosud->Mastercard en front
					//(segun mail Lisandro)
					if($p_method['name'] == 'Cencosud'){
						$banco['tarjeta'] = 'Cencosud';
						$banco['banco'] = 'Cencosud';
					}
					else{
						$banco['tarjeta'] = $p_method['name'];
						$banco['banco'] = 'Más formas de pago';
					}
					
					if($banco['banco'] == 'Más formas de pago'){
						$banco['orden'] = 6;
					}
					else{
						$banco['orden'] = 4;
					}
					
					//cada cuota/metodo de pago
					foreach($b_cuotas as $c){
						$banco['cuotas'] = $c['installments'];
						
						$metodo = $this->MP->getWhere($banco)->row();
						
						$banco['interes'] = $c['installment_rate'];
						$banco['origen'] = 'mercadopago';
						
						//cft y tea
						if(isset($c['labels']) && $c['labels'] && $c['labels'][0]){
							list($cft_str,$tea_str) = explode('|',$c['labels'][0]);
							
							if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
								//si viene en esta primera posicion, la uso
								$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
								$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
							}
							else{
								//si no,me fijo en la segunda posicion
								if(isset($c['labels']) && $c['labels'] && $c['labels'][1]){
									list($cft_str,$tea_str) = explode('|',$c['labels'][1]);
								
									if(isset($cft_str) && $cft_str && isset($tea_str) && $tea_str){
										//si viene en esta primera posicion, la uso
										$banco['cft'] = str_replace(array('CFT_','%',','),array('','','.'),$cft_str);
										$banco['tea'] = str_replace(array('TEA_','%',','),array('','','.'),$tea_str);
									}
								}
							}
						}
						
						pre($banco);
						
					}				
				}
			}
		}
		
	}


}