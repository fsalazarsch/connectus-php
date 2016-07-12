<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
        <div class="pull-right">
        <a href="<?php echo $excel; ?>"  data-toggle="tooltip" title="<?php echo $excel_tip;?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i></a>
        <!--<a href="<?php //echo $pdf; ?>" data-toggle="tooltip" title="<?php //echo $pdf_tip;?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>-->
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="Nuevo mail" class="btn btn-primary"><i class="fa fa-plus"></i></a>
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
                    <div id="grafico" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto" >
                        <!--reporte grafico-->
                    </div>



               </div>
               <div class="col-xs-5 pull-right">
                   <table class="table table-bordered table-hover">

                        <tr class="blackground">
                            <td ><?php echo $tipo;?></td>
                            <td ><?php echo $valores;?></td>
                        </tr>
                        
                        <tr>
                            <td><?php echo $abiertos;?></td>
                            <td><?php echo $estadistica['abiertos']?></td>
                        </tr>
                        <tr>
                            <td><?php echo $leidos;?></td>
                            <td><?php echo $estadistica['leidos']?></td>
                        </tr>
                        <tr>
                            <td><?php echo $click;?></td>
                            <td><?php echo $estadistica['click']?></td>
                        </tr>

                        <tr>
                            <td><?php echo $spam;?></td>
                            <td><?php echo $estadistica['spam']?></td>
                        </tr>
                        
                    </table>
                </div>
            </div>


            <div class='table_panel'>

                <?php

                    $op1=$op2=$op3=$op4=$op8 = 'btn-default';
                    $op5=$op6=$op7= 'btn-warning';

                    switch ($filt) {
                        case 'entregado':   $op1 = 'btn-primary'; break;
                        case 'esperando':   $op2 = 'btn-primary'; break;
                        case 'rebote':      $op3 = 'btn-primary'; break;
                        case 'malo':        $op4 = 'btn-primary'; break;
                        case 'abierto':     $op5 = 'btn-primary'; break;
                        case 'click':       $op6 = 'btn-primary'; break;
                        case 'spam':        $op7 = 'btn-primary'; break;
                        case 'totales':     $op8 = 'btn-primary'; break;
                    }
                ?>

                <div  class="btn-group">
                    <a href="<?php echo $btn_entregados;?>" type="button" class="btn <?php echo $op1; ?>"><?php echo $entregados;?></a>
                    <a href="<?php echo $btn_esperando;?>"  type="button" class="btn <?php echo $op2; ?>"><?php echo $esperando;?></a>
                    <a href="<?php echo $btn_rebotes;?>"    type="button" class="btn <?php echo $op3; ?>"><?php echo $rebotes;?></a>
                    <a href="<?php echo $btn_malos;?>"      type="button" class="btn <?php echo $op4; ?>"><?php echo $malo;?></a>
                    <a href="<?php echo $btn_abiertos;?>"   type="button" class="btn <?php echo $op5; ?>"><?php echo $leidos;?></a>
                    <a href="<?php echo $btn_click;?>"      type="button" class="btn <?php echo $op6; ?>"><?php echo $click;?></a>
                    <a href="<?php echo $btn_spam;?>"       type="button" class="btn <?php echo $op7; ?>"><?php echo $spam;?></a>
                    <a href="<?php echo $btn_totales;?>"    type="button" class="btn <?php echo $op8; ?>"><?php echo "Todos";?></a>
                </div>

                      
                <br><br>

                <h4>Asunto: <strong><?php echo $asunto; ?></strong><br>Remitente: <strong><?php echo $remitente; ?></strong></h4>
                <br>

                <table class="table table-bordered table-hover">

                    <tr class="blackground">
                        <td>ID</td>
                        <!-- <td>Asunto</td>
                        <td>Remitente</td>-->
                        <td><?php echo $destinatario;?></td>
                        <td><?php echo $estado;?></td>
                        <td>Abierto total</td>
                        <td class='tr_detalles'>Click</td>
                        <td>Spam</td>
                        <td>Mensaje</td>
                    </tr>

                    <?php 
                        if ($detalles){ 
                            foreach ($detalles as $value) { ?>

                                <tr>
                                    <td><?php echo $value['numero'];?></td>
                                    <!--<td><?php echo $value['nombre_envio'];?></td>
                                    <td><?php echo $value['correo_remitente'];?></td>-->
                                    <td><?php echo $value['destinatario'];?></td>
                                    
                                    <?php 
                                        if ($value['estado']=='Rebote') {
                                            ?><td ><span class='label label-danger'><?php echo $value['estado']; ?></span></td><?php
                                        }elseif($value['estado']=='Click' || $value['estado']=='Abierto' || $value['estado']=='Entregado'){
                                        ?><td ><span class='label label-success'><?php echo $value['estado']; ?></span></td><?php
                                        }else {
                                            ?><td ><span class='label label-grey'><?php echo $value['estado']; ?></span></td><?php
                                        }
                                    ?> 

                                    <td class="tr_detalles"><?php echo $value['leido'];?></td>                  
                                    <td class="tr_detalles"><?php echo $value['click'];?></td>
                                    <td class="tr_detalles"><?php echo $value['spam'];?></td>


                                    <td>

                                    <a id="msg"  href="<?php echo $btn_mensaje.'&cont='.$value['id_contacto'].'&d='.$value['destinatario']; ?>" class="btn btn-primary" target="_blank" ><i class="fa fa-eye"></i></a>
                                    </td>
                              </tr>
                            
                            <?php
                            } 
                        }else { ?>
                       <tr>
                          <td class="text-center" colspan="8"> <?php echo $text_no_results; ?> </td>
                      </tr>
                     <?php } ?>
                </table>

            </div>

            <!-- Modal para editar contactos-->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="MessageModal">
              <div class="modal-dialog" role="document">
                <div class="modal-content" style="" >
                  <div class="modal-header" style="background: #4eaefa; color: white; font-weight: bold;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel"><b><?php echo $titulo;?></b></h3>
                  </div>
                    <div class="modal-body" id="showmessage" style="width: 60%;" >
                      <?php echo html_entity_decode($mensaje_enviado, ENT_QUOTES, 'UTF-8');?>
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
    function showmessage () {
        
        
    }
</script>

<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="http://code.highcharts.com/adapters/standalone-framework.js"></script>
<script type="text/javascript">
    $(function () {
        // Radialize the colors
    

   /* $('#grafico').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: '<?php echo strtoupper($estadistica['nombre'] ." " . $estadistica['fecha']);?>'
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
                    name: "Entregados",
                    y: <?php echo $estadistica['entregados'];?>,
                    sliced: true,
                    selected: true
                },               
                {name: "Malos", y: <?php echo $estadistica['malos'];?>},
                {name: "Rebotados", y:  <?php echo $estadistica['rebotes'];?>},
                {name: "Clicks", y:  <?php echo $estadistica['click'];?>},
                {name: "Abiertos", y:  <?php echo $estadistica['leidos'];?>}
            ]
        }],
        exporting: {
            enabled: false,
            sourceWidth: 600,
            sourceHeight: 600
        }
    });
*/

  


    $('#grafico').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Estadisticas Envío E-mail'
        },
        subtitle: {
            text: '<?php echo strtoupper($estadistica['nombre'] ." " . $estadistica['fecha']);?>'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Cantidad de E-mail'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Cantidad de E-mails: <b>{point.y:.0f} </b>'
        },
        series: [{
            name: 'Estados',
            data: [
                ['Abiertos únicos', <?php echo $estadistica['abiertos'];?>],
                ['Abiertos total', <?php echo $estadistica['leidos'];?>],
                ['Click', <?php echo $estadistica['click'];?>],
                ['Spam', <?php echo $estadistica['spam'];?>],
            ],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.0f}', // one decimal
                y: 10, // 10 pixels down from the top
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });

    
});
    
</script>
