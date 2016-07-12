<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">	
		<div class="pull-right">
			<a href="<?php echo $btn_excel; ?>" data-toggle="tooltip" title="<?php echo $export_excel; ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
        	<a id="refresh" data-toggle="tooltip" title="<?php echo $refresh; ?>" class="btn btn-default"><i id="spin" class="fa fa-refresh"></i></a>
        	<a href="<?php echo $btn_nuevo; ?>" id="pdf" data-toggle="tooltip" title="<?php echo $nuevo; ?>" class="btn btn-primary"><i  class="fa fa-pencil"></i></a>
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
		        <div class="btn-group col-sm-3 " style="margin-top: 5px" role="group" >
		          <br>
		          <?php if ($volumen == 'masivo') { ?>
		          	  <a  href="<?php echo $btn_masivo;?>" type="button" class="btn btn-primary historial_filter"><?php echo $masivo;?></a>
					  <a href="<?php echo $btn_unico;?>" id='mostrar' type="button" class="btn btn-default historial_filter"><?php echo $unico;?></a>
					  <a href="<?php echo $btn_api?>" type="button" class="btn btn-default historial_filter"><?php echo $api;?></a>
					  <?
			         }elseif ($volumen == 'api') { ?>
		          	  <a  href="<?php echo $btn_masivo;?>" type="button" class="btn btn-default historial_filter"><?php echo $masivo;?></a>
					  <a href="<?php echo $btn_unico;?>" id='mostrar' type="button" class="btn btn-default historial_filter"><?php echo $unico;?></a>
					  <a href="<?php echo $btn_api?>" type="button" class="btn btn-primary historial_filter"><?php echo $api;?></a>
					  <?
			         }else{ ?>
		          	  <a  href="<?php echo $btn_masivo;?>" type="button" class="btn btn-default historial_filter"><?php echo $masivo;?></a>
					  <a href="<?php echo $btn_unico;?>" id='mostrar' type="button" class="btn btn-primary historial_filter"><?php echo $unico;?></a>
					  <a href="<?php echo $btn_api?>" type="button" class="btn btn-default historial_filter"><?php echo $api;?></a>
					  <?
			         }?>
				  
			    </div>  
			    <div class="col-sm-4" hidden>
                  <div class="form-group">
                    <label class="control-label" for="input-nombre"> Nombre </label>
                    <input type="text" name="filter_nombre" value="<?php echo $filter_nombre; ?>"  id="input-nombre" class="form-control" />
                  </div>                          
                </div>  

                <div class="col-sm-4">                          
                  <div class="form-group">
                    <label class="control-label" for="input-fecha"> Desde </label>
                    <input type="date" name="filter_fecha" value="<?php echo $filter_fecha; ?>"  id="input-fecha" class="form-control" />
                  </div>
                </div> 

                <div class="col-sm-4">                          
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
		    <br/>
	        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-category">
	          <div class="table-responsive">
	            <table class="table table-bordered table-hover" id="table_edit">
	              <thead>
	              	<tr><?foreach($headers as $value){
	              			if ($value !='Estadisticas') {
	              				?><td><?php echo $value;?></td><?
	              			}else{
	              				if ($volumen == 'masivo') {
	              					?><td><?php echo $value;?></td><?
	              				}
	              			}
	              		}?>
	              	</tr>
	              	</tr>
	              </thead>
	              <tbody>	              
	              	<?php 
	              	if($envios){
	              		if ($volumen == 'masivo') {
	              			foreach($envios as  $value) {
		              			?><tr>
		              				<td><?php echo $value['fecha']; ?></td>
		              				<td><?php echo $value['nombre']; ?></td>
		              				<td><?php echo $value['tipo']; ?></td>
		              				<?php if ($value['estado']=='Terminado' || $value['estado']=='Confirmado') {
		              					?><td ><span class="label label-success"><?php echo $value['estado']; ?></span></td><?
		              				}elseif($value['estado']=='Pendiente'){
		              					?><td ><span class="label label-grey"><?php echo $value['estado']; ?></span></td><? 
		              				}else{
		              					?><td ><span class="label label-danger"><?php echo $value['estado']; ?></span></td><?
		              				}?>	              				
		              				<td><?php echo $value['volumen']; ?></td>	              				
		              				<td><?php echo $value['confirmados']; ?></td>
		              				<td><?php echo $value['malos']; ?></td>
		              				<td><?php echo $value['error']; ?></td>
									<td><?php echo $value['esperando']; ?></td>

									<?php if (strtolower($value['tipo']) == 'masivo' && $value['cant_detalles'] > 0) {
										?><td id="est[]"><a href="<?php echo $estadistica . '&envio=' . $value['id_envio'];?>"  class="btn btn-primary pull-right"><i class="fa fa-bar-chart"></i></a></td><?
									}elseif(strtolower($value['tipo']) == 'masivo'){
										?><td><a onclick="error()"  class="btn btn-default pull-right"><i class="fa fa-bar-chart"></i></a></td><?
										}?>		              			
			              		</tr><?php
	              	   	    }
	              		}else{
	              			foreach($envios as  $value) {
		              			?><tr>
		              				<td><?php echo $value['fecha']; ?></td>
		              				<td><?php echo $value['destino']; ?></td>
		              				<td><?php echo $value['carrier']; ?></td>
		              				
		              				<td><?php echo $value['mensaje']; ?></td>

		              				<?php if ($value['estado']=='Terminado'|| $value['estado']=='Confirmado') {
		              					?><td ><span class="label label-success"><?php echo $value['estado']; ?></span></td><?
		              				}elseif($value['estado']=='Pendiente'){
		              					?><td ><span class="label label-grey"><?php echo $value['estado']; ?></span></td><? 
		              				}else{
		              					?><td ><span class="label label-danger"><?php echo $value['estado']; ?></span></td><?
		              				}?>		              			
			              		</tr><?php
	              	   	    }

	              		}	              	    
	              	}else{
	              		?>
						<tr>
							<td class="text-center" colspan="12"><?php echo $text_no_results; ?></td>
						</tr>
	              		<?php
	              		}?>

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

	<?php
		$tipo = $_GET['tipo'];
		echo "<input type='hidden' value='$tipo' id='tipo_sms'> ";
	?>

	<?php echo $footer; ?>
</div>
<script type="text/javascript">
	function error () {
		alert('Este envio no tiene datos, presione actualizar para refrescar estados.');
	}
</script>

<script type="text/javascript">
        $('#button-filter').on('click', function() {


        	var tipo  = $("#tipo_sms").val(); if(tipo == ''){ tipo = 'masivo' }


            var url = 'index.php?route=sms/historial&token=<?php echo $token; ?>';

            var filter_nombre = $('input[name=\'filter_nombre\']').val();

            if (filter_nombre) {
                url += '&filter_nombre=' + encodeURIComponent(filter_nombre); 
            }

            var filter_fecha = $('input[name=\'filter_fecha\']').val();

            if (filter_fecha) {
                url += '&filter_fecha=' + encodeURIComponent(filter_fecha); 
            }

            var filter_fecha_hasta = $('input[name=\'filter_fecha_hasta\']').val();

            if (filter_fecha_hasta) {
                url += '&filter_fecha_hasta=' + encodeURIComponent(filter_fecha_hasta); 
            }

            url += "&tipo="+encodeURIComponent(tipo);

            location = url;
        });
</script>

<script type="text/javascript">	

	$('#refresh').on('click' , function(){
		var d = document.getElementById("spin");
		d.className = d.className + " fa-spin";		

		location = window.location.href;
	});     	    
</script>
     