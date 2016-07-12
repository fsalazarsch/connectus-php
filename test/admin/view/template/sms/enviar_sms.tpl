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
    
    <div class="form-center">
    
        <div class="paso-title pasos">
            <div class="alert alert-info info-paso" role="alert">
                 <strong>&nbsp; &nbsp;Paso 1: </strong>¿A quién(es)?
            </div>
        </div>

        
            
        <div>
            <div class="divcenter">

                <div class="row">
                    <label class="radio-inline">
                        <input type="radio" name="options" id="option1" value="unico"  autocomplete="off" checked> <?php echo $texto_unico;?>
                    </label>

                    <label class="radio-inline">
                        <input type="radio" name="options" id="option2" value="masivo" autocomplete="off"> <?php echo $texto_masivo;?>
                    </label>
                </div>

            

                <div class="row margin1em" id='select_number'>
                    <div class='col-md-4 col-md-offset-2'>

                        <div class="col-xs-1 icono">
                            <i class="fa fa-globe fa-lg"></i>  
                        </div>
                        
                        <div class='col-xs-11 col-md-10'>
                            <div class="form-group">
                                <select class="form-control" id="lista_pais" name="lista_pais">
                                    <option value="0" disabled >Código</option>
                                    <?php
                                        foreach ($lista_codigos as $key ) { 

                                            if($key['pais'] == 'Chile'){
                                                $selected = 'selected';
                                            }else{
                                                $selected = '';
                                            }
                                            ?>
                                            <option value="<?php echo $key['codigo'];?>" <?php echo $selected;?> ><?php echo $key['pais']. " [+".$key['codigo']."]";?> </option>
                                    <?php } ?>
                                </select>                         
                            </div>
                        </div>
                    </div>
                        
                    <div class='col-md-4'>
                        <div class="col-xs-1 icono2">
                            <i class="fa fa-mobile-phone fa-2x"></i>
                        </div>
                        
                        <div class="col-xs-11 col-md-10">                                        
                            <div class="form-group">
                                <input type="number"    class="form-control" id="input_numero"   name="numero" placeholder="Ingresa el numero de destino" value="<?php echo $numero ?>"   />                            
                            </div>
                        </div>
                    </div>
                </div>
                        

                <div class='row' id='txt_number'>
                    <p class="input-number" id='num_final'></p>
                    <input type='hidden' id='numero_final' name='numero_final' value=''>
                </div>



                <div class='row margin1em' hidden id='hiddrop'>

                    <div class="col-md-8 col-md-offset-2">

                        <div id="dropdown">


                            <div class="col-xs-1 icono">
                                <i class="fa fa-users fa-lg"></i>
                            </div>

                                
                            <div class="col-xs-11 col-md-10">  
                                <select class="form-control" id="sel1" name="entry_lista">
                                    <option value="sel_null" disabled selected>Selecciona una lista de contactos</option>

                                    <?php foreach ($lista_contactos as $key ) { ?>
                                        <option value="<?php echo $key['id_lista'];?>"> <?php echo $key['nombre'];?> </option>
                                    
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="row div-alerta" hidden>
                    <div class="alert alert-danger alert-dismissible col-md-8 col-md-offset-2" role="danger" >
                        <strong>Error: </strong> <span id="danger_numero"></span>
                    </div>
                </div>

                <div class="row div-alerta" hidden>
                    <div class="alert alert-danger alert-dismissible col-md-8 col-md-offset-2" role="danger" >
                        <strong>Error: </strong> <span id="danger_lista"></span>
                    </div>
                </div>


                

                <script type="text/javascript">
                    document.getElementById("option1").onchange = function () {

                        $("#hiddrop").hide();

                        $("#txt_number").show();
                        $("#select_number").show();
                    };

                    document.getElementById("option2").onchange = function () {
                        $("#hiddrop").show();
                        
                        $("#txt_number").hide();
                        $("#select_number").hide();
                    };</script>
            </div>
        </div>            


        <div class="paso-title pasos">
            <div class="alert alert-info info-paso" role="alert">
                 <strong>&nbsp; &nbsp;Paso 2: </strong>¿Qué mensaje?
            </div>
        </div>

        <div class="divcenter">

            <div class="row">
                <label class="radio-inline">
                    <input type="radio" name="check_tipo_mensaje" id="check_sms_nuevo" autocomplete="off" checked> <?php echo $texto_nuevo;?>
                </label>

                <label class="radio-inline">
                    <input type="radio" name="check_tipo_mensaje" id="check_sms_predefinido" autocomplete="off"> <?php echo $texto_predefinido;?>
                </label>
            </div>

            <div class="row margin1em" id='sms_predefinido'>
            
                <div class='col-xs-12 col-md-8 col-md-offset-2'>

                    <div class="col-xs-11 col-md-10 col-xs-offset-1">
                        <select class="form-control" id="select_predefinidos">
                            <option value="" disabled selected>Selecciona un mensaje predefinido</option>
                            <?php foreach ($lista_preferidos as $key ) { ?>
                            <option value="<?php echo $key['cuerpo'];?>"> <?php echo $key['titulo'];?> </option>
                            <?php } ?>
                        </select>
                    </div>

                </div>
                    
            </div>

                


        

            <div class="display-margin row">

                <div style="margin: auto; padding: 10px;" class="col-sm-2" >
                    <br>
                    <div id="campos" class="div_campos">
                      <label style="font: 15px;"> Campos </label>
                      <ul id="lista" class="ul" onclick="lang1(event);">

                      </ul>
                    </div>                                
                </div>

                <div class="col-sm-8">
                    
                    <div class="form-group">
                        <label style="float:left">Titulo envío</label>                                
                        <input type="text" name="titulo" id="input_titulo" class="form-control" placeholder="<?php echo $placeholder_titulo_envio ?>"/>

                        <div class="row margin1em"  hidden>
                            <div class="alert alert-danger alert-dismissible col-md-12 " role="danger" >
                                <strong>Error: </strong> <span id="danger_titulo"></span>
                            </div>
                        </div> 


                    </div>

                    <div class="form-group">
                        
                        <textarea name="mensaje_a_enviar" id="mensaje-area" placeholder="<?php echo $placeholder_mensaje_a_enviar ?>" class="input-mensaje"><?php echo $mensaje_a_enviar ?></textarea>
                        

                        <div class="row margin1em" hidden>
                            <div class="alert alert-danger alert-dismissible col-md-12" role="danger" >
                                <strong>Error: </strong> <span id="danger_mensaje"></span>
                            </div>
                        </div>

                        <div class="alert alert-warning alert-dismissible" role="alert" id='alert_msg'>
                            Se enviará más de un mensaje.
                        </div>
                    </div>
                         
                    <div class="form-group">

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="check_guardar_predefinido" id="checkbox_sms_predefinido" value="guardar_preferido" autocomplete="off"> <?php echo $texto_guardar_predefinido;?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="nombre_predefinido" hidden>
                        <label for='nombre_sms_predefinido'></label>
                        <input class="form-control" type="text" name="nombre_mensaje_predefinido" value="<?php echo $nombre_mensaje_predefinido ?>" id="nombre_sms_predefinido" placeholder="<?php echo $placeholder_nuevo_predefinido;?>" readonly>
                    </div>

                        
                    <div class="form-group"  hidden>
                        <div class="alert alert-danger alert-dismissible" role="danger" >
                            <strong>Error: </strong> <span id="danger_nombre_predef"></span>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-2">
                    <br>
                    <div style="margin: auto; padding: 10px;" class="form-inline control-label">
                   
                        <label style="width:30px;" class="control-label" for="input-filename" ><div id="contador" style="display: inline;">0/160</div><span data-toggle='tooltip' data-original-title='Sobre 160 caracteres se enviará más de un SMS por contacto'></span></label>

                    </div>
                </div>

            </div>  

            
            <script type="text/javascript">

                function mostrar_alert_msg(){
                    $("#alert_msg").show();
                }

                function ocultar_alert_msg(){
                    $("#alert_msg").hide();
                }

                document.getElementById('checkbox_sms_predefinido').onclick = function() {
                    // access properties using this keyword
                    if ( this.checked ) {                                    
                        $('#nombre_predefinido').show();                                        
                    } else {

                        $("#danger_nombre_predef").parent().hide();
                        $('#nombre_predefinido').hide();    

                    }
                };

                document.getElementById("select_predefinidos").onchange = function () {
                   var x = document.getElementById("select_predefinidos").selectedIndex;
                   var y = document.getElementById("select_predefinidos").options[x].value;
                   var p = document.getElementById("select_predefinidos").options[x].text;
                   document.getElementById("mensaje-area").value = y;
                   document.getElementById("input_titulo").value = p;

                   document.getElementById("contador").innerHTML = document.getElementById("mensaje-area").value.length + "/" + "160";

                   if(document.getElementById("mensaje-area").value.length >= 160){
                        var d = document.getElementById("contador");
                        d.className = "text-danger";
                   }
                };

                

                function lang1(event) {
                   var target = event.target || event.srcElement;   
                   document.getElementById("mensaje-area").value += " %" + event.target.innerHTML + "%";
                   document.getElementById("contador").innerHTML = (document.getElementById("mensaje-area").value.length + "/" + "160" );
                   if(document.getElementById("mensaje-area").value.length >= 160){
                        var d = document.getElementById("contador");
                        d.className = "text-danger";
                   }                                                                                                        
                }

                document.getElementById("mensaje-area").onkeyup = function () {
                    document.getElementById("contador").innerHTML = this.value.length + "/" + "160";
                    
                    if(this.value.length >= 160){
                        
                        var d = document.getElementById("contador");
                        d.className = "text-danger";

                        mostrar_alert_msg();
                    }

                    if(this.value.length <= 160){
                        var d = document.getElementById("contador");
                        d.className = '';   

                        ocultar_alert_msg();                        
                        
                    }

                };    
            </script>
        </div>


        <div class="paso-title pasos">
            <div class="alert alert-info info-paso" role="alert">
                 <strong>&nbsp; &nbsp;Paso 3: </strong>¿Con qué opciones?
            </div>
        </div>
               

            <div class="divcenter"> 
                    
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for='input_remitente' class='col-sm-2 col-md-offset-2 control-label'>Remitente</label>

                            <div class='col-sm-4'>
                                <input type="text" name="remitente" id="input_remitente" value="<?php echo $remitente?>" class="form-control" />
                            </div>                           
                        </div> 
                    </div>
                    

                    <div class="row"  hidden>
                        <div class="alert alert-danger alert-dismissible col-md-8 col-md-offset-2" role="danger" >
                            <strong>Error: </strong> <span id="danger_remitente"></span>
                        </div>
                    </div>

                    <div>

                        <div class="form-group">
                            <label>¿Cuándo enviar?</label>
                        </div>

                        <div class="row form-group">
                            <label class="radio-inline">
                                <input type="radio" name="tipo_envio" id="check_envio_normal" autocomplete="off" value="ahora" checked> Ahora
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="tipo_envio" id="check_envio_programado" autocomplete="off" value="programado"> Programado
                            </label>                                
                        </div>

                        <div class="form-group">
                            <div id="btn-enviar">
                                <span><button onclick="onclickEnviarSms()" style="width:100px" id="enviar" name="enviar" id="enviar" class="btn btn-primary"/> Enviar </span>
                            </div>            
                        </div>

                    </div>
                           

                <div class='form-horizontal' hidden id="programado">                            
                    
                    <div class="form-group">
                        <label for='input_fecha' class='col-sm-2 col-md-offset-2 control-label'>Fecha</label>
                        <div class='col-sm-4'>
                            <input type="date" name="fecha_envio" value="<?php echo $fecha_envio ?>" id="input_fecha" class="form-control" />
                        </div>                             
                    </div>

                    <div class="form-group">
                        <label for='input_hora' class='col-sm-2 col-md-offset-2 control-label'>Hora</label>
                        <div class='col-sm-4'>
                            <input type="time" name="hora_envio"  id="input_hora" value="<?php echo $hora_envio ?>"  class="form-control" />   
                        </div>
                    </div>
                </div>

                <div class="form-group">

                    <div hidden id="btn-programar">
                        <span><button onclick="onclickEnviarSms()" style="width:100px" id="programar" name="programar" id="programar" class="btn btn-primary"/> Programar </span>
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
                    };</script>
            </div> 

    </div>

    <script type="text/javascript">
        $(document).ready(function(){

            var caracteres = 8;

            format_number();




            // Limitar cantidad de caracteres !!
            $("#input_numero").keyup(function(){

                if($(this).val().length > caracteres){
                    var numero = $(this).val($(this).val().substr(0, caracteres));
                }

                format_number();
            });

            $(document).on('change', "#input_numero", function(){

                if($(this).val().length > caracteres){
                    var numero = $(this).val($(this).val().substr(0, caracteres));
                }

                format_number();
            });

            $(document).on('change', "#lista_pais", function(){
                format_number();
            });






            $('#campos').hide();
            //$('#campos_predefinidos').hide();
            $('#sms_predefinido').hide();

            $('#check_sms_nuevo').on('click',function(){                
                $('#sms_predefinido').hide();
                
            });

            $('#check_sms_predefinido').on('click',function(){                
                $('#sms_predefinido').show();
            });

            $('#option1').on('click',function(){
                $('#campos').hide();
                //$('#campos_predefinidos').hide();

            });

            $('#option2').on('click',function(){
                $('#campos').show();
                //$('#campos_predefinidos').show();
            });


            $("#alert_msg").hide();



        });


        
        function format_number()
        {

            var cod = $("#lista_pais").val();
            var num = $("#input_numero").val();

            var num_final = "+ "+cod+" "+num;
            var numero_final = cod+""+num;

            $("#num_final").text(num_final);
            $("#numero_final").val(numero_final);

        }
        
        $(document).on('keyup', '#input_titulo', function(){

            var txt = $(this).val();

            $("#nombre_sms_predefinido").val(txt);

        });
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
         
    </script> 


    <script type="text/javascript">

        $("#error_numero").hide();   

        function onclickEnviarSms() {

            $('#enviar').attr('disabled', true);                                                                                        
            $('#programar').attr('disabled', true); 


            var options = $('input[name=options]:checked').val();                      
            
            var x = document.getElementById("sel1").selectedIndex;
            var entry_lista = document.getElementById("sel1").options[x].value;

            var numero = document.getElementById("numero_final").value;

            var mensaje_a_enviar = document.getElementById("mensaje-area").value;
            var titulo_envio = document.getElementById("input_titulo").value;
             
            var check_predefinido = $("#checkbox_sms_predefinido").is(':checked');
            var nombre_mensaje_predefinido = document.getElementById("nombre_sms_predefinido").value;
            
            var remitente = document.getElementById("input_remitente").value;

            var tipo_envio = $('input[name=tipo_envio]:checked').val();    
            var fecha_envio = document.getElementById("input_fecha").value;
            var hora_envio = document.getElementById("input_hora").value; 

            var errores = 0;

            if(options == "unico"){

                if(numero == ""){

                    errores++;
                    document.getElementById('danger_numero').innerHTML = "";

                    $('#danger_numero').append("Compruebe el destinatario");
                    $("#danger_numero").parent().parent().show();
                
                }else{
                    $("#danger_numero").parent().parent().hide();
                }        
            }

            if(options == "masivo"){

                if(entry_lista == "sel_null"){
                    errores++;
                    document.getElementById('danger_lista').innerHTML = "";

                    $('#danger_lista').append("Seleccione una lista");

                    $('#danger_lista').parent().parent().show();
                }else{
                    $('#danger_lista').parent().parent().hide();
                }     

            }
            
            if(mensaje_a_enviar == ""){
                errores++;
                document.getElementById('danger_mensaje').innerHTML = "";

                $('#danger_mensaje').append("Ingrese un mensaje");
                $("#danger_mensaje").parent().parent().show();
            } else {
                $("#danger_mensaje").parent().parent().hide();
            }

            if(titulo_envio == ""){
                errores++;
                document.getElementById('danger_titulo').innerHTML = "";

                $('#danger_titulo').append("Ingrese un titulo de envio");
                $("#danger_titulo").parent().parent().show();
            } else {
                $("#danger_titulo").parent().parent().hide();
            }



            if (check_predefinido) {

                if(nombre_mensaje_predefinido == ""){
                    errores++;
                    document.getElementById('danger_nombre_predef').innerHTML = "";
                    $('#danger_nombre_predef').append("Ingrese el nombre del mensaje");
                    $("#danger_nombre_predef").parent().parent().show();
                } else {
                    $("#danger_nombre_predef").parent().parent().hide();
                }            
            }             

            if(remitente == ""){                
                errores++;
                document.getElementById('danger_remitente').innerHTML = "";
                $('#danger_remitente').append("Ingrese el remitente");
                $("#danger_remitente").parent().parent().show();
            } else {
                $("#danger_remitente").parent().parent().hide();
            }        

            

            if(errores == 0){
                $.ajax(
                {                   
                    url: "index.php?route=sms/enviar_sms/send&token=<?php echo $token; ?>",
                    type: "POST",               
                    dataType: 'json',
                    data: { options: options,
                            entry_lista: entry_lista,
                            numero: numero,
                            mensaje_a_enviar: mensaje_a_enviar,
                            titulo : titulo_envio,
                            check_predefinido: check_predefinido,
                            nombre_mensaje_predefinido: nombre_mensaje_predefinido,
                            remitente: remitente,
                            tipo_envio: tipo_envio,
                            fecha_envio: fecha_envio,
                            hora_envio: hora_envio},               
                    success: function (json) {                                  
                        alert(json);
                    }   

                });
                
                setTimeout(function(){  
                    window.location.href = "index.php?route=sms/enviar_sms&token=<?php echo $token; ?>";                
                }, 1000); 
            
            }else{
                $('#enviar').attr('disabled', false);                                                                                        
                $('#programar').attr('disabled', false);
                window.scrollTo(0, 0);
            }

            
 
            
           
        }

         
    </script>   

</div>
<?php echo $footer; ?>