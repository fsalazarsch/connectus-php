<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

	<div class="page-header">
		<div class="container-fluid">      			
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
		<?php if ($error_cred) { ?>

		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_cred; ?>
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
	</div>


	<form action="<?php echo $action; ?>" method="post" id="form-enviar" enctype="multipart/form-data" class="form-center">
			
            <div class="paso-title pasos">
                <div class="alert alert-info info-paso" role="alert">
                     <strong>&nbsp; &nbsp;Paso 1: </strong>¿A quién(es)?
                </div>
            </div>

			<div class="divcenter">
				
                <div class="row">
                    <label class="radio-inline">
                        <input type="radio" name="options" id="option1" value="unico"  autocomplete="off" checked> <?php echo $texto_unico;?>
                    </label>

                    <label class="radio-inline">
                        <input type="radio" name="options" id="option2" value="masivo" autocomplete="off"> <?php echo $texto_masivo;?>
                    </label>
                </div>
				
				<div id="input-email" class="row margin1em">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="col-xs-1 col-md-offset-2 icono">
                            <i class="fa fa-envelope-o fa-lg"></i>
                        </div>

    					<div class="col-xs-11 col-md-6 ">
        					<input type="mail" name="input_email" placeholder="<?php echo $entry_email;?>" value="<?php echo $email; ?>"  class="form-control" />
                        </div>
                    </div>
                </div>

                

				<div hidden id="dropdown" class="row margin1em">

					<div class="col-md-8 col-md-offset-2">

                        <div class="col-xs-1 icono">
                            <i class="fa fa-users fa-lg"></i>
                        </div>

						<div class="col-xs-11 col-md-10 ">    
    						<select   class="form-control" id="sel1" name="entry_lista_contactos" >
    							<option value="seleccione" selected>Selecciona una lista de contactos</option>
    							<?php foreach ($lista_contactos as $key ) { ?>
    								<option value="<?php echo $key['id_lista'];?>"><?php echo $key['nombre'];?> </option>
    							<?php } ?>
    						</select>
						</div>
					</div>                           
				</div>



                <?php if ($error_email_destinatario) { ?>
                    <div class="row div-alerta">
                        <div class="alert alert-danger alert-dismissible col-md-8 col-md-offset-2" role="danger">
                            <strong>Error:</strong> <?php echo $error_email_destinatario; ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($error_lista_destinatarios) { ?>
                    <div class="row div-alerta">
                        <div class="alert alert-danger alert-dismissible col-md-8 col-md-offset-2" role="danger">
                            <strong>Error:</strong> <?php echo $error_lista_destinatarios; ?>
                        </div>
                    </div>
                <?php } ?>
				
			</div>        

            <div class="paso-title pasos">
                <div class="alert alert-info info-paso" role="alert">
                    <strong>&nbsp; &nbsp;Paso 2: </strong>¿Qué mensaje?
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-8 col-md-offset-2 divcenter">
                    <label class="radio-inline">
                        <input type="radio" name="check_tipo_mensaje" value="nuevo" id="check_sms_nuevo"  checked> <?php echo $texto_nuevo;?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="check_tipo_mensaje" value="predefinido" id="check_sms_predefinido" > <?php echo $texto_predefinido;?>
                    </label>
                </div>
            </div>
            

            <div class="row margin1em">
                <div class='col-xs-12 col-md-8 col-md-offset-2' id="sms_predefinido">

                    <div class="col-xs-11 col-md-10 col-xs-offset-1">

                        <select  class="form-control" id="select_predefinidos">
                            <option value="seleccione" selected>Selecciona el mensaje predefinido</option>
                            <?php foreach ($lista_preferidos as $key ) { ?>
                            <option value='<?php echo $key["cuerpo"];?>'> <?php echo $key["titulo"];?> </option>
                            <?php } ?>
                        </select>
                    </div>

                    <br><br><br>
                </div>

            </div>


            
            <div class="row">
                <div class="form-group col-md-8 col-md-offset-2" >
                    <label for='input-nombre_envio'>Nombre del envío</label>                                
                    <input type="text" name="nombre_envio" id="input-nombre_envio" value="<?php echo $nombre_envio ?>" class="form-control"/>
                    <span id="helpBlock" class="help-block">Este nombre será considerado para su identificación en reportes y estadisticas.</span>
                </div>
            </div>
            

            <?php if ($error_nombre_envio) { ?>
                <div class="row margin1em">
                    <div class="alert alert-danger alert-dismissible col-md-8 col-md-offset-2" role="danger">
                        <strong>Error:</strong> <?php echo $error_nombre_envio; ?>
                    </div>
                </div>
            <?php } ?>

            <div class="row">

				<div class="col-sm-2" >
					<div id="campos" class="div_campos">
                        <label>Campos</label>
                        <ul id="lista" class="ul" onclick="lang1(event);"></ul>
					</div>
				</div>
				
				<div class="col-sm-8" style="text-align:left">

					<div class="form-group">
                        <label>Asunto</label>                                
                        <input type="text" name="asunto" id="input-asunto" value="<?php echo $asunto ?>" class="form-control"/>
                        
                        <?php if ($error_asunto) { ?>

                            <div class="row margin1em">
                                <div class="alert alert-danger alert-dismissible" role="danger">
                                    <strong>Error:</strong> <?php echo $error_asunto; ?>
                                </div>
                            </div>

						<?php } ?>
                    </div>

					<div class="form-group">
                        <label for='input-message'>Mensaje</label>
                        <textarea name="mensaje_a_enviar" id="input-message"  placeholder="<?php echo $entry_editor ?>"><?php echo $mensaje; ?></textarea>
                    </div>
					

					
						
                    <div class="form-inline divcenter">

                        <div class="form-group">
                            <label for='uploadFile'>Adjuntar archivo</label>
                            <input name="uploadFile" id="uploadFile" placeholder="Seleccionar archivos" value="<?php echo $archivos; ?>" readonly="true" class="form-control" />
                        </div>
                            
                        <div class="form-group">

                            <div class="fileUpload">
                                <div class='btn btn-primary'>
                                    <input  type="file" class="upload " name="file[]" id="filesToUpload" multiple/>Seleccionar
                                </div>
                            </div>

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
									$("#input-message").code(textarea + '<p><a href="http://connectus.cl/system/download/mail/'+ file.name +'" target="_blank">' + file.name +'</a></p>');
								}
									
                                document.getElementById("uploadFile").value = this.files.length +  text_archivos;
                            };
                        </script>
                    </div>
				</div>												
		
			</div> 

					<script type="text/javascript">

						



                            function lang1(event) {
							    var target = event.target || event.srcElement;

                				//$(".note-editable").append(" %" + event.target.innerHTML + "%" );

                				//FSB

                				var txt = $('#input-message').code();
                				var minusC = 4;
                				var initialCode = '<p>';
                				var finalCode = '</p>';

                				var ultimoTexto = txt.substring(txt.length-8, txt.length).trim();

                				if (ultimoTexto == '<br></p>') {
                					minusC = 8;
                					initialCode = '';
                				} else if (ultimoTexto == '<p></p>' || ultimoTexto == '<p> </p>' || ultimoTexto == ' <p></p>'){
                					minusC = 8;
                				} else {
                					initialCode = '';
                				}

                					

                				txt = txt.substring(0, txt.length - minusC);
								
								$('#input-message').code(txt + initialCode + " %" + event.target.innerHTML + "% " + finalCode);

                				//$(".note-editable").append(" %" + event.target.innerHTML + "%" );
                				

							}

                    </script>
            

            <div class="row margin1em divcenter">
                <div class='form-group col-md-8 col-md-offset-2 '>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="check_guardar_predefinido" id="checkbox_sms_predefinido" value="guardar_preferido" autocomplete="off">
                            <?php echo $texto_guardar_predefinido;?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row" hidden id="nombre_predefinido" >

                <div class='col-md-8 col-md-offset-2'>

                    <input class="form-control" type="text" name="nombre_mensaje_predefinido" value="<?php echo $nombre_mensaje_predefinido ?>" id="nombre_sms_predefinido" placeholder="<?php echo $placeholder_nuevo_predefinido;?>" readonly>
  
                </div>

            </div>

            

            <?php if ($error_nombre_mensaje_predefinido) { ?>
                <div class="row margin1em">
                    <div class="alert alert-danger alert-dismissible col-md-6 col-md-offset-3" role="danger">
                        <strong>Error:</strong> <?php echo $error_nombre_mensaje_predefinido; ?>
                    </div>
                </div>
            <?php } ?>


            

            <div class="paso-title pasos">
                <div class="alert alert-info info-paso" role="alert">
                    <strong>&nbsp; &nbsp;Paso 3: </strong>¿Con qué opciones?
                </div>
            </div>


                    
                

                <div class="divcenter"> 
                    
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for='input_remitente' class='col-sm-2 col-md-offset-2 control-label'>Nombre remitente</label>

                            <div class='col-sm-4'>
                                <input type="text" name="nombre_remitente" id="input-lastname" value="<?php echo $nombre_remitente?>" class="form-control" />
                            </div>                           
                        </div> 
                    </div>
                    
                    <?php if ($error_nombre_remitente) { ?>
                        <div class="row">
                            <div class="alert alert-danger alert-dismissible col-md-8 col-md-offset-2" role="danger">
                                <strong>Error:</strong> <?php echo $error_nombre_remitente; ?>
                            </div>
                        </div>
                    <?php } ?>      


                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for='input_remitente' class='col-sm-2 col-md-offset-2 control-label'>Email remitente</label>

                            <div class='col-sm-4'>
                                <input type="text" name="email_remitente" id="input-lastname" value="<?php echo $email_remitente?>" class="form-control" />
                            </div>                           
                        </div> 
                    </div>

                    <?php if ($error_email_remitente) { ?>
                        <div class="row">
                            <div class="alert alert-danger alert-dismissible col-md-8 col-md-offset-2" role="danger">
                                <strong>Error:</strong> <?php echo $error_email_remitente; ?>
                            </div>
                        </div>
                    <?php } ?>




                    <div class="form-group">
                        <label>¿Cuándo enviar?</label>
                    </div>

                    <div class="row form-group">
                        <label class="radio-inline">
                            <input type="radio" name="tipo-envio" id="check_envio_normal" autocomplete="off" value="ahora" checked> Ahora
                        </label>

                        <label class="radio-inline">
                            <input type="radio" name="tipo-envio" id="check_envio_programado" autocomplete="off" value="programado"> Programado
                        </label>                                
                    </div>

                    <div class="form-group">
                        <div id="btn-enviar">
                            <span><button style="width:100px" type="submit" name="enviar" id="enviar" class="btn btn-primary"/> Enviar </span>
                        </div>            
                    </div>


                    <script type="text/javascript">
                        $('#form-enviar').on('submit',function(){										    
						    $('#enviar').attr('disabled', true);										    										    
						    $('#programar').attr('disabled', true);	
						});
                    </script>
                                                

                    <div class='form-horizontal' hidden id="programado">                            
                
                        <div class="form-group">
                            <label for='input_fecha' class='col-sm-2 col-md-offset-2 control-label'>Fecha</label>
                            <div class='col-sm-4'>
                                <input type="date" name="fecha_envio" value="<?php echo $fecha_envio ?>" id="input-lastname" class="form-control" />
                            </div>                             
                        </div>

                        <div class="form-group">
                            <label for='input_hora' class='col-sm-2 col-md-offset-2 control-label'>Hora</label>
                            <div class='col-sm-4'>
                                <input type="time" name="hora_envio"  id="input-lastname" value="<?php echo $hora_envio ?>"  class="form-control" />   
                            </div>
                        </div>

                        <?php if ($error_fecha_envio) { ?>
                            <div class="row margin1em">
                                <div class="alert alert-danger alert-dismissible col-md-6 col-md-offset-3" role="danger">
                                    <strong>Error:</strong> <?php echo $error_fecha_envio; ?>
                                </div>
                            </div>
                        <?php } ?>    

                        <?php if ($error_hora_envio) { ?>
                            <div class="row margin1em">
                                <div class="alert alert-danger alert-dismissible col-md-6 col-md-offset-3" role="danger">
                                    <strong>Error:</strong> <?php echo $error_hora_envio; ?>
                                </div>
                            </div>
                       <?php } ?> 
                    </div>

                            <div class="form-group">
                                <div hidden id="btn-programar">
                                    <span><button style="width:100px" type='submit' name="programar" id="programar" class="btn btn-primary"/> Programar </span>
                                </div>
                                                    
                            </div>                                              
                        </div>    


                           

                    	

                    	<script type="text/javascript">
                            document.getElementById("check_envio_normal").onchange = function () {
                                $('#programado').hide();
                                $('#btn-enviar').show();
                                $('#btn-programar').hide();
                                
                            };

                            document.getElementById("check_envio_programado").onchange = function () {
                                $('#programado').show();
                                $('#btn-enviar').hide();
                                $('#btn-programar').show();
                            };
                        </script>
    </form>      
</div>
<?php echo $footer; ?>  

<!--muestra el nombre del mensaje a guardar en caso de estar ckecked-->
    <script type="text/javascript">
    	<?php
            if($preferencia =='guardar_preferido')
            { ?>
                $("#nombre_predefinido").show();

        <?php } ?>
    </script>

	<script type="text/javascript">	 	

	 	document.getElementById("sel1").onchange = function () {
	 		var x = document.getElementById("sel1").selectedIndex;
            var y = document.getElementById("sel1").options[x].value;
            
            $.ajax(
			    {			    	
			    url: "index.php?route=mailing/enviar_mail/getCamposPorLista&token=<?php echo $token; ?>",
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
    	 

        $(document).ready(function( ){

        	$('#input-message').summernote({
        		height: 300,
        		onImageUpload: function(files, editor, welEditable){
        			for (var i = 0; i < files.length; i++) {
        				var tipo = files[i].type.split('/').shift();
                        if (tipo == 'image') {
        					sendFile(files[i], editor, welEditable);	
        				}else{
        					sendFile(files[i], editor, welEditable);	
        					var textarea = $("#input-message").code();
        					var url = '<?php echo DIR_DRAG_FILE;?>' + files[i].name.replace(/\ /g,'_');
    						$("#input-message").code(textarea + '<p><a href="'+ url +'" target="_blank">' + files[i].name.replace(/\ /g,'_') +'</a></p>');
        				}
        			};
    				
        		}
        	});

        	function sendFile(file, editor, welEditable) {
    	        var datos = new FormData();
    	        datos.append("file", file);
    	        $.ajax({
    	        	data: datos,
    	        	type: 'POST',
    	        	url: 'index.php?route=mailing/enviar_mail/upload_drag_image&token=<?php echo $token;?>',
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

    		var foo = [];
    		$('#select-campos multiple :selected').each(function(){
    			foo.push($(this).val());                      
    		});


			$('#campos').hide();
	        $('#sms_predefinido').hide();
	        $('#dropdown').hide();

	        $('#check_sms_nuevo').on('click',function(){                
	            $('#sms_predefinido').hide();
	            
	        });

	        $('#check_sms_predefinido').on('click',function(){                
	            $('#sms_predefinido').show();
	        });

			$('#option1').on('click',function(){
				$('#campos').hide();
	            $('#dropdown').hide();
				$('#input-email').show();

			});

			$('#option2').on('click',function(){
				$('#dropdown').show();
				$('#input-email').hide();
				$('#campos').show();
			});


            $(document).on('change', '#select_predefinidos', function(){
                
                var x = document.getElementById("select_predefinidos").selectedIndex;
                var y = document.getElementById("select_predefinidos").options[x].value; 
                var p = document.getElementById("select_predefinidos").options[x].text;

                if (y != "seleccione") {
                    var textarea = $("#input-message").code();                              
                    $("#input-message").code(y);
                    document.getElementById("input-asunto").value = p;
                    
                }

            });

            $(document).on('click', '#checkbox_sms_predefinido', function(){

                if( $(this).is(":checked")){                                    
                    $('#nombre_predefinido').show();                      
                } else {
                    $('#nombre_predefinido').hide();
                }

            });


            $(document).on('keyup', '#input-nombre_envio', function(){

                var txt = $(this).val();

                $("#nombre_sms_predefinido").val(txt);

            });

		});

    	//$('.note-editable').on('click',function(){
    	$('.note-editable').on('change','input-message',function(){
    		var selection = document.getSelection();
    		var cursorPos = selection.anchorOffset;
    		var oldContent = selection.anchorNode.nodeValue;
    		
    			/*
    			var toInsert = "TEST";
    			var newContent = oldContent.substring(0, cursorPos) + toInsert + oldContent.substring(cursorPos);
    			selection.anchorNode.nodeValue = newContent;
    			*/

    		document.cookie = "oldContent=" + oldContent;
    		document.cookie = "cursorPos=" + cursorPos;

    	});

    	function getCookie(c_name) {
            if (document.cookie.length > 0) {
                c_start = document.cookie.indexOf(c_name + "=");
                if (c_start != -1) {
                    c_start = c_start + c_name.length + 1;
                    c_end = document.cookie.indexOf(";", c_start);
                    if (c_end == -1) {
                        c_end = document.cookie.length;
                    }
                    return unescape(document.cookie.substring(c_start, c_end));
                }
            }
            return "";
    	}		
	</script>