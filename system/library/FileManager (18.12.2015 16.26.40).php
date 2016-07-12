<?php 
/**
* Exportar archivos
*/
include_once dirname(__FILE__) . '/libreriaExcel/PHPExcel.php'; 
include_once dirname(__FILE__) . '/libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc.php'; 

class FileManager
{
	private $excel_writer;
	private $php_excel;
	private $pdf_writer;

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

	                    h1{
	                        font-family:Arial, Helvetica, sans-serif;
	                        color:white;
	                        font-size: 28px;
	                        background:#000;
	                        margin:0px auto;
	                        border:#ccc 1px solid;
	                        padding: 5px;
	                        text-align: center;
	                        width: 90%;

	                    }

	                    table {
	                        font-family:Arial, Helvetica, sans-serif;
	                        color:#666;
	                        font-size:12px;
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
	                    }

						.image {
	                        padding:3px;
	                        text-align: center;
	                        height: 200px;
	                        width: 200px;
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

	function __construct()
	{
		$this->php_excel = new PHPExcel();
		$this->writer = new PHPExcel_Writer_Excel2007($this->php_excel);
		$this->pdf_writer = new DOMPDF();
	}

	/* Inicio trabajos con EXCEL */
	public function excel($col_name_array, $data_matriz, $doc_name, $no_incluir = array())
	{	
		$this->php_excel->setActiveSheetIndex(0);

		//nombre de la hoja
		$this->php_excel->getActiveSheet()->setTitle($doc_name);

		//escribiendo los encabezados de la tabla de datos
		$index = $this->startIndex();
		foreach ($col_name_array as $key => $col_name) {
			$this->php_excel->getActiveSheet()->setCellValue($index, $col_name);
			$this->php_excel->getActiveSheet()->getColumnDimension($this->letter())->setAutoSize(true);
			$index = $this->nextColumn(true);
		}

		//escribiendo los registros
		foreach ($data_matriz as $key => $row) {
			$data_index = $this->nextRow(true);
			foreach ($row as $nom_col => $cell_value) {
				if (!empty($no_incluir)) {
					if (!in_array($nom_col, $no_incluir)) {
						$this->php_excel->getActiveSheet()->setCellValue($data_index, $cell_value);
						$this->php_excel->getActiveSheet()->getColumnDimension($this->letter())->setAutoSize(true);
						$data_index = $this->nextColumn(true);
					}
				}else{
					$this->php_excel->getActiveSheet()->setCellValue($data_index, $cell_value);
					$this->php_excel->getActiveSheet()->getColumnDimension($this->letter())->setAutoSize(true);
					$data_index = $this->nextColumn(true);
				}
				
			}
				
		}

		$filename = $doc_name. '.xlsx';
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename='". $filename."'");
		header("Cache-Control: max-age=0");

		$this->excel_writer = new PHPExcel_Writer_Excel2007($this->php_excel);
		$this->excel_writer->save("php://output");
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
			return chr($this->letter_gen);
		}else{
			return chr($this->letter_gen);
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

		$this->pdf_writer->load_html($content);
		$this->pdf_writer->render();
		$this->pdf_writer->stream($doc_name.'.pdf');
			
	}

	public function pdf_with_images($col_name_array, $data_matriz, $doc_name, $no_incluir = array())
	{	
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
				$content .= $this->image_row($imgs);
			}else{
				$content .= $this->addRow($row);				
				$imgs = explode(',', $row['imagenes']);
				$content .= $this->image_row($imgs);
			}
				
		}
		
		$content .= $this->table_end;
		$content .= $this->pdf_end;

		
		$this->pdf_writer->load_html($content);
		$this->pdf_writer->render();
		$this->pdf_writer->stream($doc_name.'.pdf');
			
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
		;
		$str_row = '';
		if (json_encode($imgs)!='[""]') {
			$cantidad = count($imgs);
			$cont = 0;

			$str_row = "<tr >";
			$str_row .= "<td style='width:40px;'>";
			$str_row .="Fotografías";
			$str_row .= '</td>';
			$str_row .= "<td colspan='12'>";
			foreach ($imgs as $key => $imagen) {
				//echo "string";
				if ($cont >= 4) {
					$str_row .= "<br>";
					$cont = 0;
				}
				$str_row .= "<img src='".$this->img_directory.$imagen."' class='image' />";		
				$cont++;
			}
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
					$str_row .= '<td>' . $cell_value . '</td>';
				}	
			}else{
				$str_row .= '<td>' . $cell_value . '</td>';
			}			
		}
		$str_row .= '</tr>';

		return $str_row;
	}

	
}