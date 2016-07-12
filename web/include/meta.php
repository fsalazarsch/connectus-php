<?php
    
    # acá se configurará las etiquetas meta de cada página
    # se resivira una variable $pag, donde indicamos la página de la cual se proviene !

    $keywords   = 'SMS, E-mail, Envíos masivos, Marketing, Servicios de Comunicación, Campañas de Email';

    switch ($pag) {

        case 'index':
            $titulo     = 'Connectus | Expertos en Servicios de Comunicación';
            $desc       = 'Envíos masivos de SMS y E-mail, Llega a tus contactos de forma instantánea a través de uno de los medios de marketing mas costo-efectivo que existe.';
        break;

        case 'sms':
            $titulo     = 'Connectus | SMS';
            $desc       = 'Envíos masivos de mensaje de textos, Llega a tus contactos de forma instantánea y efectiva donde quiera que estén.';
        break;

        case 'email':
            $titulo     = 'Connectus | Expertos en Servicios de Comunicación';
            $desc       = 'Envíos masivos de Email, sin limites de contenido y fáciles de compartir mediante uno de los medios de marketing más costo-efectivo que existe.';
        break;

        case 'plataforma':
            $titulo     = 'Connectus | Expertos en Servicios de Comunicación';
            $desc       = 'Administra tus envíos en un solo lugar, desarrollado con los más altos estándares de tecnología, facilitando la gestión de bases de datos, mensajes y reportes.';
        break;

        case 'contacto':
            $titulo     = 'Connectus | Expertos en Servicios de Comunicación';
            $desc       = 'Comunícate con nosotros, nuestros ejecutivos se pondrán en contacto contigo durante el transcurso del día.';
        break;
        

    }
    

?>
<meta charset='utf-8'>
<title><?php echo $titulo; ?></title>

<meta name="robots" content="index, follow" />
<meta name="Description"    content="<?php echo $desc;      ?>" />
<meta name="Keywords"       content="<?php echo $keywords;  ?>" />


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- META Verificación Sitio Google -->
<meta name="google-site-verification" content="vaLnG1S1lVkJ9z3DINwk93vP-zWbbApTfQDABdV8GUY" />