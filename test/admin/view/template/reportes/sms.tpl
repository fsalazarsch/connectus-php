<?php echo $header; 		?>
<?php echo $column_left; 	?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">

			<div class="pull-right">
				
			</div>

    		<h1><? echo $titulo;?></h1>


    		<!--breadcrumbs de navegacion-->
		    <ul class="breadcrumb">
		        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
		        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		        <?php } ?>
		    </ul>
		</div>
	</div>




	<!--zona de mensaje de errores y confirmación de acciones-->
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
				<h3 class="panel-title"><i class="fa fa-list"></i><?php echo $detalle;?></h3>        
			</div>
	      	
	      	<div class="panel-body"> 
	     		<div class="row well">

				    <div class="col-sm-4">
						<div class="form-group">
							<label class="control-label" for="filter_name"> Nombre </label>
							<input type="text" name="filter_name" value="<?php echo $filter_name; ?>"  id="filter_name" class="form-control" />
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
		                	<button type="button" style="margin-top: 5px" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $btn_filtrar; ?></button>
		                </div>
		            </div>

		    	</div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                       
                            <td class="text-left"><?php if ($sort == 'fecha') { ?>
                                <a href="<?php echo $sort_fecha; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_creacion; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_fecha; ?>"><?php echo $column_creacion; ?></a>
                                <?php } ?></td>
                            <td class="text-left"><?php if ($sort == 'nombre') { ?>
                                <a href="<?php echo $sort_nombre; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_nombre; ?></a>
                                <?php } else { ?>
                                <a href="<?php echo $sort_nombre; ?>"><?php echo $column_nombre; ?></a>
                                <?php } ?></td>
                            <td class="text-left"><?php echo $column_tipo_archivo; ?></td>
                            <td class="text-left"><?php echo $column_archivo; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($lista_recibidos) { ?>
                        <?php foreach ($lista_recibidos as $listas) { ?>

                        <tr id="<?php echo "tr_".$listas['id_archivo']; ?>">
                            
                            <td class="text-left"><?php echo $listas['fecha']; ?></td>

                            <td class="text-left"><?php echo $listas['nombre']; ?></td>
                            <td class="text-left"><?php echo $listas['tipo_archivo']; ?></td>
                            <td class="text-left">
                            	<?php echo '<a href="http://test.connectus.cl/RepoProd/csv/'.$listas['ruta'].'"  title="Descargar"'; ?> ><i class="fa fa-download fa-lg" aria-hidden="true"></i></a>

                            	<?php echo '<a href="" data-id="'.$listas['id_archivo'].'" class="delete_archivo" title="Descargar"'; ?> ><i class="fa fa-trash fa-lg" aria-hidden="true"></i></a>
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
                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
	      	</div>
    	</div>
	</div>

	<?php echo $footer; ?>
</div>


<script type="text/javascript" src="view/javascript/reportes.js"></script>
     