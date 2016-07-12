<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid"> 
          <div class="pull-right">
          <a href="<?php echo $exp_excel; ?>" data-toggle="tooltip" title="<?php echo $excel_tip;?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
          <!--<a href="<?php //echo $exp_pdf; ?>" data-toggle="tooltip" title="<?php //echo $pdf_tip;?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>-->
          <a href="<?php echo $add; ?>" data-toggle="tooltip" title="Nuevo Sms" class="btn btn-primary"><i class="fa fa-plus"></i></a>
          <a href="<?php echo $historial;?>" class="btn btn-default" data-toggle="tooltip" title="Historial"><i class="fa fa-mail-reply" ></i></a>       
        </div>       
            <h1>Estadisticas</h1>
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
                <h3 class="panel-title"><i class="fa fa-list"></i>Datos del Envio</h3>
            </div>
            <div class="panel-body">
           <div class="row">
                <div class="col-xs-6 pull-left">
                    <div id="grafico" >
                        <!--reporte grafico-->
                    </div>
               </div>
               <div class="col-xs-6 pull-right">
                    <table class="table table-bordered table-hover">
                        <tr class="blackground">
                            <?php foreach ($headers as  $value) {
                                ?><td ><?php echo $value;?></td><?php
                            }?>
                        </tr>
                        <tr>                            
                            <?php  foreach ($totales as $key => $value) { 
                                if (strtoupper($key) == "TOTALES") { ?>
                                    <tr><td><b><?php echo strtoupper($key);?></b></td>
                                    <?php foreach ($value as $llave => $campo) {
                                        if($llave != 'fecha' && $llave !='nombre'){ ?>
                                        <td><b>                                     
                                          <?php  echo $campo; ?>
                                        </b></td>
                                        <?php }?>
                                        <?php } ?>                           
                                <?php } else{ ?>
                                    <tr><td><?php echo strtoupper($key);?></td>
                                    <?php  foreach ($value as $llave => $campo) {
                                        if($llave != 'fecha' && $llave !='nombre'){ ?>
                                        <td>                                     
                                           <?php echo $campo; ?>
                                        </td><?php } ?>
                                        <?php }    
                                    }?> 
                                </tr> 
                             <?php } ?>                                                      
                        </tbody> 
                    </table>
                </div>
            </div>
            <?php if ($filt == 'confirmados') { ?>
                    <div  class="btn-group">
                      <a href="<?php echo $btn_confirmado;?>" type="button" class="btn btn-primary "><?php echo $confirmados;?></a>
                      <a href="<?php echo $btn_esperando;?>" type="button" class="btn btn-default "><?php echo $esperando;?></a>
                      <a href="<?php echo $btn_error;?>" type="button" class="btn btn-default "><?php echo $error;?></a>
                      <a href="<?php echo $btn_total;?>" type="button" class="btn btn-default "><?php echo $todos;?></a>
                      </div><br>
                      <?
                     }elseif ($filt == 'esperando') { ?>
                     <div  class="btn-group">
                      <a href="<?php echo $btn_confirmado;?>" type="button" class="btn btn-default "><?php echo $confirmados;?></a>
                      <a href="<?php echo $btn_esperando;?>" type="button" class="btn btn-primary "><?php echo $esperando;?></a>
                      <a href="<?php echo $btn_error;?>" type="button" class="btn btn-default "><?php echo $error;?></a>
                      <a href="<?php echo $btn_total;?>" type="button" class="btn btn-default "><?php echo $todos;?></a>
                      </div><br> 
                      <?
                     }elseif ($filt == 'error') { ?>
                     <div  class="btn-group">
                      <a href="<?php echo $btn_confirmado;?>" type="button" class="btn btn-default "><?php echo $confirmados;?></a>
                      <a href="<?php echo $btn_esperando;?>" type="button" class="btn btn-default "><?php echo $esperando;?></a>
                      <a href="<?php echo $btn_error;?>" type="button" class="btn btn-primary "><?php echo $error;?></a>
                      <a href="<?php echo $btn_total;?>" type="button" class="btn btn-default "><?php echo $todos;?></a>
                      </div><br>
                      <?
                     }elseif($filt = 'totales'){ ?>
                     <div  class="btn-group">
                     <a href="<?php echo $btn_confirmado;?>" type="button" class="btn btn-default "><?php echo $confirmados;?></a>
                      <a href="<?php echo $btn_esperando;?>" type="button" class="btn btn-default "><?php echo $esperando;?></a>
                      <a href="<?php echo $btn_error;?>" type="button" class="btn btn-default "><?php echo $error;?></a>
                      <a href="<?php echo $btn_total;?>" type="button" class="btn btn-primary "><?php echo $todos;?></a>
                      </div><br>
                      <?
                     }?>
                     <br>
            <table class="table table-bordered table-hover">
                <tr class="blackground"><td><?php echo $id;?></td><td><?php echo $destinatario;?></td><td><?php echo $fecha;?></td><td><?php echo $compania;?></td><td><?php echo $estado;?></td><td><?php echo $mensaje;?></td></tr>
            <?php if ($registros) {
                foreach ($registros as $value) {?>
                <tr>
                <!--<td><?php echo html_entity_decode( $value['mensaje'], ENT_QUOTES, 'UTF-8');?></td>-->
                <td><span ><?php echo $value['numero'];?></span></td>
                <td><span ><?php echo $value['destinatario'];?></span></td>
                <td><span ><?php echo $value['fecha'];?></span></td>
                <td><span ><?php echo $value['compania']; ?></span></td>
                <?php if ($value['estado'] == 'Confirmado') { ?>
                    <td><span class="label label-success"><?php echo $value['estado'];?></span></td>
                <?php }elseif ($value['estado'] == 'Error de entrega' || $value['estado'] == 'No entregado' || $value['estado'] == 'DNS Inválido') { ?>
                    <td><span class="label label-danger"><?php echo $value['estado'];?></span></td>
                <?php }else{ ?>
                    <td><span class="label label-grey"><?php echo $value['estado'];?></span></td>
                <?php }?>                
                <!--<td><?php echo $value['destinatario'];?></td>-->
                <td><a name="<?php echo $value['mensaje'];?>" id="msg[]" class="btn btn-primary" onclick="showmessage(this)" data-target="#myModal" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                </tr>
                <?php 
                }
            }else{ ?>
                <tr><td class="text-center" colspan="8"><?php echo $text_no_result;?></td></tr>
             <?php } ?>           
            </table>  
                              
            <!-- Modal para editar contactos-->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="MessageModal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header" style="background: #4eaefa; color: white; font-weight: bold;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel"><b><?php echo $titulo;?></b></h3>
                  </div>
                    <div class="modal-body" id="showmessage">
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" id="mcan" class="btn btn-primary" data-dismiss="modal"><?php echo $aceptar;?></button>
                    </div>
                </div>
              </div>
            </div>

                <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                </div>
            </div>
          <?php echo $footer; ?>          
        </div>  
    </div> 
<script type="text/javascript">
    function showmessage (name) {
        var sms = $(name).attr('name');
        document.getElementById('showmessage').innerHTML = '';
        $('#showmessage').append(sms);
    }
</script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="http://code.highcharts.com/adapters/standalone-framework.js"></script>
<script type="text/javascript">
    $(function () {
        // Radialize the colors
    Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    });

    $('#grafico').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: '<?php echo strtoupper($chart_name); ?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                    },
                    connectorColor: 'silver'
                }
            }
        },
        series: [{
            name: "Estado",
            data: [
                {
                    name: "Confirmados",
                    y: <?php echo $estadistica['totales']['confirmados'];?>,
                    sliced: true,
                    selected: true
                },               
                {name: "Esperando", y: <?php echo $estadistica['totales']['esperando'];?>},
                {name: "No entregados", y:  <?php echo $estadistica['totales']['error'];?>}
            ]
        }],
        exporting: {
            enabled: false,
            sourceWidth: 600,
            sourceHeight: 600
        }
    });
}); 
</script>