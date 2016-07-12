<?php

    $data = array();
    $data['estado'] = true;

    # Comprobamos el token
        /*
        session_start();
        
        if(md5($_POST['_token']) != $_SESSION['idreg']){
            #No puede regsitrarse
            $data['error']  = 'No puede regsitrarse, token no valido!';
            $data['estado'] = false;
        }
        */


    # Comprobamos los datos enviados

    if(!isset($_POST['username']) OR empty($_POST['username'])){
        $data['error']  = 'Variable user vacía.';
        $data['estado'] = false;
    }else{
        $username = $_POST['username'];
    }

    if(!isset($_POST['nombre']) OR empty($_POST['nombre'])){
        $data['error']  = 'Variable nombre vacía.';
        $data['estado'] = false;
    }else{
        $nombre = $_POST['nombre'];
    }

    if(!isset($_POST['apellidos']) OR empty($_POST['apellidos'])){
        $data['error']  = 'Variable apellidos vacía.';
        $data['estado'] = false;
    }else{
        $apellidos = $_POST['apellidos'];
    }

    if(!isset($_POST['fono']) OR empty($_POST['fono'])){
        $data['error']  = 'Variable teléfono vacía.';
        $data['estado'] = false;
    }else{
        $fono = $_POST['fono'];
    }

    if(!isset($_POST['email']) OR empty($_POST['email'])){
        $data['error']  = 'Variable email vacía.';
        $data['estado'] = false;
    }else{
        $email = $_POST['email'];
    }

    if(!isset($_POST['pass']) OR empty($_POST['pass'])){
        $data['error']  = 'Variable pass vacía.';
        $data['estado'] = false;
    }else{
        $pass = $_POST['pass'];
    }


    if($data['estado']){

        require_once "../Models/Cliente.php";
        require_once "../Models/User.php"; 
        require_once "../Models/CuentaCorriente.php"; 

        # creamos un cliente
        $cliente = new Cliente();

        $cliente->setName($nombre);
        $cliente->setEmail($email);
        $cliente->setPhone($fono);

        if(!$cliente->create()){
            $data['error']  = $cliente->error;
            $data['estado'] = false;
        }else{           

            # si el cliente fue creado con exito, creamos un usuario correspondiente

            $user = new User();

            $user->setUser($username);
            $user->setName($nombre);
            $user->setLastName($apellidos);
            $user->setEmail($email);
            $user->setPass($pass);
            $user->setClient($cliente->getId());
            

            if(!$user->create()){
                $data['error']  = $user->error;
                $data['estado'] = false;

                # si el usuario falla, debemos eliminar el cliente !
                if(!$cliente->delete()){
                    $data['error'] .= "<br>".$cliente->error;
                }
            }else{

                # cuando tengamos ambos creados (cliente y usuario), debemos asignarle creditos al cliente
                $cuenta = new CuentaCorriente();

                $cuenta->setSaldoEmail(15);
                $cuenta->setSaldoSms(10);
                $cuenta->setCliente($cliente->getId());

                if(!$cuenta->cargarSaldo()){

                    $data['error']  = $cuenta->error;
                    $data['estado'] = false;

                    # Eliminar cliente
                    $cliente->delete();
                    # Eliminar usuario
                    $user->delete();
                }else{

                    #enviar email

                    require __DIR__.'/mail.php';

                    $envio = new Mail();

                    $envio->mail = $email;
                    $envio->nombre_cliente = $nombre;
                    $envio->token = $cliente->token;
                    $envio->sendMail();

                }
            }
        }

    }



    echo json_encode($data, JSON_PRETTY_PRINT);


    function getMail()
    {

        $html = "favor confirma tu email con este link <a href='connectus.des/confirm/tokendeconfirmacion'>Confirma!</a>";

        return $html;
    }
?>