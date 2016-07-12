<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
        <div class="pull-right">   
        <a href="<?php echo $full_excel; ?>" data-toggle="tooltip" title="<?php echo 'Exportar Excel'; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
        <!--<a href="<?php //echo $full_pdf; ?>" id="pdf" data-toggle="tooltip" title="<?php //echo 'Exportar PDF'; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>-->
        <a id="refresh" data-toggle="tooltip" title="Refrescar" class="btn btn-default"><i id="spin" class="fa fa-refresh"></i></a>       
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <div class="well">
                    <div>
                        <div class="row" >
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="control-label" for="input-fecha"> Fecha </label>
                                <input type="text" name="filter_fecha" value="<?php echo $filter_fecha; ?>"  id="input-fecha" class="form-control" />
                              </div>
                              <div class="form-group">
                                <label class="control-label" for="input-email">Email</label>
                                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>"  id="input-email" class="form-control" />
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="control-label" for="input-remitente">Nombre remitente</label>
                                <input type="text" name="filter_remitente" value="<?php echo $filter_remitente; ?>"  id="input-remitente" class="form-control" />
                              </div>
                              <div class="form-group">
                                <label class="control-label" for="input-correo-remitente">Correo Remitente </label>
                                <input type="text" name="filter_correo_remitente" value="<?php echo $filter_correo_remitente; ?>"  id="input-correo-remitente" class="form-control" />
                              </div>
                            </div>
                            <div class="col-sm-4 form-center">                                
                                <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">                    
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td class="text-left"><?php if ($sort == 'cuando_enviar') { ?>
                                <a href="<?php echo $sort_fecha_envio; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_fecha_envio; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_fecha_envio; ?>"><?php echo $column_fecha_envio; ?></a>
                                <?php } ?></td>

                                <td class="text-left"><?php if ($sort == 'destinatario') { ?>
                                <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                                <?php } ?></td>                                                                       
                                
                                <td class="text-left"><?php if ($sort == 'remitente') { ?>
                                <a href="<?php echo $sort_nombre_remitente; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_nombre_remitente; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_nombre_remitente; ?>"><?php echo $column_nombre_remitente; ?></a>
                                <?php } ?></td>

                                <td class="text-left"><?php if ($sort == 'correo_remitente') { ?>
                                <a href="<?php echo $sort_correo_remitente; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_correo_remitente; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_correo_remitente; ?>"><?php echo $column_correo_remitente; ?></a>
                                <?php } ?></td>
                                
                                <td class="text-left"><?php echo $column_estado; ?></td>

                                <td class="text-left"><?php echo $column_action; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($lista_rebotados) { ?>
                            <?php foreach ($lista_rebotados as $listas) { ?>
                            <tr>
                                <td class="text-left"><?php echo utf8_encode($listas['fecha_envio']); ?></td>
                                <td class="text-left"><?php echo utf8_encode($listas['email']); ?></td>
                                <td class="text-left"><?php echo utf8_encode($listas['nombre_remitente']); ?></td>
                                <td class="text-left"><?php echo utf8_encode($listas['correo_remitente']); ?></td>                                
                                <td ><span class="label label-danger"><?php echo $listas['estado']; ?></span></td>                          
                                    
                                <td class="text-left">
                                    <a type="button" data-toggle="modal" data-target="#updateModal" title="<?php echo $button_edit; ?>" id="<?php echo $listas['id_envio']; ?>" class="btn btn-primary fa fa-eye"></a>                                    
                                    <a href="<?php echo $listas['reenviar']; ?>" data-toggle="tooltip" title="<?php echo $button_reenviar; ?>" class="btn btn-success fa fa-mail-reply"></a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>                    
                </div>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar contactos-->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myUpdateModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #4eaefa; color: white; font-weight: bold;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title" id="myModalLabel">Detalle del rebote</h3>
          </div>

            <div class="modal-body" id="actualizar">
              
            </div>            
        </div>
      </div>
    </div>

    <script type="text/javascript">
        $('#button-filter').on('click', function() {
            var url = 'index.php?route=mailing/lista_mail_rebotados&token=<?php echo $token; ?>';


            var filter_fecha = $('input[name=\'filter_fecha\']').val();

            if (filter_fecha) {
                url += '&filter_fecha=' + encodeURIComponent(filter_fecha);
            }

            var filter_email = $('input[name=\'filter_email\']').val();

            if (filter_email) {
                url += '&filter_email=' + encodeURIComponent(filter_email);
            }

            var filter_remitente = $('input[name=\'filter_remitente\']').val();

            if (filter_remitente) {
                url += '&filter_remitente=' + encodeURIComponent(filter_remitente);
            }

            var filter_correo_remitente = $('input[name=\'filter_correo_remitente\']').val();

            if (filter_correo_remitente) {
                url += '&filter_correo_remitente=' + encodeURIComponent(filter_correo_remitente);
            }
           

            location = url;
        });
    </script>   

    <script type="text/javascript"> 

        $('#refresh').on('click' , function(){
            var d = document.getElementById("spin");
            d.className = d.className + " fa-spin";

            $.ajax(
                    {                   
                    url: "index.php?route=mailing/historial/refresh&token=<?php echo $token; ?>",
                    type: "POST",                                       
                    success: function () {              
                        alert('Se han refrescado los estados de los envios');
                        d.className = "fa fa-refresh" ;
                        location = "index.php?route=mailing/lista_mail_rebotados&token=<?php echo $token; ?>";

                    }

                }); 
        });
    </script>

    <script type="text/javascript">
      $('a.fa-eye').on('click',function(){
            
        var id = $(this).prop('id');

          $.ajax(
            {                   
            url: "index.php?route=mailing/lista_mail_rebotados/getEnvio&token=<?php echo $token; ?>",
            type: "POST",               
            dataType: 'json',
            data: { id_envio: id },               
            success: function (json) { 
              
              document.getElementById('actualizar').innerHTML = "";

              $('#actualizar').append("<label >Nombre remitente</label ><input type='text' class='form-control' value='"+json['nombre_remitente']+"'/><label style='margin-top:5px'>Correo remitente</label ><input type='text' class='form-control' value='"+json['correo_remitente']+"'/><label style='margin-top:5px'>Destinatario</label ><input type='text' class='form-control' value='"+json['email']+"'/><label style='margin-top:5px'>Fecha de envio</label ><input type='text' class='form-control' value='"+json['fecha_envio']+"'/><label style='margin-top:5px'>Asunto</label ><input type='text' class='form-control' value='"+json['titulo']+"'/><label style='margin-top:5px'>Mensaje</label ><div contenteditable='false' style='width:100%; border: 1px solid #ccc;'>"+ json['cuerpo'] +"</div>");                  

            },
            error: function(){
              alert('Problemas para recuperar los datos');
            }
        });
      });

    </script>

</div>
<?php echo $footer; ?>  