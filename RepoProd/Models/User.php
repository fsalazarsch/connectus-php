<?php  
    require_once "Models.php";

    class User extends Models
    {

        protected $id;

        public $user;
        public $name;
        public $lastname;
        public $email;
        public $id_client;
        protected $pass;
        public $error;

        public function setPass($pass){
            $this->pass = md5($pass);
        }

        public function setUser($user){
            $this->user = $this->_db->real_escape_string($user);
            $this->user = utf8_decode($this->user);
        }

        public function setName($name){
            $this->name = $this->_db->real_escape_string($name);
            $this->name = utf8_decode($this->name);
        }

        public function setLastName($lastname){
            $this->lastname = $this->_db->real_escape_string($lastname);
            $this->lastname = utf8_decode($this->lastname);
        }

        public function setEmail($email){
            $this->email = $this->_db->real_escape_string($email);
            $this->email = utf8_decode($this->email);
        }

        public function setClient($id_client){
            $this->id_client = $this->_db->real_escape_string($id_client);
        }

        public function setId($id){
            $this->id = (int)$id;
        }

        public function getId(){
            return $this->id;
        }



        public function getAll() 
        { 
            $result = $this->_db->query('SELECT * FROM assert_user'); 
             
            $users = $result->fetch_all(MYSQLI_ASSOC); 
             
            return $users; 
        } 

        public function getByID()
        {

            $sql = 'SELECT * FROM assert_user WHERE user_id = '.$this->id;


            $result = $this->_db->query($sql); 
            $users = $result->fetch_object(); 
            return $users; 
        }

        public function getBy()
        {

            $sql = 'SELECT * FROM assert_user ';
            $sql .= $this->_where;

            $result = $this->_db->query($sql); 
            $users = $result->fetch_object(); 
            return $users; 
        }

        public function create()
        {

            if($this->comprueba_username($this->user)){
                $this->error = "Usuario ya se encuentra registrado!";
                return false;
            }

            if($this->comprueba_email($this->email)){
                $this->error = "Email ya se encuentra registrado!";
                return false;
            }

            $salt   = $this->_db->real_escape_string($salt = substr(md5(uniqid(rand(), true)), 0, 9));
            $image  = '';
            $code   = '';
            $ip     = $this->getRealIP();
            $token  = md5(uniqid(rand(), true));


            $sql = "INSERT INTO assert_user (user_group_id, username, password, salt, firstname, lastname, email, image, code, ip, status, date_added, id_empresa, token, user_prueba)
                    VALUES (10, 
                            '".$this->user."',  
                            '".$this->pass."',  
                            '".$salt."',
                            '".$this->name."',
                            '".$this->lastname."',
                            '".$this->email."',
                            '".$image."',
                            '".$code."',
                            '".$ip."',
                            1,
                            NOW(),
                            '".$this->id_client."',
                            '".$token."',
                            1
                            )";
            
            $result = $this->_db->query($sql);


            if($this->_db->error){
                $this->error = "Problemas al ejecutar la consulta.";
                return false;
            }else{
                $this->setId($this->_db->insert_id);
                return true;
            }


        }


        public function comprueba_username($username)
        {

            $sql = 'SELECT user_id FROM assert_user WHERE username = "'.$this->_db->real_escape_string($username).'" ';

            $result = $this->_db->query($sql); 
            $users = $result->fetch_object();

            if(count($users)>0){
                return true;
            }else{
                return false;
            }
        }


        public function comprueba_email($email)
        {

            $email = $this->_db->real_escape_string($email);

            if( filter_var($email, FILTER_VALIDATE_EMAIL) ){

                $sql = 'SELECT user_id FROM assert_user WHERE email = "'.$email.'" ';

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

            $sql = "DELETE FROM assert_user WHERE user_id = ".$this->id;

            $result = $this->_db->query($sql);

            if($this->_db->error){
                $this->error = "Problemas al eliminar usuario.";
                return false;
            }else{
                return true;
            }
        }

        public function __construct(){ 
            parent::__construct();
        } 
    } 
?> 