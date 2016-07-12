<?php
// Text
$_['text_title']           = 'Factura Klarna';
$_['text_terms_fee']	   = '<span id="klarna_invoice_toc"></span> (+%s)<script type="text/javascript">var terms = new Klarna.Terms.Invoice({el: \'klarna_invoice_toc\', eid: \'%s\', country: \'%s\', charge: %s});</script>';
$_['text_terms_no_fee']	   = '<span id="klarna_invoice_toc"></span><script type="text/javascript">var terms = new Klarna.Terms.Invoice({el: \'klarna_invoice_toc\', eid: \'%s\', country: \'%s\'});</script>';
$_['text_additional']      = 'Su cuenta Klarna requiere alguna información adicional para procesar su pedido.';
$_['text_male']            = 'Masculino';
$_['text_female']          = 'Femenino';
$_['text_year']            = 'Año';
$_['text_month']           = 'Mes';
$_['text_day']             = 'Día';
$_['text_comment']         = "Klarna's ID de Fatura: %s\n%s/%s: %.4f";

// Entry
$_['entry_gender']         = 'Sexo:';
$_['entry_pno']            = 'Número personal:';
$_['entry_dob']            = 'Fecha de nacimiento:';
$_['entry_phone_no']       = 'Número de teléfono:';
$_['entry_street']         = 'Dirección:';
$_['entry_house_no']       = 'Número:';
$_['entry_house_ext']      = 'Piso/Depto.:';
$_['entry_company']        = 'Número de empresa:';

// Help
$_['help_pno']			   = 'Por favor, coloque su número de seguridad social.';
$_['help_phone_no']	       = 'Por favor, coloque su número de teléfono.';
$_['help_street']		   = 'Por favor, la entrega sólo puede ser efectuada en la dirección de registrado en Klarna.';
$_['help_house_no']		   = 'Por favor, coloque su número de calle.';
$_['help_house_ext']	   = 'Ejemplo: A, B, C, Red, Blue ect..';
$_['help_company']		   = 'Por favor, coloque su número de registro de empresa.';

// Error
$_['error_deu_terms']     = 'Usted debe aceptar la política de privacidad de Klarna (Datenschutz).';
$_['error_address_match'] = 'Las direcciones de pago y entrega deben ser iguales para usar una factura Klarna.';
$_['error_network']       = 'Error durante la conexión con Klarna. Por favor, inténtelo nuevamente.';