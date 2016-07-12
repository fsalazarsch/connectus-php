<?php	
	define('DB_DRIVER_CONNECTUS', 'mpdo');
	define('DB_HOSTNAME_CONNECTUS', 'localhost');
	define('DB_USERNAME_CONNECTUS', 'connectu_connect');
	define('DB_PASSWORD_CONNECTUS', 'cOnNectUs_05041977_.#');
	define('DB_DATABASE_CONNECTUS', 'connectu_adm_connectus');
	define('DB_PORT_CONNECTUS', '3306');
	define('DB_PREFIX_CONNECTUS', '');;
	
	$string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS;
	$conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);

	$handle = fopen('php://input','r');
	$jsonInput = fgets($handle);

	//$strs = 'city=Santiago&domain=sandbox016db1bbc60245e597370304333a73d8.mailgun.org&device-type=desktop&client-type=email+client&h=116a4b58c3190942576082df5ef2d0a7&region=12&client-name=Outlook+2010&user-agent=Mozilla%2F4.0+%28compatible%3B+MSIE+7.0%3B+Windows+NT+6.2%3B+WOW64%3B+Trident%2F7.0%3B+.NET4.0E%3B+.NET4.0C%3B+.NET+CLR+3.5.30729%3B+.NET+CLR+2.0.50727%3B+.NET+CLR+3.0.30729%3B+GWX%3AQUALIFIED%3B+MAMIJS%3B+Microsoft+Outlook+14.0.7155%3B+ms-office%3B+MSOffice+14%29&country=CL&client-os=Windows&tag=8d34201a5b85900908db6cae92723617&tag=nicolas.diaz%40assertsoft.cl&tag=connectusKey&ip=186.105.110.220&message-id=20150817232510.36739.64054%40sandbox016db1bbc60245e597370304333a73d8.mailgun.org&recipient=nicolas.diaz%40assertsoft.cl&event=opened&timestamp=1439855631&token=2a2ac38735e00a53ed6960f0413f4222c08732db341a7aba19&signature=39016d3f9574f4d9bb1eef7aa7057f167d8a23495213919ced5ba8d06c136536&body-plain=';
	
	$sss = str_ireplace('&', ',', rawurldecode(urldecode($jsonInput)));
	//$sss = str_ireplace('&', ',', rawurldecode(urldecode($strs)));
	$sss = str_ireplace('=', ':', $sss);
	
	$json = '{' . $sss . '}';

	$a = preg_replace('/(,|\{)[ \t\n]*(\w+)[ ]*:[ ]*/','$1"$2":',$json);
	$a = preg_replace('/":\'?([^\[\]\{\}]*?)\'?[ \n\t]*(,"|\}$|\]$|\}\]|\]\}|\}|\])/','":"$1"$2',$a);

    $response =  json_decode($a, true);		


    $sql = "UPDATE detalle_envio set estado = '".$response['event']."' WHERE id_respuesta_servidor = '".$response['tag']."' ";
 
	$stmt = $conexion->prepare($sql);
	$stmt->execute();

?>