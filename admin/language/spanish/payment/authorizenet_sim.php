<?php
// Heading
$_['heading_title']					= 'Authorize.Net (SIM)';

// Text
$_['text_payment']					= 'Método de Pago';
$_['text_success']					= '¡El método de pago Authorize.Net (AIM) fue actualizado con éxito!';
$_['text_edit']                     = 'Editar Authorize.Net (SIM)';
$_['text_authorizenet_sim']			= '<a onclick="window.open(\'http://reseller.authorize.net/application/?id=5561103\');"><img src="view/image/payment/authorizenet.png" alt="Authorize.Net" title="Authorize.Net" style="border: 1px solid #EEEEEE;" /></a>';

// Entry
$_['entry_merchant']				= 'ID de sucursal';
$_['entry_key']						= 'Clave de transacción';
$_['entry_callback']				= 'URL de respuesta';
$_['entry_md5']						= 'Valor MD5 Hash';
$_['entry_test']					= 'Modo de Prueba';
$_['entry_total']                   = 'Valor de compra';
$_['entry_order_status']            = 'Estado de pedido';
$_['entry_geo_zone']	            = 'Zonas geográficas'; 
$_['entry_status']		            = 'Estado';
$_['entry_sort_order']	            = 'Orden de lista';

// Help
$_['help_callback']					= 'Por favor, iniciar sesión y defina esto en <a href="https://secure.authorize.net" target="_blank" class="txtLink">https://secure.authorize.net</a>.';
$_['help_md5']						= 'Las características de MD5 Hash le permite autenticar la transacción de forma segura desde Authorize.Net. Por favor, para configurar esta característica debe iniciar sesión en <a href="https://secure.authorize.net" target="_blank" class="txtLink">https://secure.authorize.net</a>.(Opcional)';
$_['help_total']					= 'El valor total en el pedido de compra que se debe alcanzar para activar este tipo de pago.';

// Error
$_['error_permission']              = 'Atención: ¡Usted no tiene permiso para modificar el método de pago Authorize.Net (AIM)!';
$_['error_merchant']                = 'El campo Sucursal es obligatorio.';
$_['error_key']			            = 'El campo Clave es obligatorio.';