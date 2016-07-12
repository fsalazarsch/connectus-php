<?php

    $data = array();
    $data['estado'] = false;

    require_once "../Models/User.php"; 


    switch ($_POST['valida']) {

        case 'username':
            
            $user = new User();

            if($user->comprueba_username($_POST['username'])){
                $data['estado'] = true;
            }else{
                $data['estado'] = false;
            }
        break;

        case 'email':
            
            $user = new User();

            if($user->comprueba_email($_POST['email'])){

                $data['error'] = $user->error;
                $data['estado'] = true;
            }else{
                $data['estado'] = false;
            }
        break;
        
    }

    echo json_encode($data, JSON_PRETTY_PRINT);