<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      <a href="<?php echo $mis_listas;?>" class="btn btn-default" data-toggle="tooltip" title="Mis Listas"><i class="fa fa-mail-reply" ></i></a>
      <?php if ($editar) {  ?>
      <a id="crear" data-target="#myModal" data-toggle="modal" title="<?php echo $button_add; ?>" class="btn btn-primary" onclick="$('input[id*=\'mtxts\']').prop('value', '');"><i class="fa fa-plus" ></i></a>      
        <button  type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-category').submit() : false;"><i class="fa fa-trash-o"></i></button>
       <?
     }?>
      
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
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
    <!--Panel detalle de lista-->
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i><?php echo $text_detalle_lista;?></h3>
      </div>
      <div class="panel-body">
      <table id="info_lista" class="table table-bordered table-hover">
        <tr class="blackground">
          <td><?php echo $text_nombre ;?></td><td><?php echo $text_num_reg ;?></td><td><?php echo $text_creacion;?></td><td><?php echo $text_actualizacion;?></td><!--<td></td>-->
        </tr>
        <tr id="tr_lista_info">
        <?php if ($editar) {
          ?>
            <td id="td_nombre" contenteditable='true' id="td_nombre" data-toggle="tooltip" title="Pinche para editar nombre"><?php echo html_entity_decode($nombre) ;?></td><td><?php echo $num_reg ;?></td><td><?php echo $creacion;?></td><td><?php echo $actualizacion;?></td>
          <?
        }else{
            ?>
            <td id="td_nombre"><?php echo html_entity_decode($nombre) ;?></td><td><?php echo $num_reg ;?></td><td><?php echo $creacion;?></td><td><?php echo $actualizacion;?></td>
            <?
          } ?>
          
        </tr>
      </table>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading"> 
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>        
      </div>
      <div class="panel-body">       
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-category">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="table_edit">
              <thead>
                <tr>
                <?php if ($editar) { ?>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                <?php } ?>
                  
                  <?php foreach ($contactos['nombre_columnas'] as $nombre) {
                      ?><th><a href="<?php echo $nombre[1] ?>" class="<?php echo strtolower($order); ?>"><?php echo $nombre[0]; ?></a></th><?php
                  }?>
                  <?php if ($editar) {
                    ?><td><?php echo $column_action; ?></td><?
                  }?>                  
                </tr>
              </thead>
              <tbody>
                <?php if ($contactos['valores']) { ?>
                <?php foreach ($contactos['valores'] as $contacto) { ?>
                  <tr>
                  <!--carga de control-->
                  <?php if ($editar) { ?>
                    <td class="text-center"><?php if (in_array($contacto['id_contacto'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $contacto['id_contacto']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $contacto['id_contacto']; ?>" />
                        <?php } ?></td>
                  <? } ?>
                  <!--cargar los campos de la lista y sus respectivos valores-->
                  <?php foreach ($contacto as $campo => $valor) { 
                    if($campo!='id_contacto'){?> 
                      <td class="text-left"><?php echo $valor; ?></td>
                    <?php } ?>
                  <?php } ?>
                  <!--carga de botones de accion de cada row de la tabla-->
                  <?php if ($editar) {
                     ?><td > <div class="pull-right"><a type="button" id="<?php echo $contacto['id_contacto']; ?>" value="<?php echo $contacto['id_contacto']; ?>"  data-toggle="modal" data-target="#updateModal" title="<?php echo $button_edit; ?>"  class="btn btn-primary fa fa-pencil" ></a></div></td><?                  
                  }?>
                  </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
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

<!-- Modal para crear contactos-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #4eaefa; color: white; font-weight: bold;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel"><?php echo $modal_title; ?></h3>
      </div>
      <form action="<?php echo $maddaction;?>" id="FormularioModal" method="POST">
        <div class="modal-body" id="contenedor">
           <?php for ($i = 0; $i < count($form['campo']); $i++) { 
            if($form['campo'][$i]=='email'){
                ?> <label class='col-sm-2 control-label'><?php echo $form['glosa'][$i];?></label ><div class='col-sm-10'><input id="mtxts[]" placeholder='e.j: correo@ejemplo.cl' type='text' class='form-control' required value='' name='<?php echo $form['campo'][$i];?>' /><div id="txtval"></div><br></div><?php
              }elseif($form['campo'][$i]=='celular'){
                ?> <label class='col-sm-2 control-label'><?php echo $form['glosa'][$i];?></label ><div class='col-sm-10'><input id="mtxts[]" placeholder='e.j: 56987654321' type='text' class='form-control'  value='' name='<?php echo $form['campo'][$i];?>' /><div id=""></div><br></div><?php
              }else{
                ?>
                <label class='col-sm-2 control-label'><?php echo $form['glosa'][$i];?></label ><div class='col-sm-10'><input id="mtxts[]" type='text' class='form-control' required value='' name='<?php echo $form['campo'][$i];?>' /><br></div>
                <? }?>
             
          <? } ?>
        </div>
        <div class="modal-footer">
          <button type="button" id="mcan" class="btn btn-default" data-dismiss="modal"><?php echo $btn_cancel;?></button>
          <input type="submit" id="madd" class="btn btn-primary" value="<?php echo $btn_guardar;?>">
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal para editar contactos-->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myUpdateModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background: #4eaefa; color: white; font-weight: bold;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel"><?php echo $modal_title; ?></h3>
      </div>
      <form  action="<?php echo $action;?>" id="updateForm" method="POST">
        <div class="modal-body" id="actualizar">
          
        </div>
        <div class="modal-footer">
          <button type="button" id="mcan" class="btn btn-default" data-dismiss="modal"><?php echo $btn_cancel;?></button>
          <input type="submit" id="mact" class="btn btn-primary" value="<?php echo $btn_actualizar_contacto;?>">
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<?php if ($editar) {  ?>
  <script type="text/javascript">
    $('td[id*=\'ocultar]\'').show();
  </script>
<? } else{ ?>
    <script type="text/javascript">
      $('td[id*=\'ocultar]\'').hide();
    </script>
<? } ?>
 

  <!--Editar nombre de la lista-->
<script type="text/javascript">
  $('#td_nombre').focusout(function(){
    var id_lista = <?php echo $id_lista;?>;
    var url = 'index.php?route=contactos/lista_modo_edit/updateLista&token=<?php echo $token; ?>';
    var nombre_lista = $('#td_nombre').html();
           
    if (nombre_lista) {
      url += '&update=' + encodeURIComponent(nombre_lista);
    }

    if(id_lista){              
      url += '&id_lista=' + encodeURIComponent(id_lista);
    }

    location = url;
  });
</script>

<script type="text/javascript">
  $('a.fa-pencil').on('click',function(){
        
    var id = $(this).prop('id');

      $.ajax(
        {                   
        url: "index.php?route=contactos/lista_modo_edit/traerContacto&token=<?php echo $token; ?>",
        type: "POST",               
        dataType: 'json',
        data: { id_contacto: id },               
        success: function (json) { 
          document.getElementById('actualizar').innerHTML = '';         
          for (var i = 0; i < json['nombre_columnas'].length; i++) {
           $('#actualizar').append("<label >"+json['nombre_columnas'][i]+"</label ><input type='text' class='form-control' value='"+json['valores'][0][json['campos_de_contacto'][i]]+"' name='" + json['campos_de_contacto'][i] +"' />");
          };          
        },
        error: function(){
          alert('Problemas para actualizar el contacto!');
        }
    });
  });
</script>

<script type="text/javascript">
/*boton crear contacto*/
  $('#crear').on('click', function(){
    $('#mact').hide();
    $('#madd').show();
  });
</script>

<script type="text/javascript">
  $('input[name*=\'email\']').on('keyup',function(){
    document.getElementById('txtval').innerHTML = '';
    //$('#txtval').append($(this).val());
    validateEmail($(this).val());
  });

function validateEmail(email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (!emailReg.test(email)) {
        $('#txtval').append("<span style='color: red;'>correo inválido<i class='fa fa-remove'></span>");
    } else {
         $('#txtval').append("<span style='color: green;'>Correo válido<i class='fa fa-check'></i></span>");
    }
}

</script>
</div>
<?php echo $footer; ?>