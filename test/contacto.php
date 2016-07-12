<!DOCTYPE html>
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<html lang="es-ES">
    <head>
        <?php
            $pag = 'contacto';
            require 'include/meta.php';
            require 'include/head.php';
        ?>

        <style type="text/css">
            #txt_asunto{
                width: 100%;
            }
        </style>
    </head>

    <body class="page page-template-default custom-background waves-pagebuilder menu-fixed header-logo_left theme-full">

		<div id="theme-layout">

			<?php include_once 'include/header.php'; ?>

			<!-- Start Main -->
			<section id="main">
			<div id="page">
            	<!--Start Content Connectus-->
                <!--Start Page Header-->
                	<div id="PageHeader">
                    	<div class="container">
                        	<div class="waves-breadcrumbs">
								<div id="crumbs" class="">
								<span class="crumb-item"><a href="index.html">Homepage</a></span><span class="crumb-item current">Contacto</span>
                                </div>
                            </div>
                        	<div class="col-md-6"><img src="/img/contact-header-image.png" /></div>
                            <div class="col-md-6">
                                <div id="PageData"><br /><div id="PageTitle">COMUNÍCATE CON NOSOTROS</div><br />
                                Nuestros ejecutivos se pondrán en contacto<br>
                                contigo durante el transcurso del día.
								</div>
							</div>
                        </div>
                    </div>
                <!--End Page Header-->
                <!--Start Form-->
                	<div id="PortWhy">
                        <!--Start Contact Content-->
                        	<div class="row-container light bg-scroll" style="">
								<div class="waves-container container">
									<div class="row">
										<div class="col-md-12">
											<!--Start Form Title-->
                                			<div class="row">
												<div id="SectionTitleBlue">
													Déjanos tu mensaje
												</div>
											</div>
                                			<!--End Form Title-->
										<div class="row">

											<div class="tw-element col-md-12" style="">
												<div role="form" class="wpcf7" id="wpcf7-f335-p581-o1" lang="en-US" dir="ltr">
													<div class="screen-reader-response">
													</div>

    												<form id="contact-form">

                                                        <div class="row">
                                                            
                                                            <span class="wpcf7-form-control-wrap your-name">
                                                                <input type="text"  id="name" name="name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="Nombre completo"/>
                                                            </span>

                                                            <span class="wpcf7-form-control-wrap your-email">
                                                                <input type="text" name="mail" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="Email"/>
                                                            </span>

                                                            <span class="wpcf7-form-control-wrap your-fono">
                                                                <input type="text" name="fono"  value="" size="40" class="wpcf7-form-control wpcf7-text " aria-required="true" aria-invalid="false" placeholder="Teléfono"/>
                                                            </span>
                                                        </div>

                                                        <div class="row">
                                                                <input id='txt_asunto' type="text" name="website"  value=""  class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="Asunto"/>
                                                      
                                                        </div>
            											
                                                        <div class="row">
                                                            <p>
                                                                <span class="wpcf7-form-control-wrap your-message">
                                                                <textarea  name="comment" id="comment" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false" placeholder="Escribe aquí tu mensaje"></textarea></span>
                                                            </p>

                                                            <p>
                                                                <input type="submit" id="submit_contact" value="ENVIAR" class="wpcf7-form-control wpcf7-submit"/>
                                                                <div id="msg" class="message"></div>
                                                            </p>
                                                        </div>
                                                            
    												</form>
												</div>
											</div>
										</div>
									</div>
									</div>
								</div>
							</div>
                            <!--End Contact Content-->
                    </div>
                <!--End Form-->



                <!--End Content Connectus-->
			</div>
			</section>
			<!-- End Main -->

            <?php include_once 'include/footer.php'; ?>

		</div>
    
        <?php include_once 'include/javascript.php'; ?>
    </body>
</html>