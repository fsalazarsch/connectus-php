<?php


require('http://connectus.cl/libreriaExcel/PHPExcel.php');

// create new PHPExcel object
$objPHPExcel = new PHPExcel;


// create the writer
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

$objSheet = $objPHPExcel->getActiveSheet();

// rename the sheet
$objSheet->setTitle('Reporte');

/*
    # Producción
    $str_con = "mysql:host=localhost;dbname=connectu_adm_connectus; charset=utf8";
    $con = new PDO($str_con,'connectu_connect','cOnNectUs_05041977_.#'); 

    date_default_timezone_set("Chile/Continental");
	if($_GET['id_lista']){
	$id_lista = $_GET['id_lista'];

     $sql2 = "select id_empresa, datos_envio_programado from envio where id_envio = " . $id_lista.";" ;

    $stmt2 = $con->prepare($sql2);
    $stmt2->execute();
    while ($result2 = $stmt2->fetch())
    {
        if(!empty($result2['id_empresa']))
        {
			$id_empresa = $result2['id_empresa'];
			$datos_envio_programado = $result2['datos_envio_programado'];
			
		}
	}
	
	//echo $id_empresa;
	//echo $datos_envio_programado;
	
	if((!$_GET['id_empresa']) || ($_GET['id_empresa'] != $id_empresa)){
	//	echo "No ha proporcionado la empresa correctamente";
	}
	else{
	$datos_envio = json_decode($datos_envio_programado);
            if(!empty($datos_envio->asunto)){
                $asunto = $datos_envio->asunto;
            }else{
                $asunto = '';
            }		    
	/*
	$sql = "select E.nombre_envio as nombre,
                E.cuando_enviar as cuando_enviar,                
                D.estado as estado,
                D.destinatario as destinatario,
                E.correo_remitente as correo_remitente,
                D.estado_open as leido ,
                D.estado_click as click ,
                D.estado_spam as spam
                from detalle_envio as D inner join envio as E
                on D.id_envio = E.id_envio                
                where E.id_envio = ". $id_lista.";";


    $stmt = $con->prepare($sql);
    $stmt->execute();
*/
	
  //  $nombre = "Reporte Historico ".$id_lista." ".$asunto;

//    $name_csv = $id_lista." fact.xlsx";

    //$csv = fopen('csv/'.$name_csv, 'w');
	
	$objSheet->getCell('A1')->setValue('Product');
	$objSheet->getCell('B1')->setValue('Quanity');
	$objSheet->getCell('C1')->setValue('Price');
	$objSheet->getCell('D1')->setValue('Total Price');

  /*  $headers = array(   'Nombre',
                        'Fecha',
                        'estado',
                        'Destinatario',
                        'Remitente',
                        'Leido',
                        'Click',
                        'Sppam' );

    //fputcsv($csv, $headers);

    
  /*
    while ($result = $stmt->fetch())
    {
        if(!empty($result['nombre']))
        {
            //fputcsv($csv, 
                    array(  $result['nombre'],
                            $result['cuando_enviar'],
                            $result['estado'],
                            $result['destinatario'],
                            $result['correo_remitente'],
                            $result['leido'],
                            $result['click'],
                            $result['spam'],
             //           )
                    );

            //$count++;
        }
    }

    //fclose($csv);


    # Guardamos los datos del archivo creado en BD
	
	
	 $fecha = new DateTime();
    $fecha  =   $fecha->format('Y-m-d H:i:s');

	
    $sql = "INSERT INTO connectu_adm_connectus.archivos
            (nombre, ruta, creacion, tipo_archivo, tipo) VALUES
            ('".$nombre."', '".$name_csv."', '".$fecha."', 'xlsx', ".$id_empresa.") ";


    $stmt = $con->prepare($sql);

    $stmt->execute();
	}
	}
	*/
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="file.xlsx"');
header('Cache-Control: max-age=0');

$objWriter->save('php://output');
