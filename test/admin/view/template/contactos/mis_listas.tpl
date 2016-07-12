<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
        <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>        
        <a data-toggle="tooltip" title="<?php echo $button_delete; ?>" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-listas').submit() : false;" class="btn btn-danger"><i class="fa fa-trash-o" ></i></a> 
        <a href="<?php echo $full_excel; ?>" data-toggle="tooltip" title="<?php echo $button_export_excel_all; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
        <!--<a href="<?php //echo $full_pdf; ?>" id="pdf" data-toggle="tooltip" title="<?php //echo $button_export_pdf_all; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>-->
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
                            <div class="form-inline">
                                <label class="control-label" for="input-name"><?php echo $entry_nombre; ?></label>
                                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_nombre; ?>" id="input-name" class="input-text" />
                                <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $delete;?>" method="post" enctype="multipart/form-data" id="form-listas">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-max-with">
                            <thead>
                            <tr>
                            <td style="width: 1px;" class="text-center"><input type="checkbox" id="select_all" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                                <td class="text-left"><?php if ($sort == 'L.nombre') { ?>
                                    <a href="<?php echo $sort_nombre; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_nombre; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_nombre; ?>"><?php echo $column_nombre; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'p.fecha_creacion') { ?>
                                    <a href="<?php echo $sort_fecha_creacion; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_fecha_creacion; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_fecha_creacion; ?>"><?php echo $column_fecha_creacion; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $column_contactos; ?></td>
                                <td class="text-left"><?php echo $column_action; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($lista_contactos) { ?>
                            <?php foreach ($lista_contactos as $listas) { ?>
                            <tr>
                                <td class="text-center"><?php if (in_array($listas['id_lista'], $selected)) { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $listas['id_lista']; ?>" checked="checked" />
                                <?php } else { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $listas['id_lista']; ?>" />
                                <?php } ?></td> 
                                <td class="text-left"><?php echo utf8_encode($listas['nombre']); ?></td>

                                <td class="text-left"><?php echo $listas['fecha_creacion']; ?></td>
                                <td class="text-left"><span class="label label-success"><?php echo $listas['quantity']; ?></span>
                                    
                                <td class="text-left">
                                    <a href="<?php echo $listas['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                    <a href="<?php echo $listas['view']; ?>" data-toggle="tooltip" title="<?php echo $button_eye; ?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                    <a href="<?php echo $listas['downloadExcel']; ?>" data-toggle="tooltip" title="<?php echo $button_export_excel; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
                                    <!--<a href="<?php //echo $listas['downloadPDF']; ?>" id="pdf" data-toggle="tooltip" title="<?php //echo $button_export_pdf; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>-->
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
                </form>
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"><!--
        $('#button-filter').on('click', function() {
            var url = 'index.php?route=contactos/mis_listas&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

            location = url;
        });
        //--></script> 
    <script type="text/javascript"><!--
        $('input[name=\'filter_name\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=contactos/mis_listas/autocomplete&token=<?php echo $token; ?>&filter_name=' +  $('input[name=\'filter_name\']').val(),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item['nombre'],
                                value: item['id_lista']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'filter_name\']').val(item['label']);
            }
        });
        //-->
    </script>
</div>
<?php echo $footer; ?>  