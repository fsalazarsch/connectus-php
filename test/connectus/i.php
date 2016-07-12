<?php


    require 'libs/mandrill/Mandrill.php';

    $mandrill = new Mandrill('mAE8PAPvxN-qCzL7JucPuA');

    // send transactional email
    try{ 
        $message = array(
            'subject' => 'Hello, how are you?',
            'text' => 'This is a text message',
            'from_email' => 'ssilvac@connectus.cl',

            'to' => array(
                array(
                    'email' => 'sebasilvac88@gmail.com',
                    'name' => 'Sebastián Silva'
                )
            ), 

            'google_analytics_domains' => array('connectus.cl'),
            'google_analytics_campaign' => 'ssilvac@connectus.cl',
            'metadata' => array(
                'website' => 'connectus.cl',
                'X-MC-TrackingDomain' => 'connectus.cl'
            ),
            'signing_domain' => 'connectus.cl',
            'tracking_domain' => 'connectus.cl',
            'return_path_domain' => 'connectus.cl'

        );

        

        $result = $mandrill->messages->send($message);

    } catch(Mandrill_Error $e) { 

        echo "<pre>";
        echo 'An error occurred while sending this email, please try again or contact us.';

        print_r($e);
    }
