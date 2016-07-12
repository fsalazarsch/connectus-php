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
                                      <label>Campos</label>
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

                                    <textarea maxlength="160" name="cuerpo" id="input-message"  placeholder="<?php echo $entry_editor ?>" style="margin: 0px; width: 774px; height: 172px;"><?php echo $mensaje; ?></textarea>
                                    <?php if ($error_mensaje_a_enviar) { ?>
                                            <div class="text-danger"><?php echo $error_mensaje_a_enviar; ?></div>
                                        <?php } ?> 

                                    <script type="text/javascript">
										
										$("#input-message").focusout(function(){
										var str = $("#input-message").val();
										var message = str;
										
										message = message.replace(/á|à|ä|â|ª/gi, 'a'); 
										message = message.replace(/é|è|ë|ê/gi ,'e'); 
										message = message.replace(/í|ì|ï|î/gi, 'i'); 
										message = message.replace(/ó|ò|ö|ô/gi, 'o'); 
										message = message.replace(/ú|ù|ü|û/gi, 'u'); 
										message = message.replace(/ñ|Ñ/g, 'n');      
										
										message = message.replace("¡", "" );  
										message = message.replace("¿", "" );     
										message = message.replace("&", "y" );   

										$("#input-message").val(message);
										$("#input-message").html(message);
										});
										
                                        document.getElementById("input-message").onkeyup = function () {
                                        document.getElementById("contador").innerHTML = (document.getElementById("input-message").value.length + "/" + "160");
                                        
                                        if(this.value.length >= 160){
                                            var d = document.getElementById("contador");
                                            d.className = "text-danger";
                                        }

                                        if(this.value.length < 160){
                                            d.className = '';
                                        }

                                    };
                                    </script>                                
                                    
                                </div>                                                                          

                                <div class="col-sm-2">
                                    <br>
                                    <div style="margin: auto; padding: 10px;"class="col-sm-20">
                                        <label id="contador">0/160</label>
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

    function lang1(event) {
       var target = event.target || event.srcElement;   
       document.getElementById("input-message").value += " %" + event.target.innerHTML + "%"; 
       document.getElementById("contador").innerHTML = (document.getElementById("input-message").value.length + "/" + "160");                                                       
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
