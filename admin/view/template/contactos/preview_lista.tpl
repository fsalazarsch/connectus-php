<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
    <div class="pull-right">
      <a href="<?php echo $cancel;?>" class="btn btn-danger">Cancelar</a>
      <input type="submit" name="btn_confirmar"  value="Confirmar" class="btn btn-primary" onclick="valueSelects()" form='form-download'>         
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
    <?php if ($error_file) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_file; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
    <?php if ($error_warning_campo) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning_campo; ?>
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

        <form action="<?php echo $action; ?>" method="post" id="form-download">
            <div class="table-responsive table-max-with">
            <table id="table_preview" class="table table-bordered table-hover ">
              <thead>
                  <?php $sel_name = array(); 
                        $input_name = array();
                        $sel_values[] = array();
                  ?>

                  <?php if(!empty($datos)){
                  for ($i=0; $i < count($datos) ; $i++) {?><tr>
                      <?php for($x = 0; $x < count($datos[$i]); $x++) {
                        if ($i == 0) {
                          $sel_name[] = 'sel'.($x+1);
                          $sel_values[] = '';
                          $head = $encabezado ? $datos[$i][$x]: 'texto'.$x;?>
                <th style="background: #E6E6E6" >
                  <div>
                  <div class="min-with-select">
                    <select name="<?php echo 'sel'.$x; ?>"  id="<?php echo 'sel'.$x; ?>" class="form-control input-lg" >
                      <option selected value='ignorar'>ignorar</option>
                      <?php foreach ($tipoCampo as $tipo) {?>
                        <option value="<?php echo $tipo['nombre_tipo']; ?>"><?php echo $tipo['nombre_tipo']; ?></option>
                      <?php } ?>
                    </select>                
                    </div>
                    <script>

                      var previous1;
                      $("#<?php echo 'sel'.$x; ?>").focus(function () {
                          // Store the current value on focus, before it changes
                          previous1 = this.selectedIndex;
                         
                        }).change(function() {          
                          var prev = previous1;
                          previous1 = this.selectedIndex;

                          var x = this.selectedIndex;
                          var y = document.getElementById("<?php echo 'sel'.$x; ?>").length;

                          var lengthDatos = "<?php echo count($datos); ?>";
                          var lengthDatosCajaX = "<?php echo count($datos[$i]); ?>";

                          if(prev != 0){
                            for(j = 0; j <  lengthDatos; j++) {
                              for(k = 0; k <  lengthDatosCajaX; k++) {
                                if (j == 0) {
                                document.getElementById("sel"+ k).options[prev].disabled = false;                                   
                                }
                              }
                            }                                  
                          } 


                          if(x != (y - 1)){
                            document.getElementById("<?php echo $head;?>").disabled = true;
                            for(j = 0; j <  lengthDatos; j++) {
                              for(k = 0; k <  lengthDatosCajaX; k++) {
                                if (j == 0) {
                                document.getElementById("sel" + k).options[x].disabled = true;            
                                }
                              }
                            }
                          }else{
                            document.getElementById("<?php echo $head;?>").disabled = false;
                          }
                        });
                    </script>  
                  </div>  
                
                  <br>

                  <div class="form-group">              
                      <input disabled type="text" class="text-left form-control" name="<?php echo $head;?>" id="<?php echo $head;?>" value="<?php echo $head;?>"/>                      
                  </div>                 
                </th>
                <?php $input_name[] = $head;
                          }else{
                      ?><td class="success"><?php echo $datos[$i][$x]; ?></td><?php
                    }
                  } ?></tr><?php 
                  }
                  }
                  $_SESSION['sel_name'] = $sel_name;
                  $_SESSION['input_name'] = $input_name;
                  
                  ?>
              </thead>
            </table>
            </div>
          <input type="hidden" name="hidden_field" id="hidden_field" value=""/>
          <input type="hidden" name="hidden_input_name" id="hidden_input_name" value=""/>                   
        </form>        
      </div> 
    </div>     
  </div>  
</div>
<?php echo $footer;?> 


<script type="text/javascript">
  function valueSelects () {
    var values = [];
    var names = [];
  $('select :selected').each(function() {
      values.push($(this).text());
  });
  document.getElementById('hidden_field').value = values;
  $('input[type=text]').each(function(){
    names.push($(this).val());
    //alert($(this).val());
  });
  document.getElementById('hidden_input_name').value = names;
  }  
  </script>
 


 