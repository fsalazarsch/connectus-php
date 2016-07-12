<?php
    
    # acá se configurará las etiquetas meta de cada página
    # se resivira una variable $pag, donde indicamos la página de la cual se proviene !

    $keywords   = 'SMS, Email, Mail, Envíos, Masivos, Campañas, Chile, Marketing';

    switch ($pag) {

        case 'index':
            $titulo     = 'Expertos en envíos de mail y sms masivos';
            $desc       = 'Envíos masivos de SMS y Mail, Prueba ahora nuestra plataforma web de forma gratuita y confía en nosotros tus futuras campañas.';
        break;

        case 'sms':
            $titulo     = 'SMS Masivo';
            $desc       = 'Envíos masivos de mensaje de textos, Llega a tus contactos de forma instantánea y efectiva donde quiera que estén.';
        break;

        case 'email':
            $titulo     = 'Mail Masivo';
            $desc       = 'Envíos masivos de Mail, sin limites de contenido y fáciles de compartir mediante uno de los medios de marketing más costo-efectivo que existe.';
        break;

        case 'plataforma':
            $titulo     = 'Administra tus envíos de Mail y SMS masivos de forma eficiente';
            $desc       = 'Administra tus envíos en un solo lugar, desarrollado con los más altos estándares de tecnología, facilitando la gestión de bases de datos, mensajes y reportes.';
        break;

        case 'contacto':
            $titulo     = 'Comunícate con nosotros';
            $desc       = 'Comunícate con nosotros, nuestros ejecutivos se pondrán en contacto contigo durante el transcurso del día.';
        break;
        

    }
    

?>
<meta charset='utf-8'>
<title><?php echo $titulo; ?> | Connectus</title>

<meta name="robots" content="index, follow" />
<meta name="Description"    content="<?php echo $desc;      ?>" />
<meta name="Keywords"       content="<?php echo $keywords;  ?>" />


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="alternate" hreflang="es" href="http://es.example.com/" />

<!-- META Verificación Sitio Google -->
<meta name="google-site-verification" content="vaLnG1S1lVkJ9z3DINwk93vP-zWbbApTfQDABdV8GUY" />