<?php ///////DESARROLLO
/**
* Exportar archivos
*/
include_once dirname(__FILE__)  . '/libreriaExcel/PHPExcel.php'; 
include_once dirname(__FILE__)  . '/libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc.php'; 

class FileManager
{
	private $excel_writer;
	private $php_excel;
	private $pdf_writer;
	private $mPDF;
	private $H2P;

	private $letter_gen;
	private $number_gen;

	private $pdf_paper_properties;
	private $size_title = 32;
	private $pdf_ini = "<!DOCTYPE html>
               	    <html>
	               	 <head>	               	 
	                    <style>   

	                    body{
	                        padding: 30px 0px 0px 0px ;
	                    }  

	                    .lon{
							width: 200px;
	                	} 

	                	tr td:first{
	                		width: 1px; 
	                	}    

	                    h1{
	                        font-family:Arial, Helvetica, sans-serif;
	                        color:black;
	                        font-size:48px;
	                        margin:0px auto;
	                        border:#ccc 1px solid;
	                        padding: 5px;
	                        text-align: center;
	                        width: 90%;

	                    }

	                    table {
	                        font-family:Arial, Helvetica, sans-serif;
	                        color:#666;
	                        font-size:10px;
	                        background:#eaebec;
	                        margin:0px auto;
	                        border:#ccc 1px solid;
	                        width:90%;
	                    }
	                    table th {
	                        padding:10px 10px 10px 10px;
	                        border-top:1px solid #fafafa;
	                        border-bottom:1px solid #e0e0e0;
	                        background: #ededed;
	                        text-align: left;
	                    }

	                    table tr {
	                        text-align: center;
	                        padding-left:20px;
	                    }

	                    table td {
	                        padding:5px;
	                        border-top: 1px solid #ffffff;
	                        border-bottom:1px solid #e0e0e0;
	                        border-left: 1px solid #e0e0e0;
	                        background: #fafafa;
	                        text-align: left;
	                        width: 46px;
	                    }
	                    
	                    .sin-bordes{
							border: none;
							shadow:none;
							-webkit-box-shadow: none;
  							-moz-box-shadow: none;
  							box-shadow: none;
	                    }

	                    img{
	                    	width:150px;
	                    	height:150px;
	                    }
	                    </style>
	                    <meta><meta charset='UTF-8'></meta>
	                </head>
	                <body>
	                <h1>";

	private $title = 'Title';
	private $ps_title = "</h1><br>" ;
	
	private $table_start = "<table cellspacing='0'>";
	private $table_end   = "</table>";

	private $chart = '';

	private $pdf_end = "</body> </html>";

	private $img_directory;
	//private $mini_img_dir = DIR_REDUCIDAS;

	function __construct()
	{
		$this->php_excel   = new PHPExcel();
		$this->writer      = new PHPExcel_Writer_Excel2007($this->php_excel);
		$this->pdf_writer  = new DOMPDF();
		//$this->H2P = new HTML2PDF('L','A4','fr');
	}

	/* Inicio trabajos con EXCEL */
	public function excel($col_name_array, $data_matriz, $doc_name, $no_incluir = array())
	{	
		$this->php_excel->setActiveSheetIndex(0);	

		
		//nombre de la hoja
		$this->php_excel->getActiveSheet()->setTitle($doc_name);		 
		
		//escribiendo los encabezados de la tabla de datos
		$index = $this->startIndex();

		$content = 0;
		$contador_de_registros = 1;
		foreach ($col_name_array as $key => $col_name) {

			$data_index = $this->numeros_a_letras($content).$contador_de_registros;

			$this->php_excel->getActiveSheet()->setCellValue($data_index, $col_name);
			$this->php_excel->getActiveSheet()->getColumnDimension($this->numeros_a_letras($content))->setAutoSize(true);
			$index = $this->nextColumn(true);

			$content++;
		}

		$contador_de_registros++;

		//escribiendo los registros
		foreach ($data_matriz as $key => $row) {
			$content = 0; 
			//$data_index = $this->nextRow(true);
			foreach ($row as $nom_col => $cell_value) {
				if (!empty($no_incluir)) {
					if (!in_array($nom_col, $no_incluir)) {

						$data_index = $this->numeros_a_letras($content).$contador_de_registros;
						
						$this->php_excel->getActiveSheet()->setCellValue($data_index, $cell_value );
						$this->php_excel->getActiveSheet()->getColumnDimension($this->numeros_a_letras($content))->setAutoSize(true);
						$data_index = $this->nextColumn(true);

						$content++;
					}
				}else{
					$data_index = $this->numeros_a_letras($content).$contador_de_registros;					

					$this->php_excel->getActiveSheet()->setCellValue($data_index,  $cell_value );
					$this->php_excel->getActiveSheet()->getColumnDimension($this->numeros_a_letras($content))->setAutoSize(true);
					$data_index = $this->nextColumn(true);

					$content++;
				}
				
			}
			$contador_de_registros++;
				
		}

		$filename = $doc_name. '.xlsx';
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=". $filename);
		header("Cache-Control: max-age=0");

		$this->excel_writer = new PHPExcel_Writer_Excel2007($this->php_excel);
		$this->excel_writer->save("php://output");
	}

	function numeros_a_letras($numero) {
	    // Convierte un numero en una letra de la A a la Z en el alfabeto latin
	    // Utilizado para las columnas de excel
	    // A = 0, si queremos que A = 1, modificaremos 26 por 27 (en los 2 sitios que esta)
	 
	    // Si le pasamos el valor 0 nos devolvera A, si pasamos 27 nos devolvera AB
	    $res = "";
	    
	    while ($numero > -1) {
	        // Cargaremos la letra actual
	        $letter = $numero % 26;
	        $res = chr(65 + $letter) . $res;  // A 65 en ASCII (A) le sumaremos el valor de la letra y lo convertiremos a texto (65 + 0 = A)
	        
	        $numero = intval($numero / 26) - 1; // Le quitamos la letra para ir a la siguiente y le restamos 1 si no se saltara una serie
	    }            
	    
	    return $res;
	}

	public function excelToHTML($filename)
	{
		$inputFileName = $filename;
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

		$path_in_array = explode('/', $filename);
		$fullname = end($path_in_array);
		$name = explode('.', $fullname);

		header("Content-Disposition: attachment; filename='". $name[0] .".html'");
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
		$objWriter->save('php://output');
	}
	
	/* Reestablece el indice para un nuevo archivo */
	public function startIndex()
	{
		$this->letter_gen = 65;
		$this->number_gen =  1;

		return 'A1';
	}

	/* Cambia el foco a la columna siguiente.- Aumenta la letra.- A->B->N */
	public function nextColumn($next = false)
	{
		if ($next) {
			$ln_index = $this->letter(true) . $this->number();
		}else{
			$ln_index = $this->letter() . $this->number();
		}

		return $ln_index;		
	}

	/* Posiciona el foco en la fila excel siguiente */
	public function nextRow()
	{
		$this->number_gen++;
		$this->letter_gen = 65;

		return $this->letter() . $this->number();
	}

	/* Genera la letra indice para asignar valores a la hoja excel */
	public function letter($letter_up = false)
	{
		if ($letter_up) {
			$this->letter_gen++;

			$index = chr($this->letter_gen);

			if ($this->letter_gen > 90) {
				$ltr1 = ($this->letter_gen - 25) ; 
				$index = 'A'.chr($ltr1);
			}

			return $index;
		}else{
			$index = chr($this->letter_gen);

			if ($this->letter_gen > 90) {
				$ltr1 = ($this->letter_gen - 25) ; 
				$index = 'A'.chr($ltr1);
			}

			return $index;
		}
	}

	/* Maneja la numeracion que acompaña la letra para formar el indice de la columna excel */
	public function number($number_up = false)
	{
		if ($number_up) {
			$this->number_gen++;
			return $this->number_gen;
		}else{
			return $this->number_gen;
		}
	}

	/* Propiedades propias del documento creado */
	public function setExcelDocProperties($author, $description, $subject, $keywords)
	{
		$this->php_excel->setActiveSheetIndex(0);
		$this->php_excel->getProperties()
			->setCreator($creator)
			->setDescription($description)
			->setSubject($subject)
			->setKeywords($keywords);

		$this->excel_writer = $this->php_excel;
	}


	/* Metodo para cambiar color a celdas */
	//implementar aca

	/* Exportar como CSV separado por punto y coma (;) */
	public function csv($col_name_array, $data_matriz, $doc_name)
	{
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $doc_name . '.csv');

		//establecer que se descargue automaticamente
		$output = fopen('php://output', 'w');
		//establecer codificacion UTF-8
		fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

		//escribir el nombre de las columnas
		fputcsv($output, $col_name_array,';');

		//Poner datos 
		foreach ($data_matriz as $key => $data_row) {
			fputcsv($output, $data_row,';');
		}
	}

	/* Fin EXCEL*/



	/*Inicio manejo de PDF*/
	public function pdf($col_name_array, $data_matriz, $doc_name, $no_incluir = array())
	{	
		ini_set("memory_limit","-1");
		$this->PDFTitle($doc_name);

		$content = $this->pdf_ini;
		$content .= $this->title;
		$content .= $this->ps_title;
		$content .= $this->table_start;

		$content .= $this->addTableHeaders($col_name_array);

		foreach ($data_matriz as $key => $row) {
			if (!empty($no_incluir)) {
				$content .= $this->addRow($row,$no_incluir);
			}else{
				$content .= $this->addRow($row);
			}
				
		}
		
		$content .= $this->table_end;
		$content .= $this->pdf_end;

		/*html2pdf toma el html y no genera, mostrando un pdf listo para descargar. Proceso mucho mas rapido, 
		$this->H2P->writeHTML($content);
		$this->H2P->Output($doc_name.".pdf");
		*/

		/* con dompdf el archivo se crea y se descarga inmediatamente.- pero el render es demasiado lento y provoca error*/
		$this->pdf_writer->load_html($content);
		$this->pdf_writer->render();
		$this->pdf_writer->stream($doc_name.'.pdf');
		
			
	}

	/*public function pdf_with_images($col_name_array, $data_matriz, $doc_name, $no_incluir = array())
	{	
		ini_set("memory_limit","-1");
		$this->PDFTitle($doc_name);
		$content = $this->pdf_ini;
		$content .= $this->title;
		$content .= $this->ps_title;
		$content .= $this->table_start;

		$content .= $this->addTableHeaders($col_name_array);

		foreach ($data_matriz as $key => $row) {
			if (!empty($no_incluir)) {
				$content .= $this->addRow($row,$no_incluir);
				$imgs = explode(',', $row['imagenes']);
				$this->imagenes_reducidas($imgs);
				$content .= $this->image_row($imgs);
			}else{
				$content .= $this->addRow($row);				
				$imgs = explode(',', $row['imagenes']);
				$content .= $this->image_row($imgs);
			}
				
		}
		
		$content .= $this->table_end;
		$content .= $this->pdf_end;
		//echo $content;

		$handle = fopen(DIR_LOGS.'export.log', 'w');
		fwrite($handle, $content);
		fclose($handle);

		$this->H2P->writeHTML($content);
		$this->H2P->Output($doc_name.".pdf");

	}*/

	public function create_big_pdf($col_name_array, $data_matriz, $doc_name,$limit,  $no_incluir = array())
	{
		ini_set("memory_limit","-1");
		header("Content-Type: application/pdf");

		header("Cache-Control: max-age=0");
		
		if ($limit>0) {
			$segmentos = array_chunk($data_matriz, $limit);
		}else{
			$segmentos = array(0=>$data_matriz);
		}
		

		foreach ($segmentos as $key => $piece) {
			$this->PDFTitle($doc_name);
			$content = $this->pdf_ini;
			$content .= $this->title;
			$content .= $this->ps_title;
			$content .= $this->table_start;

			$content .= $this->addTableHeaders($col_name_array);

			foreach ($piece as $key => $row) {
				if (!empty($no_incluir)) {
					$content .= $this->addRow($row,$no_incluir);
					$imgs = explode(',', $row['imagenes']);
					$this->imagenes_reducidas($imgs);
					$content .= $this->image_row($imgs);
				}else{
					$content .= $this->addRow($row);				
					$imgs = explode(',', $row['imagenes']);
					$content .= $this->image_row($imgs);
				}
					
			}
			
			$content .= $this->table_end;
			$content .= $this->pdf_end;

			//echo $content;
			
			$this->pdf_writer->load_html($content);
			$this->pdf_writer->render();
			//header("Content-Disposition: attachment; filename=". $doc_name.$key.".pdf");
			//file_put_contents(DIR_FILES."file".$doc_name.".pdf", $this->pdf_writer->output());
			file_put_contents("php://output", $this->pdf_writer->output());
			//$this->pdf_writer->stream($doc_name.'.pdf');
		}
		
	}

	public function imagenes_reducidas($imagenes)
	{
		$peques = array();
		$ancho_reducida = 150;
		$alto_reducida = 150;
		foreach ($imagenes as $key => $archivo) {
			$origen  = $this->img_directory.$archivo;
			$destino = DIR_REDUCIDAS.$archivo;
			$this->redim($origen, $destino, $ancho_reducida, $alto_reducida);
		}
	}

	public function pdf_with_chart($col_name_array, $data_matriz, $doc_name, $no_incluir = array())
	{	
		$this->PDFTitle($doc_name);

		$content = $this->pdf_ini;
		$content .= $this->title;
		$content .= $this->ps_title;


		$content .= $this->chart;
		$content .= $this->table_start;
		$content .= $this->addTableHeaders($col_name_array);

		foreach ($data_matriz as $key => $row) {
			if (!empty($no_incluir)) {
				$content .= $this->addRow($row,$no_incluir);
			}else{
				$content .= $this->addRow($row);
			}
				
		}
		
		$content .= $this->table_end;
		$content .= $this->pdf_end;

		$this->pdf_writer->load_html($content);
		$this->pdf_writer->render();
		$this->pdf_writer->stream($doc_name.'.pdf');
			
	}

	public function insert_chart($src, $height, $width='')
	{
		$width = empty($width)? $height : $width;
		$this->chart = "<div style='text-align:center;'>
						<img src='".$src."' style='height: ".$height."px; width:".$width."px;margin-left:auto, margin-right:auto;display:block;' />
						</div>";
	}

	public function image_row($imgs)
	{

		$str_row = '';
		if (json_encode($imgs)!='[""]') {
			$cont = 0;

			$str_row = "<tr >";
			$str_row .= "<td >";
			$str_row .="<span >Fotografías</span>";
			$str_row .= '</td>';
			$str_row .= "<td colspan='14'>";
			$str_row .= "<table class='sin-bordes' border='0' style='border-style:solid; background-color:transparent; border-color:transparent;' >";
			$str_row .= "<tr>";
			
			/* restringue el arreglo a solo 20 imagenes */
			$img_exportar = count($imgs) > 20 ? array_slice($imgs, 0, 20) : $imgs;

			foreach ($img_exportar as $key => $imagen) {

				if ($cont > 4) {
					$str_row .= "</tr><tr>";
					$cont = 0;
				}
				//se revizar la existencia de la ruta y que esta pertenesca a un archivo
				if (file_exists($this->mini_img_dir.$imagen) && is_file($this->mini_img_dir.$imagen)) {
					$str_row .= "<td><img src='".$this->mini_img_dir.$imagen."' class='image' /></td>";		
					$cont++;
				}
			}
			$str_row .= '</tr>';
			$str_row .= '</table>';
			$str_row .= '</td>';
			$str_row .= '</tr>';			
		}

		return $str_row;
		
	}

	public function set_img_directory($folder)
	{
		$this->img_directory = $folder;
	}


	public function PDFTitle($title)
	{
		$this->title = $title;
	}

	public function set_size_title($px)
	{
		$this->size_title = $px;
	}

	public function set_paper($size, $orientation = 'portrait')
	{
		$this->pdf_writer->set_paper($size, $orientation);
	}

	public function addTableHeaders($headers)
	{	
		$str_header = '<tr>';
		foreach ($headers as $key => $col_name) {
			$str_header .= '<th>' . $col_name . '</th>';
		}
		$str_header .= '</tr>';

		return $str_header;
	}

	public function addRow($row,$no_incluir = array())
	{
		$str_row = '<tr>';
		foreach ($row as $key => $cell_value) {
			if (!empty($no_incluir)) {
				if (!in_array($key, $no_incluir)) {
					if ($key == 'longitud') {
						$str_row .= "<td class='lon'>" . $cell_value . '</td>';
					}else{
						$str_row .= '<td>' . $cell_value . '</td>';
					}					
				}	
			}else{
				if ($key == 'longitud') {
					$str_row .= "<td class='lon'>" . $cell_value . '</td>';
				}else{
					$str_row .= '<td>' . $cell_value . '</td>';
				}
			}			
		}
		$str_row .= '</tr>';

		return $str_row;
	}

	function redim($ruta1,$ruta2,$ancho,$alto) 
    { 
	    # se obtene la dimension y tipo de imagen
	    if (file_exists($ruta1) && is_file($ruta1)) {
	    		

	    $datos=getimagesize ($ruta1); 

	    $ancho_orig = $datos[0]; # Anchura de la imagen original 
	    $alto_orig = $datos[1];    # Altura de la imagen original 
	    $tipo = $datos[2]; 

	    if ($tipo==1){ # GIF 
	    	if (function_exists("imagecreatefromgif")) 
	    		$img = imagecreatefromgif($ruta1); 
	    	else 
	    		return false; 
	    } 
	    else if ($tipo==2){ # JPG 
	    	if (function_exists("imagecreatefromjpeg")) 
	    		$img = imagecreatefromjpeg($ruta1); 
	    	else 
	    		return false; 
	    } 
	    else if ($tipo==3){ # PNG 
	    	if (function_exists("imagecreatefrompng")) 
	    		$img = imagecreatefrompng($ruta1); 
	    	else 
	    		return false; 
	    } 

	    # Se calculan las nuevas dimensiones de la imagen 
	    if ($ancho_orig>$alto_orig) 
	    { 
	    	$ancho_dest=$ancho; 
	    	$alto_dest=($ancho_dest/$ancho_orig)*$alto_orig; 
	    } 
	    else 
	    { 
	    	$alto_dest=$alto; 
	    	$ancho_dest=($alto_dest/$alto_orig)*$ancho_orig; 
	    } 

	    // imagecreatetruecolor, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
	    $img2=@imagecreatetruecolor($ancho_dest,$alto_dest) or $img2=imagecreate($ancho_dest,$alto_dest); 

	    // Redimensionar 
	    // imagecopyresampled, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
	    @imagecopyresampled($img2,$img,0,0,0,0,$ancho_dest,$alto_dest,$ancho_orig,$alto_orig) or imagecopyresized($img2,$img,0,0,0,0,$ancho_dest,$alto_dest,$ancho_orig,$alto_orig); 

	    // Crear fichero nuevo, según extensión. 
	    if ($tipo==1) // GIF 
	    if (function_exists("imagegif")) 
	    	imagegif($img2, $ruta2); 
	    else 
	    	return false; 

	    if ($tipo==2) // JPG 
	    if (function_exists("imagejpeg")) 
	    	imagejpeg($img2, $ruta2); 
	    else 
	    	return false; 

	    if ($tipo==3)  // PNG 
	    if (function_exists("imagepng")) 
	    	imagepng($img2, $ruta2); 
	    else 
	    	return false; 
	}
	return true; 
	} 
}