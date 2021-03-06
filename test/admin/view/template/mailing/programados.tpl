<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">   
        <div class="pull-right">
            <a href="<?php echo $btn_excel; ?>" data-toggle="tooltip" title="<?php echo $export_excel; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
            <!--<a href="<?php //echo $btn_pdf; ?>" id="pdf" data-toggle="tooltip" title="<?php //echo $export_pdf; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>-->
            <a id="refresh" data-toggle="tooltip" title="<?php echo $refresh; ?>" class="btn btn-default"><i id="spin" class="fa fa-refresh"></i></a>
            
            <a id="delete-progamados" data-toggle="tooltip" title="<?php echo $eliminar; ?>" class="btn btn-danger"><i  class="fa fa-trash"></i></a>

        </div>      
            <h1><? echo $titulo;?></h1>
            <!--breadcrumbs de navegacion-->
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!--zona de mensaje de errores y confirmación de acciones-->
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>

        <div class="panel panel-default">
          <div class="panel-heading"> 
            <h3 class="panel-title"><i class="fa fa-list"></i><?php echo $detalle;?></h3>        
          </div>
          <div class="panel-body">              
            <div class="row well">  
                <div class="btn-group col-sm-3 " style="margin-top: 5px"  role="group" >
                <br>         
                 <?php if ($volumen == 'masivo') { ?>
                      <a  href="<?php echo $btn_masivo;?>" type="button" class="btn btn-primary historial_filter"><?php echo $masivo;?></a>
                      <a href="<?php echo $btn_unico;?>" id='mostrar' type="button" class="btn btn-default historial_filter"><?php echo $unico;?></a>
                      <a href="<?php echo $btn_api?>" type="button" class="btn btn-default historial_filter"><?php echo $api;?></a>
                      <?
                     }elseif ($volumen == 'api') { ?>
                      <a  href="<?php echo $btn_masivo;?>" type="button" class="btn btn-default historial_filter"><?php echo $masivo;?></a>
                      <a href="<?php echo $btn_unico;?>" id='mostrar' type="button" class="btn btn-default historial_filter"><?php echo $unico;?></a>
                      <a href="<?php echo $btn_api?>" type="button" class="btn btn-primary historial_filter"><?php echo $api;?></a>
                      <?
                     }else{ ?>
                      <a  href="<?php echo $btn_masivo;?>" type="button" class="btn btn-default historial_filter"><?php echo $masivo;?></a>
                      <a href="<?php echo $btn_unico;?>" id='mostrar' type="button" class="btn btn-primary historial_filter"><?php echo $unico;?></a>
                      <a href="<?php echo $btn_api?>" type="button" class="btn btn-default historial_filter"><?php echo $api;?></a>
                      <?
                     }?>
                </div> 
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label" for="input-nombre"> Nombre </label>
                    <input type="text" name="filter_nombre" value="<?php echo $filter_nombre; ?>"  id="input-nombre" class="form-control" />
                  </div>                          
                </div>  

                <div class="col-sm-4">                          
                  <div class="form-group">
                    <label class="control-label" for="input-fecha"> Fecha </label>
                    <input type="text" name="filter_fecha" value="<?php echo $filter_fecha; ?>"  id="input-fecha" class="form-control" />
                  </div>
                </div>                      
                <div class="col-sm-1"> 
                    <div class="form-group"> 
                        <br>                         
                        <button type="button" style="margin-top: 5px" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $btn_filtrar; ?></button>
                    </div>
                </div>              
            </div> 
            <br/>
            <form method="post" enctype="multipart/form-data" id="form-category">
              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="table_edit">
                  <thead>
                    <tr>
                    <!--header-->

                        <td><input type='checkbox' id='slc_all'></td>

                        <?php

                            foreach($headers as $value){
                            if ($value !='Estadisticas') {
                                ?><td><?php echo $value;?></td><?
                            }else{
                                if ($volumen == 'masivo') {
                                    ?><td><?php echo $value;?></td><?
                                }
                            }
                        }?>                     
                    </tr>
                  </thead>
                  <tbody>                 
                    <?php 
                    if($envios){
                        if ($volumen == 'masivo') {

                            foreach($envios as  $value) {
                                ?><tr id='tr_<?php echo $value['id_envio']; ?>'>
                                    <td><input type='checkbox' class='chk_prog' data-id='<?php echo $value['id_envio']; ?>'></td>
                                    <td><?php echo $value['fecha']; ?></td>
                                    <td><?php echo $value['nombre']; ?></td>
                                    <!-- <td><?php echo $value['tipo']; ?></td> -->

                                    <?php if (strtolower($value['estado'])=='terminado') {
                                        ?><td ><span class="label label-success"><?php echo $value['estado']; ?></span></td><?
                                    }elseif($value['estado']=='Pendiente'){
                                        ?><td ><span class="label label-grey"><?php echo $value['estado']; ?></span></td><?
                                    }else{
                                        ?><td ><span class="label label-grey"><?php echo $value['estado']; ?></span></td><?
                                    }?>

                                    <td><?php echo $value['volumen']; ?></td>

                                    <td>
                                        
                                        <a id='edit-masivo' class="btn btn-success btn-edit-envio" data-toggle="modal" data-target="#editmail" title="Editar" data-id='<?php echo $value['id_envio']; ?>'><i class="fa fa-edit"></i></a>
                                    </td>                               


                                </tr><?php
                            }
                        }else{ 
                            foreach($envios as  $value) {
                                ?><tr id='tr_<?php echo $value['id_envio']; ?>'>
                                    <td><input type='checkbox' class='chk_prog' data-id='<?php echo $value['id_envio']; ?>'></td>
                                    <td><?php echo $value['fecha']; ?></td>
                                    <td><?php echo $value['remitente']; ?></td>
                                    <td><?php echo $value['destinatario']; ?></td>
                                    <td><?php echo $value['nombre']; ?></td>
                                    <td><?php echo $value['asunto']; ?></td>

                                    <?php if ($value['estado']=='Terminado' || $value['estado']=='Abierto' || $value['estado']=='Click' || $value['estado']=='Entregado') {
                                        ?><td ><span class="label label-success"><?php echo $value['estado']; ?></span></td><?
                                    }elseif($value['estado']=='Pendiente'){
                                        ?><td ><span class="label label-grey"><?php echo $value['estado']; ?></span></td><?
                                    }elseif($value['estado']=='Rebote'){
                                        ?><td ><span class="label label-danger"><?php echo $value['estado']; ?></span></td><?
                                    }else{
                                        ?><td ><span class="label label-grey"><?php echo $value['estado']; ?></span></td><?
                                    }?> 


                                    <td >
                                        <a id="msg"  href="<?php echo $btn_mensaje.'&envio='.$value['id_envio'].'&cont=&d='.$value['destinatario']; ?>" class="btn btn-primary" target="_blank" ><i class="fa fa-eye"></i></a>
                                        <a id="edit-unico" data-id='<?php echo $value['id_envio']; ?>' data-toggle="modal" data-target="#editmail" title="<?php echo $editar; ?>" class="btn btn-success">
                                            <i  class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                </tr><?php
                            }
                         }
                        
                    }else{
                        ?>
                        <tr>
                            <td class="text-center" colspan="12"><?php echo $text_no_results; ?></td>
                        </tr>
                        <?php
                        }?>

                  </tbody>
                </table>
              </div>
            </form>
            <div class="row">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
          </div>
        </div>
    </div>

    <!--
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myUpdateModal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: #4eaefa; color: white; font-weight: bold;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel">Mensaje</h3>
              </div>

                <div class="modal-body" id="actualizar">
                  
                </div>            
            </div>
          </div>
        </div>
    -->
    <?php echo $footer; ?>
</div>


<div class="modal fade" id='editmail' tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Editar MAIL programado</h4>
            </div>

            <form>  
                
                <input id='idmail' type='hidden'>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="input_titulo">Titulo envío</label>                                
                        <input type="text" name="Titulo" id="input_titulo" class="form-control" disabled/>
                        <div class='text-danger' id="danger_titulo"></div>                                    
                    </div>
                    

                    <div class="form-group">
                        <label>Fecha</label>                                
                          <input type="date" name="fecha_envio" value="" id="input_fecha" class="input-text" />
                    </div>

                    <div class="form-group">
                        <label>Hora</label>
                            <input type="time" name="hora_envio"  id="input_hora" value=""  class="input-text" />   
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id='btn-upd-mail'>Guardar</button>
                </div>

            </form>
                

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript" src="view/javascript/emailprogramados.js"></script>








<script type="text/javascript">
    function error () {
        alert('Este envio no tiene datos, presione actualizar para refrescar estados.');
    }

    $('a.fa-eye').on('click',function(){
            
        var id = $(this).prop('id');

        document.getElementById('actualizar').innerHTML = "";

        //$('#actualizar').append("<div contenteditable='false' style='width:100%; border: 1px solid #ccc;'>"+ id +"</div>");                
        $('#actualizar').append( id );                

    });
</script>


      