<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
        <div class="pull-right">   
        <a href="<?php echo $full_excel; ?>" data-toggle="tooltip" title="<?php echo 'Exportar Excel'; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
        <!--<a href="<?php //echo $full_pdf; ?>" id="pdf" data-toggle="tooltip" title="<?php //echo 'Exportar PDF'; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>-->
         <button type="button" data-toggle="tooltip" title="Borrar" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-download').submit() : false;"><i class="fa fa-trash-o"></i></button>     
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>        
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
                                    <button type="button" style="margin-top: 5px" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-download">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                            <td style="width: 1px;" class="text-center"><input type="checkbox" id="select_all" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                <td class="text-left"><?php if ($sort == 'nombre') { ?>
                                    <a href="<?php echo $sort_nombre; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_nombre; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_nombre; ?>"><?php echo $column_nombre; ?></a>
                                    <?php } ?></td>

                                    <td class="text-left"><?php if ($sort == 'nombre') { ?>
                                    <a href="<?php echo $sort_autor; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_contactos; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_autor; ?>"><?php echo $column_contactos; ?></a>
                                    <?php } ?></td>
                                

                                <td class="text-left"><?php if ($sort == 'fecha_creacion') { ?>
                                    <a href="<?php echo $sort_fecha_creacion; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_fecha_creacion; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_fecha_creacion; ?>"><?php echo $column_fecha_creacion; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $column_action; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($lista_preferidos) { ?>
                            <?php foreach ($lista_preferidos as $listas) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($listas['id_mensaje'], $selected)) { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $listas['id_mensaje']; ?>" checked="checked" />
                                <?php } else { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $listas['id_mensaje']; ?>" />
                                <?php } ?></td> 
                                <td class="text-left"><?php echo utf8_encode($listas['titulo']); ?></td>

                                <td class="text-left"><?php echo utf8_encode($listas['autor']); ?></td>
                                <td class="text-left"><?php echo $listas['fecha_creacion']; ?></td>
                                    
                                <td class="text-left">
                                    <a href="<?php echo $listas['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
                    </form>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"><!--
        $('#button-filter').on('click', function() {
            var url = 'index.php?route=mailing/lista_mails_predefinidos&token=<?php echo $token; ?>';

            var filter_nombre = $('input[name=\'filter_nombre\']').val();

            if (filter_nombre) {
                url += '&filter_nombre=' + encodeURIComponent(filter_nombre);
            }

            var filter_fecha = $('input[name=\'filter_fecha\']').val();

            if (filter_fecha) {
                url += '&filter_fecha=' + encodeURIComponent(filter_fecha);
            }

            location = url;
        });
        //--></script> 
    <script type="text/javascript"><!--
        $('input[name=\'filter\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=mailing/lista_mails_predefinidos/autocomplete&token=<?php echo $token; ?>&filter=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {                        
                        response($.map(json, function(item) {
                            return {
                               label: item['titulo'],                                
                                value: item['id_mensaje'] 
                            }

                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter\']').val(item['label']);
            }
        });
        //-->
    </script>
</div>
<?php echo $footer; ?>  