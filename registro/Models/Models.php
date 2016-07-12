<?php  
require_once __DIR__."/../../admin/config.php"; 

class Models 
{ 
    protected $_db;
    protected $_where = ' WHERE 1=1 ';

    public function __construct() 
    { 
        $this->_db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE); 

        if ( $this->_db->connect_errno ) 
        { 
            echo "Fallo al conectar a MySQL: ". $this->_db->connect_error; 
            return;     
        } 

        $this->_db->set_charset('utf-8'); 
    }


    public function where($campo, $condicion, $busqueda)
    {

        $this->_where .= " AND ( ".$campo." ".$condicion." '".$busqueda."') ";

    }

    public function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
           
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
       
        return $_SERVER['REMOTE_ADDR'];
    }

    protected function use_db($database)
    {   
        $sql = "USE ".$database;
        $this->_db->query($sql);

        if($this->_db->error){
            return false;
        }else{
            return true;
        }
    }




} 
?> 