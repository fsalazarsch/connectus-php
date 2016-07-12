<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
      <!--botones de accion-->
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
      
      <div class="panel panel-default">
        <div class="panel-heading"> 
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>        
        </div>
        <div class="panel-body"> 
          <div class="well">
          <form action="<?php echo $update; ?>" method="post" enctype="multipart/form-data" id="form-category">
            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="table_edit">
              <?php echo json_encode($contacto);?>
              <?php  for ($i=0; $i < count($contacto['campos']) ; $i++) { ?>
                  <div class="form-group required">
                      <label><?php echo $contacto['glosa_campos'][$i] ?></label>
                      <div class="col-sm-10">
                        <input type="text" name="<?php echo $contacto['campos'][$i];?>" value="<?php echo $contacto['valores'][$i];?>">
                      </div>
                  </div>
                  <?php }?> 
              </table>
            </div>
          </form>
            <input type="submit" name="actualizar" value="Actualizar" class="btn btn-primary" />
          </div>
        </div>
      </div>
  </div>  

  <!--Editar nombre de la lista-->
  <!--<script type="text/javascript">
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
  </script>-->
</div>
<?php echo $footer; ?>