<!DOCTYPE html>
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<html lang="en-US">
    <head>
    
        <meta charset='utf-8'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Connectus | Expertos en Servicios de Comunicación</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <style type="text/css">
		IMG.displayed { display: block; margin-left: auto; margin-right: auto; }
		#Logo { width: 100%; text-align: center; overflow: hidden; padding: 20px; background-color: #1f90d4; }
		#FormText { text-align: center; margin-bottom: 40px; margin-top: 40px; }
		#Footer { width: 100%; font-family: 'Open Sans', sans-serif; font-size: 11px; color: #ffffff; text-align: center; text-transform: uppercase; margin-top: 83px; background-color: #1f90d4; padding-top: 40px; padding-bottom: 40px; }
		#FooterLogo { margin-bottom: 20px; }
		.FooterLink { color: #ffffff; }
		.FooterLink:hover { color: #ffffff; }
		.btn { background-color: #1f90d4; color: #ffffff; text-transform: uppercase; }
        </style>
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/font-face.css" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic,600' rel='stylesheet' type='text/css'></head>
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>





        <!-- Start Main -->
        <div id="Logo"><a href="/"><img src="images/logo.png" class="displayed" /></a></div>
        <div class="container">

                <!--Start Content Connectus-->
                    <form class="form-horizontal" method="POST" action="Controllers/registro.php">

                        <!-- Form Name -->
                        <div id="FormText">Ingresa los datos solicitados para crear tu cuenta.</div>

                        <?php
                            $token = base64_encode(rand(100,10000000).date('Ymdhsu') );
                            session_start();
                            $_SESSION['idreg'] = md5($token);
                        ?>

                        <input type='hidden' name='_token' id='_token' value='<?php echo $token ?>' />

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="username">Usuario</label>  
                            <div class="col-md-4">
                                <input id="username" name="username" type="text" placeholder="Usuario" class="form-control input-md" required>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="nombre">Nombre</label>  
                          <div class="col-md-4">
                          <input id="nombre" name="nombre" type="text" placeholder="Nombre" class="form-control input-md" required>
                            
                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="apellidos">Apellidos</label>  
                          <div class="col-md-4">
                          <input id="apellidos" name="apellidos" type="text" placeholder="Apellidos" class="form-control input-md" required>
                            
                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="fono">Teléfono</label>  
                          <div class="col-md-4">
                          <input id="fono" name="fono" type="text" placeholder="Teléfono" class="form-control input-md" required>
                            
                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="email">E-mail</label>  
                            <div class="col-md-4">
                                <input id="email" name="email" type="text" placeholder="Email" class="form-control input-md" required>
                                <span id="error_input_username" class="help-block"></span>
                            </div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="pass">Contraseña</label>
                          <div class="col-md-4">
                            <input id="pass" name="pass" type="password" placeholder="Contraseña" class="form-control input-md">
                            
                          </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                          <label class="col-md-4 control-label" for="submit_btn"></label>
                          <div class="col-md-4">
                            <button id="submit_btn" name="submit_btn" class="btn">
                                
                                <i class="glyphicon glyphicon-pencil"></i> Registrate
                            </button>
                          </div>
                        </div>

                    </form>
                <!--End Content Connectus-->
            </div>
			<div id="Footer">
				<div id="FooterLogo"><img src="images/logo.png" /><br /></div>
                <a href="/sms-masivo" class="FooterLink">sms</a> | <a href="/mail-masivo" class="FooterLink">e-mail</a> | ivr | whatsapp<br />
                <br />
                Santa Beatriz Nº 100, Oficina 501 - Providencia<br /><i class="fa fa-phone-square"></i> +569 8449 2882
			</div>

            <!-- End Main -->


        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

        <!-- JS -->
        <script src='js/registro.js'></script>
    </body>
</html>