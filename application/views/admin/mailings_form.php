<?php echo $header;?>

<style>
.seleccion .col-md-3 { width:100%; text-align:left; }
.seleccion .col-md-9 { width:100%; text-align:left; margin-top:5px; }
.seleccion .col-md-9 span.select2 { width:100% !important; }
.btn-add-fecha { margin-top: 35px; }
.btn-add-habitacion { margin-top: 35px; }

  .tr_row input[type="text"] { width:70px; }
  .tr_row .form-group label { display:none; }
  .tr_row .form-group .col-md-9 {     margin: 0 auto; float: none; }
.datepicker.dropdown-menu { z-index: 10000 !important; }
</style>
  

  <!--=== Page Header ===-->
  <div class="page-header">
    <div class="page-title">
      <h3><?php echo implode(" &raquo; ", $this->breadcrumbs);?></h3>
    </div>
  </div>
  <br/>
  <form id="formEdit" class="form-horizontal row-border" method="post" action="<?php echo $route;?>/save" enctype="multipart/form-data">

  <div class="row">
    <div class="col-md-12"> 
  
      <!-- Tabs-->
      <div class="tabbable tabbable-custom tabs-left">
        <ul class="nav nav-tabs tabs-left">
          <li class="active"><a href="#tab1" data-toggle="tab">Datos generales</a></li>
          <li><a id="tabPrev" href="#tab2" data-toggle="tab">Previsualización</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab1">
          
              <div class="widget box">
                <div class="widget-header">
                  <h3 style="margin-bottom:20px;">Propiedades</h3>
                </div>
                <div class="widget-content">
                  <div class="row">
                    <div class="col-md-12"> 
                        <?php echo $this->admin->input('asunto', 'Asunto', 'required', $row, false); ?>
                     
                        <?php echo $this->admin->combo('destino_id', 'Destino', 's2 ', $row, $destinos, 'id', 'destino',false); ?>
                        
                        <?php echo $this->admin->combo('paquete_id', 'Paquete', 's2 ', $row, $paquetes, 'id', 'paquete',false); ?>

                        <?php echo $this->admin->combo('formato', 'Importar Formato', 's2 ', $row, $mailings_formatos, 'nombre', 'nombre',false); ?>


                        <div id="data_previas" style="position: relative; width: 100%; float: left; display:none;">
                      
                          <div class="alert alert-info">
                            <div class="form-group div_btnPrevias" id="" style="display:none;">
                              <b>Previsualización</b>: Para guardar los datos y previsualizar el mailing en el tab <b>Previsualización</b> haga click en el siguiente botón: 
                                <button class="btn btn-orange btn-sm btnSave" type="button" id="" style="">Grabar y previsualizar</button> 
                            </div>
                          </div>
                          
                          <div class="alert alert-info">
                            <b>Tamaño de imágenes</b>: Para una correcta visualización de imágenes en el envío se recomienda un ancho máximo de 600px, que es el tamaño máximo del contenedor del mailing.
                          </div>
                          
                          <div style="width:100%; float:left;">
                            <div class="form-group" id="div_participar_previas">
                              <label class="col-md-2 control-label" for="participar_previas">Participá de las previas</label>
                              <div class="col-md-10 clearfix">
                                <textarea id="participar_previas" name="participar_previas" style="border: 1px solid #000;">
                                </textarea>
                              </div>
                            </div>
                          </div>
                          <div style="width:100%; float:left;">
                            <div class="form-group" id="div_previas_info_adicional">
                              <label class="col-md-2 control-label" for="previas_info_adicional">Información adicional</label>
                              <div class="col-md-10 clearfix">
                                <textarea id="previas_info_adicional" name="previas_info_adicional" style="border: 1px solid #000;">
                                </textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <div id="data_pre_viaje" style="position: relative; width: 100%; float: left; display:none;">
                          
                          <div class="alert alert-info">
                              <div class="form-group div_btnPrev" id="" style="display:none;padding:0;">
                                <b>Previsualización</b>: Para guardar los datos y previsualizar el mailing en el tab <b>Previsualización</b> haga click en el siguiente botón: 
                                  <button class="btn btn-orange btnSave" type="button" id="" style="">Grabar y previsualizar</button>
                              </div>
                          </div>
                          
                          <div class="alert alert-info">
                            <b>Tamaño de imágenes</b>: Para una correcta visualización de imágenes en el envío se recomienda un ancho máximo de 600px, que es el tamaño máximo del contenedor del mailing.
                          </div>
                          
                          <div style="width:100%; float:left;">
                            <div class="form-group" id="div_sobre_lugares_salida">
                              <label class="col-md-2 control-label" for="sobre_lugares_salida">Sobre los lugares de salida</label>
                              <div class="col-md-10 clearfix">
                                <textarea id="sobre_lugares_salida" name="sobre_lugares_salida" style="border: 1px solid #000;">
                                </textarea>
                              </div>
                            </div>
                          </div>

                          <div style="width:100%; float:left;">
                            <div class="form-group" id="div_que_hay_que_llevar">
                              <label class="col-md-2 control-label" for="que_hay_que_llevar">¿Qué hay que llevar?</label>
                              <div class="col-md-10 clearfix">
                                <textarea id="que_hay_que_llevar" name="que_hay_que_llevar" style="border: 1px solid #000;">
                                </textarea>
                              </div>
                            </div>
                          </div>

                          <div style="width:100%; float:left;">
                            <div class="form-group" id="div_en_el_micro">
                              <label class="col-md-2 control-label" for="en_el_micro">¿En el micro/avión?</label>
                              <div class="col-md-10 clearfix">
                                <textarea id="en_el_micro" name="en_el_micro" style="border: 1px solid #000;">
                                </textarea>
                              </div>
                            </div>
                          </div>

                          <div style="width:100%; float:left;">
                            <div class="form-group" id="div_sobre_el_viaje">
                              <label class="col-md-2 control-label" for="sobre_el_viaje">Sobre el viaje</label>
                              <div class="col-md-10 clearfix">
                                <textarea id="sobre_el_viaje" name="sobre_el_viaje" style="border: 1px solid #000;">
                                </textarea>
                              </div>
                            </div>
                          </div>

                          <div style="width:100%; float:left;">
                            <div class="form-group" id="div_coordinadores">
                              <label class="col-md-2 control-label" for="coordinadores">Coordinadores</label>
                              <div class="col-md-10 clearfix">
                                <textarea id="coordinadores" name="coordinadores" style="border: 1px solid #000;">
                                </textarea>
                              </div>
                            </div>
                          </div>

                          <div style="width:100%; float:left;">
                            <div class="form-group" id="div_info_adicional">
                              <label class="col-md-2 control-label" for="info_adicional">Información adicional</label>
                              <div class="col-md-10 clearfix">
                                <textarea id="info_adicional" name="info_adicional" style="border: 1px solid #000;">
                                </textarea>
                              </div>
                            </div>
                          </div>


                        </div>
                        
                        
                    </div>

                  </div>
                </div>
                
                </div>
            
          </div>
              
          <div class="tab-pane" id="tab2">
          
              <div class="widget box">
              
              <div class="widget-header">
                <h3 style="margin-bottom:20px;">Previsualización de Contenido</h3>
              </div>
              <div class="widget-content">
                <div class="row">
                <div class="col-md-12">
                 
                  <div class="form-group" id="div_contenido">
                    <? $name = 'contenido'; $label='Contenido'; ?>

                    <div class="col-md-12 clearfix">
                      <textarea id="<?=$name;?>" name="<?=$name;?>" style="border: 1px solid #000;"><?=isset($row->contenido)?base64_decode($row->contenido):"";?></textarea>
                    </div>
                  </div>

                </div>
                </div>
              </div>
              
              </div>
             
          </div>
           
         </div>

        
        <div class="form-actions">
          <input type="hidden" name="id" id="id" value="<?php if (isset($row->id)) echo $row->id;?>" />
          <input type="hidden" name="paquete_id_hidden" id="paquete_id_hidden" value="<?=isset($row->paquete_id)?$row->paquete_id:'';?>" />
        
          <input type="submit" value="Grabar" class="btn btn-primary">
          <input type="button" value="Cancelar" class="btn btn-default" onclick="history.back();">
        </div> 

    </div>
  </div>

  </form>

<script src="<?=base_url();?>media/admin/ckeditor3/ckeditor.js"></script>

    
<?php echo $footer; ?>

<script>
var reload_coords = true;

$(document).ready(function(){

  CKEDITOR.config.height = '300px';
  CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
  CKEDITOR.config.language = 'es';
  CKEDITOR.config.removePlugins = 'elementspath';
  CKEDITOR.config.resize_enabled = false;
  CKEDITOR.config.htmlEncodeOutput = true;
  CKEDITOR.config.basicEntities  = true;
  CKEDITOR.config.entities = true;
  CKEDITOR.config.filebrowserImageUploadUrl = '<?=site_url('admin/uploader');?>';
  CKEDITOR.config.filebrowserUploadMethod = 'form';
  CKEDITOR.config.toolbar = [
      { name: 'basicstyles', items: [ 'Bold', 'Underline', 'Link' ] },
      { name: 'insert', items: [ 'Image', 'Emojione']}
  ];
  /*'Image'*/

  CKEDITOR.replace( 'contenido' );
  CKEDITOR.replace( 'sobre_lugares_salida' );
  CKEDITOR.replace( 'que_hay_que_llevar' );
  CKEDITOR.replace( 'en_el_micro' );
  CKEDITOR.replace( 'sobre_el_viaje' );
  CKEDITOR.replace( 'coordinadores' );
  CKEDITOR.replace( 'info_adicional' );
  CKEDITOR.replace( 'participar_previas' );
  CKEDITOR.replace( 'previas_info_adicional' );
  

    $('.tab_container').tabs();
    
    //se usaria solo para pre viaje
    $('body').on('click','.btnSave',function(e){
      e.preventDefault();
      
      $.fancybox.showLoading();
      
      var view = $('#div_contenido .nicEdit-main').html();
      var id = $('#id').val();
      var formato = $('#formato').val();
      var asunto = $('#asunto').val();
      var paquete_id = $('#paquete_id').val();
     /* var sobre_lugares_salida = $('#div_sobre_lugares_salida .nicEdit-main').html();
      var que_hay_que_llevar = $('#div_que_hay_que_llevar .nicEdit-main').html();
      var en_el_micro = $('#div_en_el_micro .nicEdit-main').html();
      var sobre_el_viaje = $('#div_sobre_el_viaje .nicEdit-main').html();
      var coordinadores = $('#div_coordinadores .nicEdit-main').html();
      var info_adicional = $('#div_info_adicional .nicEdit-main').html();
      var previas_info_adicional = $('#div_previas_info_adicional .nicEdit-main').html();
      var participar_previas = $('#div_participar_previas .nicEdit-main').html();
      var contenido = nicEditors.findEditor('contenido').getContent();*/
      

      var sobre_lugares_salida = CKEDITOR.instances.sobre_lugares_salida.getData();
      var que_hay_que_llevar = CKEDITOR.instances.que_hay_que_llevar.getData();
      var en_el_micro = CKEDITOR.instances.en_el_micro.getData();
      var sobre_el_viaje = CKEDITOR.instances.sobre_el_viaje.getData();
      var coordinadores = CKEDITOR.instances.coordinadores.getData();
      var info_adicional = CKEDITOR.instances.info_adicional.getData();
      var previas_info_adicional = CKEDITOR.instances.previas_info_adicional.getData();
      var participar_previas = CKEDITOR.instances.participar_previas.getData();
      var contenido = CKEDITOR.instances.contenido.getData();

      //actuliza el mailing segun los valores de los campos y luego recarga el contenido del final
      $.post("<?=base_url();?>admin/mailings/update",{sobre_lugares_salida: sobre_lugares_salida, //de pre viaje
                              que_hay_que_llevar: que_hay_que_llevar, //de pre viaje
                              en_el_micro: en_el_micro, //de pre viaje
                              sobre_el_viaje: sobre_el_viaje, //de pre viaje
                              coordinadores: coordinadores, //de pre viaje
                              info_adicional: info_adicional, //de pre viaje
                              participar_previas: participar_previas, //de previas
                              previas_info_adicional: previas_info_adicional, //de previas
                              paquete_id: paquete_id,
                              formato: formato,
                              asunto: asunto,
                              contenido: contenido, //para otros
                              id: id},
      function(data){
        if(data.view){
          /*
          var iframe = document.getElementById('preview');
          iframe.src = iframe.src;
          */
          $('#id').val(data.id);
          /*nicEditors.findEditor('contenido').setContent('');
          nicEditors.findEditor('contenido').setContent(data.view);*/
          
          CKEDITOR.instances.contenido.setData(data.view);

          $('#tabPrev').trigger('click');
          $.fancybox.hideLoading();
        }     
      },'json');
    });
    
    
    $('body').on('change','#formato',function(e){
      var formato = $(this).val();
      var paquete_id = $('#paquete_id').val();
      var paquete_id_h = $('#paquete_id_hidden').val();
      var id = $('#id').val();
      
      //12-03-2015 para cuando el paquete NO este activo (paquete_id no aparece en el combo) lo tomo desde campo hidden
      if(paquete_id == '' && paquete_id_h != ''){
        paquete_id = paquete_id_h;
      }
      
      if((formato == 'Pre Viaje' || formato == 'Pre Viaje Extranjeros') && paquete_id == ''){
        jAlert('Debes elegir primero un paquete','Atención');
        return false;
      }
      
      if(formato == 'Pre Viaje' || formato == 'Pre Viaje Extranjeros'){
        
        $('.div_btnPrev').show();
        $('#data_pre_viaje').show();
        $('#data_previas').hide();
      }
      else if(formato == 'Previas'){
        
        $('.div_btnPrev').show();
        $('#data_previas').show();
        $('#data_pre_viaje').hide();
      }
      else{
        
        $('#data_pre_viaje').hide();
        $('#data_previas').hide();
        $('.div_btnPrev').hide();
      }
      
      $.fancybox.showLoading();
      $.post("<?=base_url();?>admin/mailings/get_contenido",{reload_coords: reload_coords, formato: formato, paquete_id: paquete_id, id: id},function(data){
        reload_coords = true;
        if(data.success){
          $.fancybox.hideLoading();
          
          if(data.view){
            /*nicEditors.findEditor('contenido').setContent('');
            nicEditors.findEditor('contenido').setContent(data.view);*/

            ////CKEDITOR.instances.contenido.setData('');
            CKEDITOR.instances.contenido.setData(data.view);

            //para los mailings que no son de PRE VIAJE les cargo el tab previsualizacion
            $('#tabPrev').trigger('click');
          }
          else{
            //pre viaje o previas
            if(formato == 'Pre Viaje' || formato == 'Pre Viaje Extranjeros'){
              /*nicEditors.findEditor('sobre_lugares_salida').setContent('');
              nicEditors.findEditor('sobre_lugares_salida').setContent(data.sobre_lugares_salida);
              
              nicEditors.findEditor('que_hay_que_llevar').setContent('');
              nicEditors.findEditor('que_hay_que_llevar').setContent(data.que_hay_que_llevar);
              
              nicEditors.findEditor('en_el_micro').setContent('');
              nicEditors.findEditor('en_el_micro').setContent(data.en_el_micro);
              
              nicEditors.findEditor('sobre_el_viaje').setContent('');
              nicEditors.findEditor('sobre_el_viaje').setContent(data.sobre_el_viaje);
              
              nicEditors.findEditor('coordinadores').setContent('');
              nicEditors.findEditor('coordinadores').setContent(data.coordinadores);
              
              nicEditors.findEditor('info_adicional').setContent('');
              nicEditors.findEditor('info_adicional').setContent(data.info_adicional);*/

              //CKEDITOR.instances.sobre_lugares_salida.setData('');
              CKEDITOR.instances.sobre_lugares_salida.setData(data.sobre_lugares_salida);

              //CKEDITOR.instances.que_hay_que_llevar.setData('');
              CKEDITOR.instances.que_hay_que_llevar.setData(data.que_hay_que_llevar);
              
              //CKEDITOR.instances.en_el_micro.setData('');
              CKEDITOR.instances.en_el_micro.setData(data.en_el_micro);
              
              //CKEDITOR.instances.sobre_el_viaje.setData('');
              CKEDITOR.instances.sobre_el_viaje.setData(data.sobre_el_viaje);
              
              //CKEDITOR.instances.coordinadores.setData('');
              CKEDITOR.instances.coordinadores.setData(data.coordinadores);
              
              //CKEDITOR.instances.info_adicional.setData('');
              CKEDITOR.instances.info_adicional.setData(data.info_adicional);

            }
            else if(formato == 'Previas'){
              /*nicEditors.findEditor('participar_previas').setContent('');
              nicEditors.findEditor('participar_previas').setContent(data.participar_previas);
              
              nicEditors.findEditor('previas_info_adicional').setContent('');
              nicEditors.findEditor('previas_info_adicional').setContent(data.previas_info_adicional);*/
              
              //CKEDITOR.instances.participar_previas.setData('');
              CKEDITOR.instances.participar_previas.setData(data.participar_previas);

              //CKEDITOR.instances.previas_info_adicional.setData('');
              CKEDITOR.instances.previas_info_adicional.setData(data.previas_info_adicional);
            }
          }
        }
        else{
          $.fancybox.hideLoading();
        }     
      },'json');
    });
    
    $('body').on('change','#destino_id',function(e){
      var destino_id = $(this).val();
      
       $.fancybox.showLoading();
        $.get("<?=base_url();?>admin/mailings/get_paquetes/"+destino_id,function(data){
          if(data.success){
            $('#paquete_id option').remove();
            $('#paquete_id').trigger('change');
            $.each(data.paquetes,function(i,el){
              var newOption = new Option(el.paquete, el.id, false, false);
              $('#paquete_id').append(newOption).trigger('change');
            });
            $.fancybox.hideLoading();
          }
        },'json');
    });

    $('body').on('change','#paquete_id',function(e){
      var formato = $('#formato').val();
      var paquete_id = $(this).val();
      var id = $('#id').val();
      
      if((formato == 'Pre Viaje' || formato == 'Pre Viaje Extranjeros') && paquete_id != ''){
        $.fancybox.showLoading();
        $.post("<?=base_url();?>admin/mailings/get_contenido",{formato: formato, paquete_id: paquete_id, id: id},function(data){
          if(data.success){
            $.fancybox.hideLoading();
            
            if(data.view){
             /* nicEditors.findEditor('contenido').setContent('');
              nicEditors.findEditor('contenido').setContent(data.view);*/

              //CKEDITOR.instances.contenido.setData('');
              CKEDITOR.instances.contenido.setData(data.view);

              //para los mailings que no son de PRE VIAJE les cargo el tab previsualizacion
              $('#tabPrev').trigger('click');
            }
            else{
              //pre viaje     
             /* nicEditors.findEditor('sobre_lugares_salida').setContent('');
              nicEditors.findEditor('sobre_lugares_salida').setContent(data.sobre_lugares_salida);
              
              nicEditors.findEditor('que_hay_que_llevar').setContent('');
              nicEditors.findEditor('que_hay_que_llevar').setContent(data.que_hay_que_llevar);
              
              nicEditors.findEditor('en_el_micro').setContent('');
              nicEditors.findEditor('en_el_micro').setContent(data.en_el_micro);
              
              nicEditors.findEditor('sobre_el_viaje').setContent('');
              nicEditors.findEditor('sobre_el_viaje').setContent(data.sobre_el_viaje);
              
              nicEditors.findEditor('coordinadores').setContent('');
              nicEditors.findEditor('coordinadores').setContent(data.coordinadores);
              
              nicEditors.findEditor('info_adicional').setContent('');
              nicEditors.findEditor('info_adicional').setContent(data.info_adicional);*/

              //CKEDITOR.instances.sobre_lugares_salida.setData('');
              CKEDITOR.instances.sobre_lugares_salida.setData(data.sobre_lugares_salida);

              //CKEDITOR.instances.que_hay_que_llevar.setData('');
              CKEDITOR.instances.que_hay_que_llevar.setData(data.que_hay_que_llevar);

              //CKEDITOR.instances.en_el_micro.setData('');
              CKEDITOR.instances.en_el_micro.setData(data.en_el_micro);

              //CKEDITOR.instances.sobre_el_viaje.setData('');
              CKEDITOR.instances.sobre_el_viaje.setData(data.sobre_el_viaje);

              //CKEDITOR.instances.coordinadores.setData('');
              CKEDITOR.instances.coordinadores.setData(data.coordinadores);

              //CKEDITOR.instances.info_adicional.setData('');
              CKEDITOR.instances.info_adicional.setData(data.info_adicional);
            }
          }
          else{
            $.fancybox.hideLoading();
          }
        },'json');
      }
    });
    
    <? if(isset($row->formato) && $row->formato != ''){ ?>
      setTimeout(function(){
          reload_coords = false;
          $('#formato').trigger('change');
      },2000);
    <? } ?>
  });
</script>