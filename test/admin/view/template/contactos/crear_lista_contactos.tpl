<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" id="save_btn" form="form-download" onclick="revizar()" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <script type="text/javascript">
                    document.getElementById("save_btn").onclick = function () {
                        waitingDialog.show('Cargando archivo espere un momento.');
                    };
                </script>
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
        <div class="alert alert-success">
            <i class="fa fa-check-circle">

            </i> <?php echo $success; ?>
            <!--    ///Agregar un href  -->
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-download" class="form-horizontal">
                    <div class="form-group required">
                        <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                        <div class="col-sm-10">
                             <input type="text" name="nombre_lista"  placeholder="<?php echo $entry_name; ?>" value="<?php echo $nombre_lista;?>" class="form-control" /><br>
                             <input type="checkbox" name="ck_encabezado" id="ck_encabezado" value="true" checked><span class="little-margin"><label>Archivo con encabezados</label></span>
                        </div> 
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-filename"><span data-toggle="tooltip" title="<?php echo $help_filename; ?>"><?php echo $entry_filename; ?></span></label>
                        <div class="col-sm-10">
                                <input name="uploadFile" id="uploadFile" placeholder="Seleccionar lista" readonly="true" class="form-control" />
                                <?php if ($error_file) { ?>
                                    <div class="text-danger"><?php echo $error_file; ?></div>
                                <?php } ?>
                                <span class="input-group-btn">
                                        <div class="fileUpload btn btn-primary pull-right">
                                            <span> <?php echo $text_btn_seleccionar; ?> </span>
                                            <input id="uploadBtn" type="file" class="upload" name="file" accept=".csv, .xlsx, .xls"/>
                                        </div>
                                    </span>
                            <script type="text/javascript">
                                document.getElementById("uploadBtn").onchange = function () {
                                    document.getElementById("uploadFile").value = this.value;
                                };
                            </script>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script type="text/javascript">
    function revizar () {
        if( $('#ck_encabezado').attr('checked') ) {
            $('#ck_encabezado').val('true');
        }else{
            $('#ck_encabezado').val('true');
        }
    }
</script>

</div>
<?php echo $footer; ?>