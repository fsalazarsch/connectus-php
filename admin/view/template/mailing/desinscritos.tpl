<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
        <div class="pull-right">        
        <a href="<?php echo $full_excel; ?>" data-toggle="tooltip" title="<?php echo $button_export_excel_all; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
        <!--<a href="<?php //echo $full_pdf; ?>" id="pdf" data-toggle="tooltip" title="<?php //echo $button_export_pdf_all; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>-->
        <?php if ($tipo_usuario == 'administrador') { ?>
            <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-primary" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-category').submit() : false;"><i class="fa fa-pencil"></i></button>
        <?php } ?>
        
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
                                <input type="text" name="filter_destino" value="<?php echo $filter_destino; ?>"  id="input-email" class="form-control" />
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="control-label" for="input-remitente">Remitente</label>
                                <input type="text" name="filter_remitente" value="<?php echo $filter_remitente; ?>"  id="input-telefono" class="form-control" />
                              </div>
                              <div class="form-group">
                                <label class="control-label" for="input-correo-campania">Campaña</label>
                                <input type="text" name="filter_campania" value="<?php echo $filter_campania; ?>"  id="input-correo-remitente" class="form-control" />
                              </div>
                            </div>
                            <div class="col-sm-4 form-center">                                
                                <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $inscribir;?>" method="post" enctype="multipart/form-data" id="form-category">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                            <?php if ($tipo_usuario == 'administrador') { ?>
                                <td style="width: 1px;" class="text-center"><input type="checkbox" id="select_all" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                            <?php } ?>
                                <td class="text-left"><?php if ($sort == 'fecha') { ?>
                                    <a href="<?php echo $sort_nombre; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_fecha; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_nombre; ?>"><?php echo $column_fecha; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'email') { ?>
                                    <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_remitente; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_email; ?>"><?php echo $column_remitente; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'telefono') { ?>
                                    <a href="<?php echo $sort_telefono; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_mail; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_telefono; ?>"><?php echo $column_mail; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $column_campania; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($desinscritos) { ?>
                            <?php foreach ($desinscritos as $listas) { ?>
                            <tr>
                                <?php if($tipo_usuario == 'administrador'){ ?>
                                    <td class="text-center"><?php if (in_array($listas['id_contacto'], $selected)) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $listas['id_contacto']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $listas['id_contacto'] ?>" />
                                    <?php } ?></td> 
                                <?php } ?>
                                <td class="text-left"><?php echo $listas['fecha']; ?></td>

                                <td class="text-left"><?php echo $listas['remitente']; ?></td>
                                <td class="text-left"><?php echo $listas['email']; ?>
                                <td class="text-left"><?php echo $listas['campania_desinscrito']; ?></td>                               
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
                </form>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
        $('#button-filter').on('click', function() {

            var url = 'index.php?route=mailing/desinscritos&token=<?php echo $token; ?>';

            var filter_fecha = $('input[name=\'filter_fecha\']').val();

            if (filter_fecha) {
                url += '&filter_fecha=' + encodeURIComponent(filter_fecha); 
            }
            
            var filter_remitente = $('input[name=\'filter_remitente\']').val();
            
            if (filter_remitente) {
                url += '&filter_remitente=' + encodeURIComponent(filter_remitente);
            }   

            var filter_destino = $('input[name=\'filter_destino\']').val();

            if (filter_destino) {
                url += '&filter_destino=' + encodeURIComponent(filter_destino); 
            }
            
            var filter_campania = $('input[name=\'filter_campania\']').val();
            
            if (filter_campania) {
                url += '&filter_campania=' + encodeURIComponent(filter_campania);
            }         

            location = url;
        });
//--></script>
<?php echo $footer; ?>  