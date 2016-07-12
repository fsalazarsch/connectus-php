<?php  
    require_once "Models.php"; 

    class Cliente extends Models 
    {     
        
        protected $id;
        public $name;
        public $tipo;
        public $email;
        public $error;


        public function setName($name){
            $this->name = $this->_db->real_escape_string($name);
            $this->name = utf8_decode($this->name);
        }

        public function setTipoCliente($tipo){
            $this->tipo = $this->_db->real_escape_string($tipo);
            $this->tipo = utf8_decode($this->tipo);
        }

        public function setEmail($email){
            $this->email = $this->_db->real_escape_string($email);
            $this->email = utf8_decode($this->email);
        }

        public function setId($id)
        {
            $this->id = (int)$id;
        }

        public function getId(){
            return $this->id;
        }


        public function getInfoById(){

            # Producción
            $this->use_db('connectu_connecta');

            # Testing
            //$this->use_db('connectu_connecta_test');

            $sql = "SELECT c.*, g.name as tipo_cliente
                    FROM assert_customer c 
                    INNER JOIN assert_customer_group_description g ON c.customer_group_id = g.customer_group_id 
                    WHERE c.customer_id = ".$this->id;


            $result = $this->_db->query($sql);

            if($result->num_rows > 0){

                $obj =  $result->fetch_object();

                $this->setName($obj->firstname);
                $this->setTipoCliente($obj->tipo_cliente);

            }else{
                return false;
            }

        }

        public function getAll()
        {

            # Producción
            $this->use_db('connectu_connecta');

            # Testing
            //$this->use_db('connectu_connecta_test');

            $sql = "SELECT c.*, g.name 
                    FROM assert_customer c
                    INNER JOIN assert_customer_group_description g ON c.customer_group_id = g.customer_group_id 
                    WHERE c.status = 1 ";


            $result = $this->_db->query($sql);

            if($result->num_rows > 0){

                return $result;
            }else{
                return false;
            }

        }

        public function create()
        {

            if($this->comprueba_email($this->email)){
                $this->error = "Email ya se encuentra registrado!";
                return false;
            }

            $sql = "INSERT INTO assert_customer SET 
                        customer_group_id = 1, 
                        firstname = '" . $this->name . "',
                        razon_social ='" . $this->name . "',
                        rut_empresa='', 
                        email = '" . $this->email . "', 
                        telephone = '', 
                        fax = '', 
                        custom_field = '', 
                        newsletter = 0, 
                        status = 1, 
                        approved = 1, 
                        safe = 0, 
                        ip='',
                        token='',
                        date_added = NOW()";

            $result = $this->_db->query($sql);

            if($this->_db->error){
                $this->error = "Problemas al ejecutar la consulta.";
                return false;
            }else{
                $this->setId($this->_db->insert_id);
                unset($this->error);
                return true;
            }
        }


        public function comprueba_email($email)
        {

            $email = $this->_db->real_escape_string($email);

            if( filter_var($email, FILTER_VALIDATE_EMAIL) ){

                $sql = 'SELECT customer_id FROM assert_customer WHERE email = "'.$email.'" ';

                $result = $this->_db->query($sql); 
                $users = $result->fetch_object();

                if(count($users)>0){
                    $this->error = "Email ya se encuentra registrado.";
                    return true;
                }else{
                    return false;
                }

            }else{
                $this->error = "Email invalido.";
                return true;
            }

            
        }

        public function delete()
        {

            if(empty($this->id)){
                $this->error = "No se ha definido el Id.";
                return false;
            }

            $sql = "DELETE FROM assert_customer WHERE customer_id = ".$this->id;

            $result = $this->_db->query($sql);

            if($this->_db->error){
                $this->error = "Problemas al eliminar cliente.";
                return false;
            }else{
                return true;
            }
        }

        public function __construct() 
        { 
            parent::__construct(); 
        } 
    } 
?> 