<?php

    set_time_limit(0);
    error_reporting(E_ALL);

    # Producción
    //$str_con = "mysql:host=localhost;dbname=connectu_adm_connectus; charset=utf8";
    //$con = new PDO($str_con,'connectu_connect','cOnNectUs_05041977_.#'); 

    # Testing
    $str_con = "mysql:host=localhost;dbname=connectu_adm_test; charset=utf8";
    $con = new PDO($str_con,'connectu_test','Connectus.2016;');


    date_default_timezone_set("Chile/Continental");

    $fecha = new DateTime();
    $fecha->modify('-1 month');

    $anio   =   $fecha->format('Y');
    $mes    =   $fecha->format('m');
    $fecha  =   $fecha->format('Y-m-d H:i:s');

    
    // sql prod
    /*$sql = "SELECT 
                e.id_empresa    as id_empresa,
                d.id_detalle_envio as id_detalle,
                e.estado        as estado_envio,
                e.tipo_envio    as tipo_envio,
                e.tipo_mensaje  as tipo_mensaje,
                d.estado        as estado_mensaje,
                e.cuando_enviar as cuando_enviar,
                c.id_conector   as id_conector,
                c.glosa         as conector,
                a.firstname     as nombre,
                g.name          as tipo_cliente
                
            FROM connectu_adm_connectus.envio e 
            JOIN connectu_adm_connectus.detalle_envio d ON d.id_envio = e.id_envio
            LEFT JOIN connectu_adm_connectus.conector c ON e.id_conector = c.id_conector
            LEFT JOIN connectu_connecta.assert_customer a ON e.id_empresa = a.customer_id
            INNER JOIN connectu_connecta.assert_customer_group_description g ON a.customer_group_id = g.customer_group_id 

            WHERE e.tipo_mensaje = 'SMS' 
            AND MONTH(e.cuando_enviar) in ($mes)
            AND YEAR(e.cuando_enviar) = $anio; ";*/

    // sql test
    $sql = "SELECT 
                e.id_empresa    as id_empresa,
                d.id_detalle_envio as id_detalle,
                e.estado        as estado_envio,
                e.tipo_envio    as tipo_envio,
                e.tipo_mensaje  as tipo_mensaje,
                d.estado        as estado_mensaje,
                e.cuando_enviar as cuando_enviar,
                c.id_conector   as id_conector,
                c.glosa         as conector,
                a.firstname     as nombre,
                g.name          as tipo_cliente
                
            FROM connectu_adm_test.envio e 
            JOIN connectu_adm_test.detalle_envio d ON d.id_envio = e.id_envio
            LEFT JOIN connectu_adm_test.conector c ON e.id_conector = c.id_conector
            LEFT JOIN connectu_connecta_test.assert_customer a ON e.id_empresa = a.customer_id
            INNER JOIN connectu_connecta_test.assert_customer_group_description g ON a.customer_group_id = g.customer_group_id 

            WHERE e.tipo_mensaje = 'SMS' 
            AND MONTH(e.cuando_enviar) in ($mes)
            AND YEAR(e.cuando_enviar) = $anio; ";

    $stmt = $con->prepare($sql);
    $stmt->execute();



    //$name_csv = date('Ymd')."_sms_reporte.csv";
    $nombre = "Reporte SMS ".$anio."-".$mes;

    $name_csv = $anio."-".$mes." SMS.csv";

    $csv = fopen('csv/'.$name_csv, 'w');


    $headers = array(   '#',
                        'ID Cliente',
                        'ID Detalle',
                        'Nombre',
                        'Tipo cliente',
                        'Estado',
                        'Tipo envío',
                        'Tipo mensaje',
                        'Estado mensaje',
                        'Conector',
                        'Enviado' );

    fputcsv($csv, $headers);

    
    $count=1;

    while ($result = $stmt->fetch())
    {
        if(!empty($result['id_empresa']))
        {
            fputcsv($csv, 
                    array(  $count,
                            $result['id_empresa'],
                            $result['id_detalle'],
                            $result['nombre'],
                            $result['tipo_cliente'],
                            $result['estado_envio'],
                            $result['tipo_envio'],
                            $result['tipo_mensaje'],
                            $result['estado_mensaje'],
                            $result['conector'],
                            $result['cuando_enviar'],
                        )
                    );

            $count++;
        }
    }

    fclose($csv);


    # Guardamos los datos del archivo creado en BD

    // sql prod
    /*$sql = "INSERT INTO connectu_adm_connectus.archivos
            (nombre, ruta, creacion, tipo_archivo, tipo) VALUES
            ('$nombre', '$name_csv', '$fecha', 'csv', 1) ";*/

    // sql test
    $sql = "INSERT INTO connectu_adm_test.archivos
            (nombre, ruta, creacion, tipo_archivo, tipo) VALUES
            ('$nombre', '$name_csv', '$fecha', 'csv', 1) ";


    $stmt = $con->prepare($sql);

    $stmt->execute();
    