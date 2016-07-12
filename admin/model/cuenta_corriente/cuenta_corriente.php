<?php
class ModelCuentaCorrienteCuentaCorriente extends Model{
    public function addCuentaCorriente($id_empresa){
        $aux_m = $this->get_default_connector('MAIL');
        $aux_s = $this->get_default_connector('SMS');

        $sms = $aux_s->row['id_conector'];
        $mail = $aux_m->row['id_conector'];
		
        $sql = "INSERT INTO cuenta_corriente SET saldo_credito = 0, id_empresa = " . $id_empresa . ", saldo_mail = 0, saldo_sms = 0" ;
        $sql .= " ,id_conector_mail = " . $mail; 
        $sql .= " ,id_conector_sms = " . $sms; 
        $sql .= " ,consumidos_sms  = 0";
        $sql .= " ,consumidos_mail = 0"; 

        $result = $this->admDB->query($sql);

		
        return $result->row;
    }

    public function setSaldo($nuevo_saldo, $id_empresa){
       // $this->connectusDB->query('UPDATE cuenta_corriente SET saldo_creditos = '. $nuevo_saldo .' WHERE id_empresa = ' . $id_empresa);
    }

    public function getCuentaEmpresa($id_empresa) {
        $result = $this->admDB->query($sql = "SELECT * FROM cuenta_corriente WHERE id_empresa = " . $id_empresa);
        return $result->row; 
    }

    public function updateCuenta($id_empresa, $saldo_carga, $saldo_mail, $saldo_sms ){
        
            //los valores ingresados por parametro son las cantiadades que se desean agregar; NO SE DEBEN REEMPLAZAR DIRECTAMENTE EN EL UPDATE.
            $cuenta_corriente = $this->getCuentaEmpresa($id_empresa);

            $sql="INSERT INTO compra SET cantidad_sms =" . $saldo_sms .", cantidad_mail = " . $saldo_mail . ", fecha = NOW(), valor = " . $saldo_carga .", id_cuenta_corriente =" . $cuenta_corriente['id_cuenta_corriente'] . ", cantidad_creditos=". ($saldo_sms + $saldo_mail);
            $this->admDB->query($sql);

            $mail_nuevo_total = ( $this->getSaldoEnviosMailHistorico($id_empresa) - $this->creditosConsumidosMail($id_empresa) );
            $sms_nuevo_total  = ( $this->getSaldoEnviosSmsHistorico($id_empresa) - $this->creditosConsumidosSms($id_empresa)   ) ;

            $saldo = $mail_nuevo_total + $sms_nuevo_total; 
            $consulta = "UPDATE cuenta_corriente SET saldo_credito = " . $saldo . ",saldo_mail=" . $mail_nuevo_total . ", saldo_sms=" . $sms_nuevo_total . "  WHERE id_empresa = " . $id_empresa;


            $result = $this->admDB->query($consulta);
            $aux = $result->num_rows;

            if ($aux > 0) {
                 if ($saldo_sms > 0) {
                    $this->restablecerSMS($id_empresa);
                }

                if ($saldo_mail > 0) {
                    $this->restablecerMail($id_empresa);
                }

                return true;
             }else{
                return false;
             }
        
    }

    public function getSaldoEnviosSmsHistorico($id_empresa)
    {
        $cuenta = $this->getCuentaEmpresa($id_empresa);

        $sql = "select sum(cantidad_sms) as 'SMS' from compra where id_cuenta_corriente = " . $cuenta['id_cuenta_corriente'];
        
        $result = $this->admDB->query($sql);
        if($result->num_rows > 0){
            return $result->row['SMS'];
        }else{
            return 0;
        }
        
    }

    public function getSaldoEnviosMailHistorico($id_empresa)
    {        
        $cuenta = $this->getCuentaEmpresa($id_empresa);

        $sql = "select sum(cantidad_mail) as 'MAIL' from compra where id_cuenta_corriente = " . $cuenta['id_cuenta_corriente'];
        
        $result = $this->admDB->query($sql);
        if($result->num_rows > 0){
            return $result->row['MAIL'];
        }else{
            return 0;
        }
    }

    public function restablecerMail($id_empresa){
        $this->admDB->query('UPDATE cuenta_corriente SET consumidos_mail = 0 WHERE id_empresa = ' . $id_empresa);
    }

    public function restablecerSMS($id_empresa) {
        $this->admDB->query('UPDATE cuenta_corriente SET consumidos_sms = 0 WHERE id_empresa = ' . $id_empresa);
    }

    public function creditosConsumidosSms($id_empresa) {
        $cuenta = $this->getCuentaEmpresa($id_empresa);
        $sql_total = "select sum(sms_consumidos) as 'consumidos' from consumo where id_cuenta_corriente = " . $cuenta['id_cuenta_corriente'];

        $aux       = $this->admDB->query($sql_total);
        $resultado = $aux->row['consumidos'];

        if (isset($resultado)) {
            if (!empty($resultado)) {
                return $resultado;
            }
        }else{
            return 0;
        } 
    }

    public function creditosConsumidosMail($id_empresa) {
        $cuenta = $this->getCuentaEmpresa($id_empresa);
        $sql_total = "select sum(mail_consumidos) as 'consumidos' from consumo where id_cuenta_corriente = " . $cuenta['id_cuenta_corriente'];
        $aux       = $this->admDB->query($sql_total);
       
        $resultado = $aux->row['consumidos'];

        if (isset($resultado)) {
            if (!empty($resultado)) {
                return $resultado;
            }
        }else{
            return 0;
        } 
    }

    public function get_default_connector($tipo){
        $sql = "SELECT id_conector FROM conector WHERE defecto = 1 and tipo = '" . $tipo . "'";
        $conector_default = $this->admDB->query($sql);

        return $conector_default;
    }

    public function get_conectores($tipo){
        $sql = "SELECT id_conector FROM conector WHERE tipo = '" . $tipo . "'";
        $conectores = $this->admDB->query($tipo);

        return $conectores;
    }

    public function creditosSmsRestantes($id_empresa) {
        $sql          = "SELECT ( saldo_sms - consumidos_sms ) AS disponibles FROM cuenta_corriente WHERE id_empresa = " . $id_empresa;
        $result       = $this->admDB->query($sql);
        $diferencia   = $result->row['disponibles'];
        
        return $diferencia; 
    }

    public function creditosMailRestantes($id_empresa) {
        $sql          = "SELECT ( saldo_mail - consumidos_mail ) AS disponibles FROM cuenta_corriente WHERE id_empresa = " . $id_empresa;
        $result       = $this->admDB->query($sql);
        $diferencia   = $result->row['disponibles'];
        
        return $diferencia; 
    }

}