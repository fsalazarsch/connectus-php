<?php
// Heading
$_['heading_title']					= 'PayPal Payflow Pro iFrame';
$_['heading_refund']				= 'Reembolso';

// Text
$_['text_payment']                  = 'Método de Pago';
$_['text_success']                  = '¡El método de pago PayPal Payflow Pro iFrame fue actualizado con éxito!';
$_['text_edit']                     = 'Editar PayPal Payflow Pro iFrame';
$_['text_pp_payflow_iframe']        = '<a target="_BLANK" href="https://www.paypal.com/uk/mrb/pal=V4T754QB63XXL"><img src="view/image/payment/paypal.png" alt="PayPal Website Payment Pro" title="PayPal Website Payment Pro iFrame" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_authorization']            = 'Autorización';
$_['text_sale']                     = 'Venta';
$_['text_authorise']                = 'Autorizar';
$_['text_capture']                  = 'Captura Retrasada';
$_['text_void']                     = 'Anulada';
$_['text_payment_info']             = 'Información de Pago';
$_['text_complete']                 = 'Completo';
$_['text_incomplete']               = 'Incompleto';
$_['text_transaction']              = 'Transacción';
$_['text_confirm_void']             = 'Si anula no puede capturar los fondos adicionales!';
$_['text_refund']                   = 'Devolución';
$_['text_refund_issued']            = 'El reembolso fue emitido con éxito';
$_['text_redirect']                 = 'Redireccionar';
$_['text_iframe']                   = 'Iframe';
$_['help_checkout_method']			= "Utilice el método redireccionar si no tiene instalado un certificado SSL o si usted no tiene la opción disponible de pago con PayPal, en su página de pago del servidor.";

// Column
$_['column_transaction_id']			= 'ID de Transacción';
$_['column_transaction_type']		= 'Tipo de Transacción';
$_['column_amount']					= 'Valor';
$_['column_time']					= 'Tiempo';
$_['column_actions']				= 'Opción';

// Tab
$_['tab_settings']                  = 'Configuración';
$_['tab_order_status']              = 'Estados';
$_['tab_checkout_customisation']    = 'Personalización';

// Entry
$_['entry_vendor']                  = 'Vendedor';
$_['entry_user']                    = 'Nombre de usuario';
$_['entry_password']                = 'Contraseña';
$_['entry_partner']                 = 'Asociado';
$_['entry_test']                    = 'Modo de prueba';
$_['entry_transaction']				= 'Método de transacción';
$_['entry_total']                   = 'Valor de compra';
$_['entry_order_status']            = 'Estado de pedido';
$_['entry_geo_zone']                = 'Zonas geográficas';
$_['entry_status']                  = 'Estado';
$_['entry_sort_order']              = 'Orden de lista';
$_['entry_transaction_id']          = 'ID de transacción';
$_['entry_full_refund']             = 'Reembolso completo';
$_['entry_amount']                  = 'Valor';
$_['entry_message']                 = 'Mensaje';
$_['entry_ipn_url']                 = 'URL de IPN';
$_['entry_checkout_method']         = 'Método de compra';
$_['entry_debug']			     	= 'Registro de errores';
$_['entry_transaction_reference']   = 'Referencia de Transacción';
$_['entry_transaction_amount']      = 'Valor de transacción';
$_['entry_refund_amount']           = 'Valor de reembolso';
$_['entry_capture_status']          = 'Estado de captura';
$_['entry_void']                    = 'Anulada';
$_['entry_capture']                 = 'Captura';
$_['entry_transactions']            = 'Transacción';
$_['entry_complete_capture']        = 'Captura completa';
$_['entry_canceled_reversal_status'] = 'Estado de devolución cancelada';
$_['entry_completed_status']        = 'Pedido completado';
$_['entry_denied_status']           = 'Pedido denegado';
$_['entry_expired_status']          = 'Pedido expirado';
$_['entry_failed_status']           = 'Pedido fallido';
$_['entry_pending_status']          = 'Pedido pendiente';
$_['entry_processed_status']        = 'Pedido procesado';
$_['entry_refunded_status']         = 'Pedido reembolsado';
$_['entry_reversed_status']         = 'Estado de devolución';
$_['entry_voided_status']           = 'Pedido anulado';
$_['entry_cancel_url']              = 'URL de cancelación';
$_['entry_error_url']               = 'URL de error';
$_['entry_return_url']              = 'URL de volver';
$_['entry_post_url']                = 'URL oculta';

// Help
$_['help_vendor']					= 'Su ID de comerciante que creó cuando se registró en Payments Pro account';
$_['help_user']						= 'Si configura uno o más usuarios en su cuenta, este será el valor del ID del usuario autorizado para procesar las transacciones. Sin embargo, si usted no ha creado usuarios adicionales en la cuenta, el usuario tiene el mismo valor que VENDEDOR';
$_['help_password']					= 'La contraseña debe tener entre 6 y 32 caracteres, creada durante el registro.';
$_['help_partner']					= 'El ID que le ha proporcionado PayPal Reseller, que ha registrado en Payflow SDK. Si ha adquirido su cuenta directamente desde PayPal, utilice el PayPal Pro en su lugar.';
$_['help_test']						= 'Utilice el servidor de pruebas (sandbox) para procesar las transacciones en la pasarela de pagos.<br /><br />Seleccionar (SI) para prueba.<br />Seleccionar (NO) para activar.';
$_['help_total']					= 'El valor total en el pedido de compra que se debe alcanzar para activar este tipo de pago.';
$_['help_debug']					= 'Información adicional en el registro de errores del sistema.';

// Button
$_['button_refund']					= 'Reembolsar';
$_['button_void']					= 'Anular';
$_['button_capture']				= 'Capturar';

// Error
$_['error_permission']              = 'Atención: ¡Usted no tiene permiso para modificar el método de pago PayPal Website Payment Pro iFrame (UK)!';
$_['error_vendor']                  = 'El campo Vendedor es obligatorio.';
$_['error_user']                    = 'El campo Usuario es obligatorio.'; 
$_['error_password']                = 'El campo Contraseña es obligatorio.'; 
$_['error_partner']                 = 'El campo Asociado es obligatorio.';
$_['error_missing_data']            = 'Datos en falta.';
$_['error_missing_order']           = 'No se pudo encontrar el pedido.';
$_['error_general']                 = 'Se ha producido un error.';
$_['error_capture_amt']             = 'Introduzca un valor para capturar.';