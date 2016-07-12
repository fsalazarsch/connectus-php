<?php
// Heading
$_['heading_title']					= 'First Data EMEA Connect (3DSecure enabled)';

// Text
$_['text_payment']					= 'Método de Pago';
$_['text_success']					= '¡El método de pago First Data fue actualizado con éxito!';
$_['text_edit']                     = 'Editar First Data EMEA Connect (3DSecure enabled)';
$_['text_notification_url']			= 'URL de Notificación';
$_['text_live']						= 'Modo Real';
$_['text_demo']						= 'Modo Prueba';
$_['text_enabled']					= 'Habilitar';
$_['text_merchant_id']				= 'ID de tienda';
$_['text_secret']					= 'Clave secreta';
$_['text_capture_ok']				= 'Pago efectuado con éxito';
$_['text_capture_ok_order']			= 'Pago efectuado con éxito, actualizar el estado del pedido - Cerrado';
$_['text_void_ok']					= 'La anulación fue efectuada, actualizar el estado del pedido para anulado.';
$_['text_settle_auto']				= 'Venta';
$_['text_settle_delayed']			= 'Pre-Autorización';
$_['text_success_void']				= 'La transacción fue anulada con éxito';
$_['text_success_capture']			= 'La transacción fue efectuada con éxito';
$_['text_firstdata']				= '<img src="view/image/payment/firstdata.png" alt="First Data" title="First Data" style="border: 1px solid #EEEEEE;" />';
$_['text_payment_info']				= 'Información de pago';
$_['text_capture_status']			= 'Pago efectuado';
$_['text_void_status']				= 'Pago anulado';
$_['text_order_ref']				= 'Ref. de Pedido';
$_['text_order_total']				= 'Total de Autorizados';
$_['text_total_captured']			= 'Total de Capturados';
$_['text_transactions']				= 'Transacciones';
$_['text_column_amount']			= 'Valor';
$_['text_column_type']				= 'Tipo';
$_['text_column_date_added']		= 'Fecha de Registro';
$_['text_confirm_void']				= '¿Está seguro que desea anular el pago?';
$_['text_confirm_capture']			= '¿Está seguro que desea capturar el pago?';

// Entry
$_['entry_merchant_id']				= 'ID de tienda';
$_['entry_secret']					= 'Clave secreta';
$_['entry_total']					= 'Total';
$_['entry_sort_order']				= 'Orden de lista';
$_['entry_geo_zone']				= 'Zona geográfica';
$_['entry_status']					= 'Estado';
$_['entry_debug']					= 'Registro de errores';
$_['entry_live_demo']				= 'Seleccionar';
$_['entry_auto_settle']				= 'Tipo de liquidación';
$_['entry_card_select']				= 'Seleccionar tarjeta';
$_['entry_tss_check']				= 'Verificar TSS';
$_['entry_live_url']				= 'URL de conexión (real)';
$_['entry_demo_url']				= 'URL de conexión (demo)';
$_['entry_status_success_settled']	= 'Éxito - Resuelto';
$_['entry_status_success_unsettled']= 'Éxito - S/ Resolver';
$_['entry_status_decline']			= 'Declinado';
$_['entry_status_void']				= 'Anulado';
$_['entry_enable_card_store']		= 'Activar el almacenamiento tarjeta';

// Help
$_['help_total']					= 'El valor total en el pedido de compra que se debe alcanzar para activar este tipo de pago.';
$_['help_notification']				= 'Usted necesita proporcionar esta URL (First Data) para obtener las notificaciones de pago.';
$_['help_debug']					= 'Habilitar para guardar los datos sensibles a un archivo de registro. Usted debe desactivar siempre, al menos que se indique lo contrario.';
$_['help_settle']					= 'Si utiliza la opción Pre-Autenticación, debe completar una nueva autenticación dentro de 3-5 días de lo contrario su transacción será dada de baja.';

// Tab
$_['tab_account']					= 'Información (API)';
$_['tab_order_status']				= 'Estados';
$_['tab_payment']					= 'Ajustes de Pago';
$_['tab_advanced']					= 'Avanzado';

// Button
$_['button_capture']				= 'Capturado';
$_['button_void']					= 'Anulado';

// Error
$_['error_merchant_id']				= 'El ID de tienda es requerido.';
$_['error_secret']					= 'El secreto compartido es requerido.';
$_['error_live_url']				= 'La URL de la conexión real es requerida.';
$_['error_demo_url']				= 'La URL de la demo es requerida.';
$_['error_data_missing']			= 'Los datos están en faltan.';
$_['error_void_error']				= 'No es posible anular la transacción.';
$_['error_capture_error']			= 'No se posible capturar la transacción.';