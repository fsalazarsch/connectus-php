<?php 
class ModelDesignYoutube extends Model
{
	public function link($route = '')
	{
		$id_parametro = !empty($route) ? $route : 'default/video';

		$sql = "SELECT valor1 as link, valor2 as tipo FROM parametro WHERE id_parametro IN ('$id_parametro', 'default/video')" ;
		$result = $this->admDB->query($sql);

		if ($result->num_rows == 2) {
			foreach ($result->rows as $value) {
				if ($value['tipo'] != 'video/default') {
					$video = $value['link']; 
				}
			}
		}else {
			$video = $result->row['link'];
		}
		

		return $video;
	}
}