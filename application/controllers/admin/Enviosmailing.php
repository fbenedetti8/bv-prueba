<?php
include "AdminController.php";

class Enviosmailing extends AdminController{

    function __construct() {
        parent::__construct();
        $this->load->model('Enviomailing_model', 'Enviomailing');
        $this->model = $this->Enviomailing;
        $this->page = "enviosmailing";
        $this->data['currentModule'] = "mailings";
        $this->data['page'] = $this->page;
        $this->data['route'] = site_url('admin/' . $this->page);  
        $this->pageSegment = 4;
        $this->data['page_title'] = "Envios de Mailings";
        $this->limit = 50;
        $this->init();
        $this->validate = FALSE;

        $this->load->model('Reserva_estado_model','Reserva_estado');
        $estados = $this->Reserva_estado->getAll('','');
        $this->data['estados'] = $estados;
        
        $this->load->model('Reserva_model','Reserva');
        $this->load->model('Paquete_model','Paquete');
        $this->load->model("Mailing_model","Mailing");
        $this->load->model("Enviomailinghecho_model","Enviomailinghecho");
        $this->load->model("Enviomailingestado_model","Enviomailingestado");

    }

    function onBeforeEdit($id=''){
        parent::onBeforeEdit();
        
        $paquetes = $this->Paquete->getAllConReservas_mailings();
        foreach($paquetes->result() as $paq){
            $paq->titulo = $paq->nombre;
        }
        $this->data['paquetes'] = $paquetes;
        $this->data['mailings'] = $this->Mailing->getAll(999,0,'id','desc');
    }

    /*
        este metodo se usa cuando se genera un nuevo envio de mailing.
        el metodo carga los usuarios, quienes realizaron reserva sobre un determinado paquete, para enviarles el mailing correspondiente
    */
    function save(){
        extract($_POST);
        
        $mailing = $this->Mailing->get($mail_id)->row();
        $paquete_id = isset($mailing->paquete_id) ? $mailing->paquete_id : 0;

        if(!$paquete_id || !$mail_id || !$estado_id){
            header("location:" . $this->data['route']);
        }

        //genero registro para este envio de mailing
        $data = array(
                        "mail_id" => $mail_id,
                        "paquete_id" => $paquete_id
                    );              
        $envio_id = $this->model->insert($data);

        //15-07-19 el array de estados lo guardo en nueva tabla        
        foreach ($estado_id as $estado) {
            $data_estado = [];
            $data_estado['mailing_envio_id'] = $envio_id;
            $data_estado['estado_id'] = $estado;

            $this->Enviomailingestado->insert($data_estado);
        }
        
        //obtiene las reservas de dicho paquete que estén en el estado seleccionado
        $reservas = $this->Reserva->getAllByPaquete($paquete_id,$estado_id)->result();

        $this->realizarEnvio($envio_id,$reservas);
    }
    
    /*
        este metodo se usa para los casos en que se generaron nuevas reservas de usuarios sobre un determinado paquete,
        y ya se hizo un envio previo de mailing a los usuarios anteriores.
        el metodo carga los usuarios a enviarles el mailing correspondiente
    */
    function enviarRestantes($envio_id){
        $restantes = $this->model->getMailsInscriptosRestantes($envio_id)->result();
        
        $this->realizarEnvio($envio_id,$restantes);
    }
    
    //se usa via ajax para chequeo
    function chequearRestantes($envio_id){
        $restantes = $this->model->getMailsInscriptosRestantes($envio_id)->result();        
        if(empty($restantes)){
            echo "No hay reservas para enviar el mail.";
        }
        else
            echo "";
    }
    
    /*
        este metodo realiza el envio del mailing correspodiente a los usuarios seleccionados y registra el envio en la base de datos
    */
    function realizarEnvio($envio_id,$restantes){
        $ret = realizar_envio_mailing($envio_id,$restantes);
        
        header("location:" . $this->data['route']);
    }   

}