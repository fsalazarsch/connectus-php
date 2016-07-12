<?php 

    class Mail
    {
        protected   $sendgrid;
        public      $mail;
        public      $nombre_cliente;
        public      $link_confirmacion;
        public      $token;

        function __construct()
        {
            require_once __DIR__.'/../../connectus/libs/sendgrid/sendgrid-php.php';
            $this->sendgrid = new SendGrid('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');
        }

        public function sendMail()
        {
            $nombre_remitente       = "Connectus Chile";
            $email_remitente        = "registro@connectus.cl";
            $asunto                 = "¡Bienvenido a Connectus!";
            $nombre_envio           = 'Email de confirmación a '.$this->mail;

            $SGemail = new SendGrid\Email();
    
            $SGemail
                ->addTo($this->mail)
                ->setFrom($email_remitente)
                ->setFromName($nombre_remitente)
                ->setSubject($asunto)
                ->setHtml($this->getMail())
                ->addCategory('Registro usuarios');

                
            $result = $this->sendgrid->send($SGemail);
        }

        public function getMail()
        {
            $html = file_get_contents(__DIR__.'/../views/mailregistro.html');

            $html = str_replace('{{name_client}}', $this->nombre_cliente, $html);
            $html = str_replace('{{link_to_confirm}}', 'http://connectus.cl/registro/confirm.php?correo='.$this->mail.'&token='.$this->token, $html);

            return $html;
        }
    }