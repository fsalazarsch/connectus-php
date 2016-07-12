<?php

class database {

  private static $db_host = 'localhost'; 
	private static $db_user = 'connectu_test'; 
	private static $db_pass = 'Connectus.2016;'; 
	private static $db_name1 = 'connectu_adm_test';
  private static $db_name2 = 'connectu_connecta_test';

	public $conexion;

 //método para conectar a la BD
   public function conectar($bd)
   {
    if(!isset($this->conexion))
    {
      try {

          $this->conexion = new PDO("mysql:host=".self::$db_host.";dbname=".$bd, self::$db_user, self::$db_pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
          $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      } catch(PDOException $e) {

          die("ERROR al Conectar a la BD - CAUSA: " . $e->getMessage());

      }

      

    }
   } 

  // Método para realizar una consulta 
   public function consulta($sql)
   {
      $result = $this->conexion->query($sql);

      if(!$result)
      {
          die("ERROR al ejecutar query:".$sql." - CAUSA: ". $this->conexion->errorInfo());
          return null;
      } else {
          return $result;  
      }

   }


   // Método para ejecutar una sentencia con los parámetros dentro de un arreglo
   public function ejecutarConArreglo($sql, $array)
   {
      
      try {

          $sql_exec = $this->conexion->prepare($sql);
          $sql_exec ->execute($array);

          return $sql_exec;

      }

      catch(PDOException $e) {

          die("ERROR al ejecutar sentencia:".$sql." - CAUSA: ".$e->getMessage());
          return 0;

      }
    }


    // Método para ejecutar una sentencia
   public function ejecutar($sql)
   {
      
      try {

          $sql_exec = $this->conexion->prepare($sql);
          $sql_exec ->execute();

          return $sql_exec;

      }

      catch(PDOException $e) {

          die("ERROR al ejecutar sentencia:".$sql." - CAUSA: ".$e->getMessage());
          return 0;

      }
    }

   
 /*
 //METODO PARA CONTAR EL NUMERO DE RESULTADOS
 function numero_de_filas($result){
  if(!is_resource($result)) return false;
  return mysql_num_rows($result);
 }

 //METODO PARA CREAR ARRAY DESDE UNA CONSULTA
 function fetch_array($result){
  if(!is_resource($result)) return false;
   return mysql_fetch_array($result);
 }
 
 //METODO PARA CREAR ARRAY DESDE UNA CONSULTA
 function fetch_assoc($result){
  if(!is_resource($result)) return false;
   return mysql_fetch_assoc($result);
 }
  */

  public function desconectar()
   {
      $this->conexion = null;
   }
 

}
?>