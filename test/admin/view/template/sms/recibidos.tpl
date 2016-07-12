<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
        <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>        
        <a href="<?php echo $full_excel; ?>" data-toggle="tooltip" title="<?php echo $button_export_excel; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
        <!--<a href="<?php //echo $full_pdf; ?>" id="pdf" data-toggle="tooltip" title="<?php //echo $button_export_pdf; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>-->
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
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label class="control-label" for="input-nombre"> Remitente </label>
                                <input type="text" name="filter_name" value=""  id="input-nombre" class="form-control" />
                              </div>                          
                            </div> 

                            <div class="col-sm-3">                          
                              <div class="form-group">
                                <label class="control-label" for="input-fecha"> Desde </label>
                                <input type="date" name="filter_fecha" value="<?php echo $filter_fecha; ?>"  id="input-fecha" class="form-control" />
                              </div>
                            </div> 

                            <div class="col-sm-3">                          
                              <div class="form-group">
                                <label class="control-label" for="input-fecha-hasta"> Hasta </label>
                                <input type="date" name="filter_fecha_hasta" value="<?php echo $filter_fecha_hasta; ?>"  id="input-fecha-hasta" class="form-control" />
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

                <form action="<?php echo $delete;?>" method="post" enctype="multipart/form-data" id="form-listas">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                           
                                <td class="text-left"><?php if ($sort == 'fecha') { ?>
                                    <a href="<?php echo $sort_fecha; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_fecha; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_fecha; ?>"><?php echo $column_fecha; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php if ($sort == 'remitente') { ?>
                                    <a href="<?php echo $sort_remitente; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_remitente; ?></a>
                                    <?php } else { ?>
                                    <a href="<?php echo $sort_remitente; ?>"><?php echo $column_remitente; ?></a>
                                    <?php } ?></td>
                                <td class="text-left"><?php echo $column_mensaje; ?></td>
                                <td class="text-left"><?php echo $column_destino; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($lista_recibidos) { ?>
                            <?php foreach ($lista_recibidos as $listas) { ?>
                            <tr>
                                
                                <td class="text-left"><?php echo $listas['fecha']; ?></td>

                                <td class="text-left"><?php echo $listas['remitente']; ?></td>
                                <td class="text-left"><?php echo $listas['mensaje']; ?></td>
                                <td class="text-left"><?php echo $listas['destino']; ?></td>
                                    
                               
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
            var url = 'index.php?route=sms/recibidos&token=<?php echo $token; ?>';

            var filter_name = $('input[name=\'filter_name\']').val();

            if (filter_name) {
                url += '&filter_name=' + encodeURIComponent(filter_name);
            }

             var filter_fecha = $('input[name=\'filter_fecha\']').val();

            if (filter_fecha) {
                url += '&filter_fecha=' + encodeURIComponent(filter_fecha);
            }

            var filter_fecha_hasta = $('input[name=\'filter_fecha_hasta\']').val();

            if (filter_fecha_hasta) {
                url += '&filter_fecha_hasta=' + encodeURIComponent(filter_fecha_hasta); 
            }

            location = url;
        });
        //--></script> 

</div>
<?php echo $footer; ?>  