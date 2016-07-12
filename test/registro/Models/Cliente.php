<?php  
    require_once "Models.php"; 

    class Cliente extends Models 
    {     
        
        protected $id;
        public $name;
        public $email;
        public $phone;
        public $token;
        public $error;


        public function setName($name){
            $this->name = $this->_db->real_escape_string($name);
            $this->name = utf8_decode($this->name);
        }

        public function setEmail($email){
            $this->email = $this->_db->real_escape_string($email);
            $this->email = utf8_decode($this->email);
            $this->token = md5(date('Y-m-d')."-".strrev($this->email));
        }

        public function setPhone($phone){
            $this->phone = $this->_db->real_escape_string($phone);
            $this->phone = utf8_encode($this->phone);
        }

        public function setId($id)
        {
            $this->id = (int)$id;
        }

        public function getId(){
            return $this->id;
        }



        public function create()
        {

            if($this->comprueba_email($this->email)){
                $this->error = "Email ya se encuentra registrado!";
                return false;
            }

            
            $sql = "INSERT INTO assert_customer SET 
                        customer_group_id = 2, 
                        firstname = '" . $this->name . "',
                        razon_social ='" . $this->name . "',
                        rut_empresa='', 
                        email = '" . $this->email . "', 
                        telephone = '".$this->phone."', 
                        token = '".$this->token."',
                        fax = '', 
                        custom_field = '', 
                        newsletter = 0, 
                        status = 1, 
                        approved = 1, 
                        safe = 0, 
                        ip='',
                        date_added = NOW()";

            $result = $this->_db->query($sql);

            if($this->_db->error){
                $this->error = "Problemas al crear cliente.<br>".$this->_db->error;
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

        public function confirm($token = '')
        {

            if(!empty($token)){

                $sql = 'SELECT customer_id FROM assert_customer WHERE email = "'.$this->email.'" AND token = "'.$token.'" ';

                $result     = $this->_db->query($sql); 
                $cliente    = $result->fetch_object();

                if(count($cliente) > 0)
                {
                    # si existe
                    # se procede a eliminar token

                    $this->id = $cliente->customer_id;

                    if(!$this->eliminarToken())
                    {
                        $this->error = "Problemas al actualizar cliente.";
                        return false;
                    }else{
                        return true;
                    }
                   
                    
                }else{
                    $this->error = "No hay coincidencia.";
                    return false;
                }

            }else{
                $this->error = "Token vacío.";
                return false;
            }
        }

        public function eliminarToken()
        {

            if(empty($this->id)){
                $this->error = "No se ha definido el Id.";
                return false;
            }

            $sql = "UPDATE assert_customer SET token = '' WHERE customer_id = ".$this->id;

            $result = $this->_db->query($sql);

            if($this->_db->error){
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