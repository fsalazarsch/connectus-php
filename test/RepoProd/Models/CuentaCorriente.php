<?php  
    require_once "Models.php"; 

    class CuentaCorriente extends Models 
    {     
        
        protected   $id;
        protected   $cliente;
        protected   $saldo_email    = 0;
        protected   $saldo_sms      = 0;
        public      $error;

        
        public function setSaldoEmail($saldo)
        {
            $this->saldo_email = (int)$saldo;
        }

        public function setSaldoSms($saldo)
        {
            $this->saldo_sms = (int)$saldo;
        }

        public function setCliente($cliente)
        {
            $this->cliente = (int)$cliente;
        }

        public function cargarSaldo()
        {   

            if(empty($this->cliente)){
                $this->error = "No se ha seleccionado un cliente.";
                return false;
            }

            if( ($this->saldo_email == 0) and ($this->saldo_sms == 0)){
                $this->error = "no hay saldo para cargar.";
                return false;
            }else{

                $sql = "INSERT INTO cuenta_corriente SET 
                            id_conector_sms = 1,
                            id_conector_mail = 3,
                            saldo_credito = ".($this->saldo_email + $this->saldo_sms).",
                            saldo_mail = ".$this->saldo_email.",
                            saldo_sms   = ".$this->saldo_sms.",
                            id_empresa  = ".$this->cliente;

                $this->_db->query($sql);

                if($this->_db->error){

                    $this->error = "Problemas al cargar saldos. ".$this->_db->error;
                    return false;

                }else{
                    return true;
                }

            }     

        }



        public function __construct() 
        { 
            parent::__construct();
            $this->use_db(DB_DATABASE_CONNECTUS);
        }
    }