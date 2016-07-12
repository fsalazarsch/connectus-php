<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
        <div class="pull-right">
        
        <button type="submit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>       
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> 
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
	            <form action="<?php echo $action; ?>" method="post"  enctype="multipart/form-data" id="form-enviar" >
	                <div>
	                    <div class="divcenter">           
	                        <div id="dropdown">
	                            <div class="btn-group">                                
	                                <select   class="input-text" id="sel1" name="entry_lista_contactos" >
	                                    <option value="seleccione" selected>Selecciona una lista de contactos</option>
	                                    <?php foreach ($lista_contactos as $key ) { ?>
	                                        <option value="<?php echo $key['id_lista'];?>"><?php echo $key['nombre'];?> </option>
	                                    <?php } ?>
	                                </select>                                
	                            </div>                           
	                        </div>
	                        

	                    <div class="display-margin row">

	                            <div class="col-sm-2" >
                                        <br>
                                    <div style="margin: auto; padding: 10px;"class="col-sm-20">
	                                  <label >Campos</label>
	                                  <ul id="lista" class="ul" onclick="lang1(event);">

	                                  </ul>
	                                </div>

	                                
	                            </div>
	                            
	                            <div class="col-sm-8" style="text-align:left">
	                                <div class="form-group">
	                                    <label>Titulo</label>                                
	                                    <input type="text" name="nombre_mensaje_predefinido" id="nombre_sms_predefinido" value="<?php echo $nombre_mensaje_predefinido ?>" placeholder="<?php echo $placeholder_nuevo_predefinido;?>" class="form-control"/>   
	                                    <?php if ($error_nombre_mensaje_predefinido) { ?>
	                                        <div class="text-danger"><?php echo $error_nombre_mensaje_predefinido; ?></div>
	                                    <?php } ?>                                 
	                                </div>                                

	                                <textarea name="cuerpo" id="input-message"  placeholder="<?php echo $entry_editor ?>"><?php echo $mensaje; ?></textarea>
	                                <?php if ($error_mensaje_a_enviar) { ?>
	                                        <div class="text-danger"><?php echo $error_mensaje_a_enviar; ?></div>
	                                    <?php } ?>                                 

	                                <div class="form-group">
	                                    
	                                    <label>Adjuntar archivo</label>
	                                    <input name="uploadFile" id="uploadFile" placeholder="Seleccionar archivos" readonly="true" class="input-text" />
	                                
	                                
	                                    <div style="top:0px" class="fileUpload btn btn-primary">
	                                        <input  type="file" class="upload" name="file[]" id="filesToUpload" multiple/>Seleccionar
	                                    </div>


	                                    <script type="text/javascript">
	                                        
	                                        document.getElementById("filesToUpload").onchange = function () {                                           
	                                                                                    
	                                            if (this.files.length > 1) {
	                                                var text_archivos = ' archivos seleccionados.';
	                                            }else{
	                                                var text_archivos = ' archivo seleccionado.';                                                                                               
	                                            }                                                                               

	                                            for (var i = 0; i < this.files.length; i++) {
	                                              var file = this.files[i];
	                                              var textarea = $("#input-message").code();
	                                                $("#input-message").code(textarea + '<p><a href="http://assertsoft.cl/developer/connectus/platx/system/download/mail/'+ file.name +'" target="_blank">' + file.name +'</a></p>');   
	                                            }
	                                                
	                                            document.getElementById("uploadFile").value = this.files.length +  text_archivos;
	                                        };
	                                    </script>
	                                </div>
	                            </div>                                                                          
	                        </div> 
	                    </div>                                
	                </div>
	            </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    document.getElementById('checkbox_sms_predefinido').onclick = function() {
            // access properties using this keyword
            if ( this.checked ) {                                    
                $('#nombre_predefinido').show();                      
            } else {
                $('#nombre_predefinido').hide();
            }
        };

    document.getElementById("select_predefinidos").onchange = function () {
        var x = document.getElementById("select_predefinidos").selectedIndex;
        var y = document.getElementById("select_predefinidos").options[x].value; 

        
        

        if (y != "seleccione") {
            var textarea = $("#input-message").code();                              
            $("#input-message").code(textarea + ' ' + y);
        }
    };                                                       

    function lang1(event) {
        var target = event.target || event.srcElement;                                          
        var textarea = $("#input-message").code();          
        
        $("#input-message").code(textarea + ' ' + "%" + event.target.innerHTML + "%");
    }
</script>

<script type="text/javascript">
    $('#input-message').summernote({
        height: 300,
        onImageUpload: function(files, editor, welEditable){
            for (var i = 0; i < files.length; i++) {
                var tipo = files[i].type.split('/').shift();
                if (tipo == 'image') {
                    sendFile(files[i], editor, welEditable);                    
                }else{
                    var textarea = $("#input-message").code();
                    var url = '<?php echo DIR_DRAG_FILE;?>' + files[i].name;
                    $("#input-message").code(textarea + '<p><a href="'+ url +'" target="_blank">' + files[i].name +'</a></p>');
                };
            };
        }
    });

    function sendFile(file, editor, welEditable) {
        var datos = new FormData();
        datos.append("file", file);
        $.ajax({
            data: datos,
            type: 'POST',
            url: 'index.php?route=mailing/crear_editar_predefinido/upload_drag_image&token=<?php echo $token;?>',
            cache: false,
            contentType: false, 
            processData: false,
            success: function(url){
                editor.insertImage(welEditable, url);
            },
            error: function(){
                alert('Algo salio mal; no se puede cargar el archivo.');
            }
        });
    }
</script> 

<script type="text/javascript">     

    document.getElementById("sel1").onchange = function () {
    var x = document.getElementById("sel1").selectedIndex;
    var y = document.getElementById("sel1").options[x].value;
    
    $.ajax(
        {                   
        url: "index.php?route=mailing/crear_editar_predefinido/getCamposPorLista&token=<?php echo $token; ?>",
        type: "POST",               
        dataType: 'json',
        data: { id_lista: y},               
        success: function (json) {              
            document.getElementById('lista').innerHTML = "";
            for (var i = 1; i < json[0] +1 ; i++){                              
                $("#lista").append('<li class="li" ><a>' + json[i] + '</a></li>');                                                  
            }

        }

    });                              
    
    };                         
</script> 
 
<?php echo $footer; ?>  