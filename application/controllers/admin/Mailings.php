<?php
include "AdminController.php";

class Mailings extends AdminController{

    function __construct() {
        parent::__construct();
        $this->load->model('Mailing_model', 'Mailing');
        $this->model = $this->Mailing;
        $this->page = "mailings";
        $this->data['currentModule'] = "mailings";
        $this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);  
        $this->pageSegment = 4;
        $this->data['page_title'] = "Mailings";
        $this->limit = 50;
        $this->init();
        $this->validate = FALSE;

        $this->data['mailings_formatos'] = array(array('nombre' => 'Pre Viaje'),array('nombre' => 'Pre Viaje Extranjeros'),array('nombre' => 'Previas'),array('nombre' => 'Informacion Importante'));
            
        //se cargan por ajax
        $this->data['paquetes'] = array();

        $this->data['destinos'] = $this->db->query("select id, nombre as destino from bv_destinos where publicado = 1 order by nombre asc");
        
        $this->load->model('Paquete_model','Paquete');
        $this->load->model('Destino_model','Destino');

        
    }
    
    function index(){
        $this->model->filters = '1=1';

        $this->data['visibilidad'] = '';
        //filtro de activo
        if(isset($_POST['visibilidad']) && ($_POST['visibilidad']=='activos' || $_POST['visibilidad']=='inactivos') ){
            $this->data['visibilidad'] = $_POST['visibilidad'];
            $this->model->filters .= ' and activo = '.($_POST['visibilidad']=='activos'?1:0);
            $this->session->set_userdata('visibilidad',$_POST['visibilidad']);
        }
        else{
            if(isset($_POST['visibilidad']) && $_POST['visibilidad'] == ''){
                $this->session->unset_userdata('visibilidad');
            }
            
            if($this->session->userdata('visibilidad')){
                $this->data['visibilidad'] = $this->session->userdata('visibilidad');
                $this->model->filters .= ' and activo = '.($this->session->userdata('visibilidad')=='activos'?1:0);
            }
        }
        
        parent::index();
    }

    function get_paquetes($destino_id){
        $ret['success'] = true;
        $ret['paquetes'] = $this->db->query("select id, destino_id, CONCAT(codigo,' ',nombre,' - ', case when activo = 1 then 'Activo' else 'Inactivo' end ) as paquete from bv_paquetes where destino_id = ".$destino_id." and (activo = 1 or (activo = 0 and fecha_fin > date(now())) or (activo = 0 and year(fecha_inicio) = year(now()) and year(fecha_fin) = year(now())) ) order by fecha_inicio desc")->result();

        echo json_encode($ret);
    }

    function duplicate($id){
        $data = $this->model->get($id);
        
        $data_to_insert = array();
        foreach($data->result_array() as $row){     
            $row['copia_id'] = $row['id'];
            unset($row['id']);
            $data_to_insert = $row;
        }
        
        $id_ins = $this->model->insert($data_to_insert);
        redirect($this->data['route']);
    }
    
    function cargar_mail($titulo,$contenido){
        $data['title'] = $titulo; 
        $data['contenido'] = $contenido; 

        $this->load->model('Sucursal_model','Sucursal');
        $this->Sucursal->filters = "id in (3)";
        $data['sucursales'] = $this->Sucursal->getAll(1,0,'id','asc')->result();
        
        $o = new stdClass();
        $o->telefono = '(011) 5235-3810. Lunes a Viernes de 10 a 19 hs.';
        $data['orden'] = $o;

        //cargo footer de mail generico
        $data['mail_footer'] = $this->load->view('mails/mail_footer',$data,true);
        
        return $this->load->view('admin/mailings/mailings_base',$data,true);
    }

    function enviar_prueba(){
        extract($_POST);
        if(isset($id)){
            $mailing = $this->model->get($id)->row();
            //$emailfrom = $this->config->item('email_info');       
            $emailfrom = 'reservas@buenas-vibras.com.ar';
            //$emailto = $this->config->item('email_info');
            $emailto = $email;
            //$emailto = "mrodriguez@id4you.com";
            $asunto = $mailing->asunto;
            
            $mailing->contenido = html_entity_decode(base64_decode($mailing->contenido));
            
            $mensaje = $this->cargar_mail($mailing->formato,$mailing->contenido);
            if( enviarMail($emailfrom,$emailto,$bcc='',$asunto,$mensaje) )
                echo 1;
        }
        else
            echo 0;
    }
    

    function preview($id){
        $this->data['row'] = $this->model->get($id)->row();
        $this->load->view('admin/mailings_preview',$this->data);
    }
    
    function data_contenido(){
        //obtengo datos del paquete
        if(isset($_POST['paquete_id']) && $_POST['paquete_id'] > 0){
            $paquete = $this->Paquete->get($_POST['paquete_id'])->row();
            $paquete->fecha_inicio = fecha_salida($paquete->fecha_inicio);
            
            $destino = $this->Destino->get($paquete->destino_id)->row();
            $agencia = false;
            $data['agencia'] = $agencia;
        }
        else{
            $paquete = array();
        }
        
        $data['paquete'] = $paquete;

        foreach($_POST as $k=>$v){
            $data[$k] = $v;
        }
        
        //prevas
        if($_POST['formato'] == 'Previas'){
            $contenido = $this->load->view('admin/mailings/mailing_previas',$data,true);
        }
        else if($_POST['formato'] == 'Pre Viaje'){
            //pre viaje
            if(isset($paquete->en_avion) && $paquete->en_avion){
                $contenido = $this->load->view('admin/mailings/mailing_pre_viaje_avion',$data,true);
            }
            else{
                $contenido = $this->load->view('admin/mailings/mailing_pre_viaje',$data,true);
            }
        }
        else if($_POST['formato'] == 'Pre Viaje Extranjeros'){
            //pre viaje extranjeros
            if(isset($paquete->en_avion) && $paquete->en_avion){
                $contenido = $this->load->view('admin/mailings/mailing_pre_viaje_extranjeros_avion',$data,true);
            }
            else{
                $contenido = $this->load->view('admin/mailings/mailing_pre_viaje_extranjeros',$data,true);
            }
        }
        else{//otro
            $contenido = $_POST['contenido'];
        }

        return $contenido;
    }

    //actualiza contenido para recargar contenido de mail pre viaje segun los campos que vengan
    function update(){
        $contenido = $this->data_contenido();        

        $datos_update = array();
        foreach($_POST as $k=>$v){
            $datos_update[$k] = ($v);
        }

        $datos_update['contenido'] = base64_encode($contenido);
        $datos_update['sobre_lugares_salida'] = base64_encode($datos_update['sobre_lugares_salida']);
        $datos_update['que_hay_que_llevar'] = base64_encode($datos_update['que_hay_que_llevar']);
        $datos_update['en_el_micro'] = base64_encode($datos_update['en_el_micro']);
        $datos_update['sobre_el_viaje'] = base64_encode($datos_update['sobre_el_viaje']);
        $datos_update['coordinadores'] = base64_encode($datos_update['coordinadores']);
        $datos_update['info_adicional'] = base64_encode($datos_update['info_adicional']);

        
        if(!isset($_POST['id']) || (isset($_POST['id']) && $_POST['id'] == ''))
            $_POST['id'] = $this->model->insert($datos_update);
        else
            $this->model->update($_POST['id'],$datos_update);
        
        $mail = $this->model->get($_POST['id'])->row();
        $mail->contenido = base64_decode($mail->contenido);

        $ret['id'] = $_POST['id'];
        $ret['view'] = $mail->contenido;
        echo json_encode($ret);

    }
        
    function onBeforeSave(){
        if($_POST['contenido'] == ''){
            $_POST['contenido'] = $this->data_contenido();
        }
        
        $_POST['contenido'] = base64_encode($_POST['contenido']);

        $_POST['sobre_lugares_salida'] = base64_encode($_POST['sobre_lugares_salida']);
        $_POST['que_hay_que_llevar'] = base64_encode($_POST['que_hay_que_llevar']);
        $_POST['en_el_micro'] = base64_encode($_POST['en_el_micro']);
        $_POST['sobre_el_viaje'] = base64_encode($_POST['sobre_el_viaje']);
        $_POST['coordinadores'] = base64_encode($_POST['coordinadores']);
        $_POST['info_adicional'] = base64_encode($_POST['info_adicional']);

    }   

    function get_contenido(){
        if(isset($_POST['formato']) && $_POST['formato']){
            $data['datos_existentes'] = false;
            
            $data['paquete'] = array();
            $data['reload_coordinadores'] = false;
            
            //si el mailing ya tiene datos precargados los tomo de ahí
            if(isset($_POST['id']) && $_POST['id'] > 0){
                $mailing = $this->model->get($_POST['id'])->row();
                $mailing->contenido = html_entity_decode(base64_decode($mailing->contenido));
                $mailing->sobre_lugares_salida = html_entity_decode(base64_decode($mailing->sobre_lugares_salida));
                $mailing->que_hay_que_llevar = html_entity_decode(base64_decode($mailing->que_hay_que_llevar));
                $mailing->en_el_micro = html_entity_decode(base64_decode($mailing->en_el_micro));
                $mailing->sobre_el_viaje = html_entity_decode(base64_decode($mailing->sobre_el_viaje));
                $mailing->coordinadores = html_entity_decode(base64_decode($mailing->coordinadores));
                $mailing->info_adicional = html_entity_decode(base64_decode($mailing->info_adicional));
                
                //si carga el del pre viaje
                if($_POST['formato'] == 'Pre Viaje'){
                    if(isset($_POST['paquete_id']) && $_POST['paquete_id'] > 0){
                        $paquete = $this->Paquete->get($_POST['paquete_id'])->row();
                        $paquete->fecha_inicio = fecha_salida($paquete->fecha_inicio);
                        $paquete->coordinadores = $this->Paquete->getCoordinadoresData($_POST['paquete_id'])->result();
                        $paquete->celulares = $this->Paquete->getCelularesData($_POST['paquete_id']);
                        $data['paquete'] = $paquete;
                        $data['mailing'] = $mailing;
                        
                        //si carga el de pre viaje y el paquete es el mismo que el del mailing, tomo los datos de ahi
                        if($_POST['formato'] == 'Pre Viaje' && $mailing->paquete_id == $_POST['paquete_id']){
                            $data['success'] = true;
                            $data['sobre_lugares_salida'] = $mailing->sobre_lugares_salida;
                            $data['que_hay_que_llevar'] = $mailing->que_hay_que_llevar;
                            $data['en_el_micro'] = $mailing->en_el_micro;
                            $data['sobre_el_viaje'] = $mailing->sobre_el_viaje;
                            $data['coordinadores'] = $mailing->coordinadores;
                            $data['info_adicional'] = $mailing->info_adicional;
                            $data['datos_existentes'] = true;
                            
                            if($_POST['paquete_id'] == 397)
                                $data['reload_coordinadores'] = true;
                        }
                        else if($mailing->paquete_id != $_POST['paquete_id'] && $mailing->copia_id > 0){
                            //si fue copiado de otro paquete
                            $mailing = $this->model->get($mailing->copia_id)->row();
                            $mailing->contenido = html_entity_decode(base64_decode($mailing->contenido));
                            $mailing->sobre_lugares_salida = html_entity_decode(base64_decode($mailing->sobre_lugares_salida));
                            $mailing->que_hay_que_llevar = html_entity_decode(base64_decode($mailing->que_hay_que_llevar));
                            $mailing->en_el_micro = html_entity_decode(base64_decode($mailing->en_el_micro));
                            $mailing->sobre_el_viaje = html_entity_decode(base64_decode($mailing->sobre_el_viaje));
                            $mailing->coordinadores = html_entity_decode(base64_decode($mailing->coordinadores));
                            $mailing->info_adicional = html_entity_decode(base64_decode($mailing->info_adicional));

                            $data['success'] = true;
                            $data['sobre_lugares_salida'] = $mailing->sobre_lugares_salida;
                            $data['que_hay_que_llevar'] = $mailing->que_hay_que_llevar;
                            $data['en_el_micro'] = $mailing->en_el_micro;
                            $data['sobre_el_viaje'] = $mailing->sobre_el_viaje;
                            if($paquete->en_avion)
                                $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje_avion/coordinadores',$data,true);
                            else
                                $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje/coordinadores',$data,true);
                            $data['info_adicional'] = $mailing->info_adicional;
                            $data['datos_existentes'] = true;
                            
                        }
                    }
                }
                else if($_POST['formato'] == 'Pre Viaje Extranjeros'){ //17-07-15 nuevo formato para extranjeros
                    if(isset($_POST['paquete_id']) && $_POST['paquete_id'] > 0){
                        $paquete = $this->Paquete->get($_POST['paquete_id'])->row();
                        $paquete->fecha_inicio = fecha_salida($paquete->fecha_inicio);
                        $paquete->coordinadores = $this->Paquete->getCoordinadoresData($_POST['paquete_id'])->result();
                        $paquete->celulares = $this->Paquete->getCelularesData($_POST['paquete_id']);
                        $data['paquete'] = $paquete;
                        $data['mailing'] = $mailing;
                        
                        //si carga el de pre viaje y el paquete es el mismo que el del mailing, tomo los datos de ahi
                        if($_POST['formato'] == 'Pre Viaje Extranjeros' && $mailing->paquete_id == $_POST['paquete_id']){
                            $data['success'] = true;
                            $data['sobre_lugares_salida'] = $mailing->sobre_lugares_salida;
                            $data['que_hay_que_llevar'] = $mailing->que_hay_que_llevar;
                            $data['en_el_micro'] = $mailing->en_el_micro;
                            $data['sobre_el_viaje'] = $mailing->sobre_el_viaje;
                            $data['coordinadores'] = $mailing->coordinadores;
                            $data['info_adicional'] = $mailing->info_adicional;
                            $data['datos_existentes'] = true;
                            
                            if($_POST['paquete_id'] == 397)
                                $data['reload_coordinadores'] = true;
                        }
                        else if($mailing->paquete_id != $_POST['paquete_id'] && $mailing->copia_id > 0){
                            //si fue copiado de otro paquete
                            $mailing = $this->model->get($mailing->copia_id)->row();
                            $mailing->contenido = html_entity_decode(base64_decode($mailing->contenido));
                            $mailing->sobre_lugares_salida = html_entity_decode(base64_decode($mailing->sobre_lugares_salida));
                            $mailing->que_hay_que_llevar = html_entity_decode(base64_decode($mailing->que_hay_que_llevar));
                            $mailing->en_el_micro = html_entity_decode(base64_decode($mailing->en_el_micro));
                            $mailing->sobre_el_viaje = html_entity_decode(base64_decode($mailing->sobre_el_viaje));
                            $mailing->coordinadores = html_entity_decode(base64_decode($mailing->coordinadores));
                            $mailing->info_adicional = html_entity_decode(base64_decode($mailing->info_adicional));
                            
                            $data['success'] = true;
                            $data['sobre_lugares_salida'] = $mailing->sobre_lugares_salida;
                            $data['que_hay_que_llevar'] = $mailing->que_hay_que_llevar;
                            $data['en_el_micro'] = $mailing->en_el_micro;
                            $data['sobre_el_viaje'] = $mailing->sobre_el_viaje;
                            if($paquete->en_avion)
                                $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros_avion/coordinadores',$data,true);
                            else
                                $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/coordinadores',$data,true);
                            $data['info_adicional'] = $mailing->info_adicional;
                            $data['datos_existentes'] = true;
                            
                        }
                    }
                }
                else if($_POST['formato'] == 'Previas'){
                    //si carga el de previas y el formato es el mismo que el del mailing, tomo los datos de ahi
                    if($mailing->formato == $_POST['formato']){
                        $data['success'] = true;
                        $data['participar_previas'] = $mailing->participar_previas;
                        $data['previas_info_adicional'] = $mailing->previas_info_adicional;
                        $data['datos_existentes'] = true;
                    }
                }
                else if($_POST['formato'] == 'Informacion Importante'){
                    //si carga el de Informacion Importante y el formato es el mismo que el del mailing, tomo los datos de ahi
                    if($mailing->formato == $_POST['formato']){
                        $data['success'] = true;
                        $data['view'] = $mailing->contenido;
                        $data['datos_existentes'] = true;
                    }
                }
            }
            else{
                if(isset($_POST['paquete_id']) && $_POST['paquete_id'] > 0){
                    $paquete = $this->Paquete->get($_POST['paquete_id'])->row();
                    $paquete->fecha_inicio = fecha_salida($paquete->fecha_inicio);
                    $paquete->coordinadores = $this->Paquete->getCoordinadoresData($_POST['paquete_id'])->result();
                    $paquete->celulares = $this->Paquete->getCelularesData($_POST['paquete_id']);
                    $data['paquete'] = $paquete;
                }
            }
            
            //tomo de las secciones genericas
            if($_POST['formato'] == 'Pre Viaje'){
                if(!$data['datos_existentes']){
                    if($paquete->en_avion){
                        $data['success'] = true;
                        $data['sobre_lugares_salida'] = $this->load->view('admin/mailings/secciones_pre_viaje_avion/sobre_lugares_salida',$data,true);
                        $data['que_hay_que_llevar'] = $this->load->view('admin/mailings/secciones_pre_viaje_avion/que_hay_que_llevar',$data,true);
                        $data['en_el_micro'] = $this->load->view('admin/mailings/secciones_pre_viaje_avion/en_el_micro',$data,true);
                        $data['sobre_el_viaje'] = $this->load->view('admin/mailings/secciones_pre_viaje_avion/sobre_el_viaje',$data,true);
                        $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje_avion/coordinadores',$data,true);
                        $data['info_adicional'] = $this->load->view('admin/mailings/secciones_pre_viaje_avion/info_adicional',$data,true);
                        
                        //$data['view'] = $this->load->view('admin/mailings/mailing_pre_viaje_avion',$data,true);
                    }
                    else{
                        $data['success'] = true;
                        $data['sobre_lugares_salida'] = $this->load->view('admin/mailings/secciones_pre_viaje/sobre_lugares_salida',$data,true);
                        $data['que_hay_que_llevar'] = $this->load->view('admin/mailings/secciones_pre_viaje/que_hay_que_llevar',$data,true);
                        $data['en_el_micro'] = $this->load->view('admin/mailings/secciones_pre_viaje/en_el_micro',$data,true);
                        $data['sobre_el_viaje'] = $this->load->view('admin/mailings/secciones_pre_viaje/sobre_el_viaje',$data,true);
                        $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje/coordinadores',$data,true);
                        $data['info_adicional'] = $this->load->view('admin/mailings/secciones_pre_viaje/info_adicional',$data,true);
                        
                        //echo $this->load->view('admin/mailings/mailing_pre_viaje',$data,true);
                    }
                }
                else{
                    if($data['reload_coordinadores']){
                        //$data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje/coordinadores',$data,true);
                    }
                    
                    if(isset($_POST['reload_coords']) && $_POST['reload_coords']){
                        $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje/coordinadores',$data,true);    
                    }
                }
            }
            else if($_POST['formato'] == 'Pre Viaje Extranjeros'){ //17-07-15 nuevo formato de extranjeros
                if(!$data['datos_existentes']){
                    if($paquete->en_avion){
                        $data['success'] = true;
                        $data['sobre_lugares_salida'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros_avion/sobre_lugares_salida',$data,true);
                        $data['que_hay_que_llevar'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros_avion/que_hay_que_llevar',$data,true);
                        $data['en_el_micro'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros_avion/en_el_micro',$data,true);
                        $data['sobre_el_viaje'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros_avion/sobre_el_viaje',$data,true);
                        $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros_avion/coordinadores',$data,true);
                        $data['info_adicional'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros_avion/info_adicional',$data,true);
                        
                        //$data['view'] = $this->load->view('admin/mailings/mailing_pre_viaje_extranjeros_avion',$data,true);
                    }
                    else{
                        $data['success'] = true;
                        $data['sobre_lugares_salida'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/sobre_lugares_salida',$data,true);
                        $data['que_hay_que_llevar'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/que_hay_que_llevar',$data,true);
                        $data['en_el_micro'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/en_el_micro',$data,true);
                        $data['sobre_el_viaje'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/sobre_el_viaje',$data,true);
                        $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/coordinadores',$data,true);
                        $data['info_adicional'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/info_adicional',$data,true);
                        
                        //echo $this->load->view('admin/mailings/mailing_pre_viaje_extranjeros',$data,true);
                    }
                }
                else{
                    if($data['reload_coordinadores']){
                        //$data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/coordinadores',$data,true);
                    }
                    
                    if(isset($_POST['reload_coords']) && $_POST['reload_coords']){
                        $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/coordinadores',$data,true);    
                    }
                }
            }
            else if($_POST['formato'] == 'Previas'){
                if(!$data['datos_existentes']){
                    $data['success'] = true;
                    $data['participar_previas'] = $this->load->view('admin/mailings/secciones_previas/participar_previas',$data,true);
                    $data['previas_info_adicional'] = $this->load->view('admin/mailings/secciones_previas/previas_info_adicional',$data,true);
                }
            }
            else if($_POST['formato'] == 'Informacion Importante'){
                if(!$data['datos_existentes']){
                    $data['success'] = true;
                    $data['view'] = $this->load->view('admin/mailings/mailing_info_importante','',true);
                }
            }
            else{
                $data['error'] = true;
            }
        }
        else{
            $data['error'] = true;
        }
        
        echo json_encode($data);
    }
    
    
    //16-07-15 test para ver el template de mailing
    function ver_template(){
        $data = array();
        
        $data['sobre_lugares_salida'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/sobre_lugares_salida',$data,true);
        $data['que_hay_que_llevar'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/que_hay_que_llevar',$data,true);
        $data['en_el_micro'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/en_el_micro',$data,true);
        $data['sobre_el_viaje'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/sobre_el_viaje',$data,true);
        $data['coordinadores'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/coordinadores',$data,true);
        $data['info_adicional'] = $this->load->view('admin/mailings/secciones_pre_viaje_extranjeros/info_adicional',$data,true);
        
        $this->load->view('admin/mailings/mailing_pre_viaje_extranjeros');
    }

    function onEditReady($id=''){
        if($id){
            $this->data['paquetes'] = $this->db->query("select id, destino_id, CONCAT(codigo,' ',nombre) as paquete from bv_paquetes where destino_id = ".$this->data['row']->destino_id." and activo = 1 or (activo = 0 and fecha_fin > date(now())) or (activo = 0 and year(fecha_inicio) = year(now()) and year(fecha_fin) = year(now())) order by fecha_inicio desc")->result();

        }
    }

}
