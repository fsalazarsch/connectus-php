<?php
    
    error_reporting(E_ALL);
    set_time_limit(0);
    
    require 'Controllers/ReportEnvioController.php';

    $reporte = new ReportEnvio();

    $clientes = $reporte->getClientes();


    echo "<table border='1'>";

    echo "<tr>";
    echo "<th>ID Cliente</th>";
    echo "<th>Cliente</th>";
    echo "<th>Tipo Cliente</th>";
    echo "<th>ID Envío</th>";
    echo "<th>Tipo</th>";
    echo "<th>Tipo Mensaje</th>";
    echo "<th>Fecha</th>";
    echo "<th>Volumen</th>";
    echo "</tr>";


    # REPORTE DE FACTURACIÓN


    while ($row = $clientes->fetch_object()) {
        
        //echo "<tr><td colspan='5'>".$row->customer_id." / ".$row->firstname."</td></tr>";

        $envios =  $reporte->getEnviosPorCliente((int)$row->customer_id);

        if($envios){
            while ($r = $envios->fetch_object()) {
                
                echo "<tr>";
                echo "<td>".$row->customer_id."</td>";
                echo "<td>".$row->firstname."</td>";
                echo "<td>".$row->name."</td>";

                echo "<td>".$r->id_envio."</td>";
                echo "<td>".$r->tipo_envio."</td>";
                echo "<td>".$r->tipo_mensaje."</td>";
                echo "<td>".$r->cuando_enviar."</td>";


                $volumen = $reporte->getVolumen($r->id_envio);

                $cant = $volumen->fetch_object()->cantidad;
                    
                echo "<td>".$cant."</td>";

                echo "</tr>";
                
            }
        }else{

            echo "<tr>";
            echo "<td>".$row->customer_id."</td>";
            echo "<td>".$row->firstname."</td>";
            echo "<td>".$row->name."</td>";
            echo "<td colspan='5' >Sin resultados</td>";
            echo "</tr>";
        }

            

    }

    echo "</table>";
    

?>