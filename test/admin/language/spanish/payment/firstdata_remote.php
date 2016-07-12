<?php
// Heading
$_['heading_title']					= 'First Data EMEA Web Service API';

// Text
$_['text_payment']					= 'Método de Pago';
$_['text_success']					= '¡El método de pago First Data fue actualizado con éxito!';
$_['text_firstdata_remote']			= '<img src="view/image/payment/firstdata.png" alt="First Data" title="First Data" style="border: 1px solid #EEEEEE;" />';
$_['text_edit']                     = 'Editar First Data EMEA Web Service API';
$_['text_card_type']				= 'Tipo de tarjeta';
$_['text_enabled']					= 'Habilitar';
$_['text_merchant_id']				= 'ID de tienda';
$_['text_subaccount']				= 'Subcuenta';
$_['text_user_id']					= 'ID de usuario';
$_['text_capture_ok']				= 'Captura con éxito.';
$_['text_capture_ok_order']			= 'Pago efectuado con éxito, actualizar el estado del pedido para el éxito - Cerrado';
$_['text_refund_ok']				= 'Reembolso con éxito';
$_['text_refund_ok_order']			= 'Reembolso con éxito, actualizar el estado del pedido para reembolsado.';
$_['text_void_ok']					= 'La anulación fue un éxito, actualizar el estado del pedido para anulado.';
$_['text_settle_auto']				= 'Venta';
$_['text_settle_delayed']			= 'Pre-Autorización';
$_['text_mastercard']				= 'Mastercard';
$_['text_visa']						= 'Visa';
$_['text_diners']					= 'Diners';
$_['text_amex']						= 'American Express';
$_['text_maestro']					= 'Maestro';
$_['text_payment_info']				= 'Información de pago';
$_['text_capture_status']			= 'Pago capturado';
$_['text_void_status']				= 'Pago anulado';
$_['text_refund_status']			= 'Pago reembolsado';
$_['text_order_ref']				= 'Ref. de pedido';
$_['text_order_total']				= 'Total de autorizados';
$_['text_total_captured']			= 'Total de pagos';
$_['text_transactions']				= 'Transacciones';
$_['text_column_amount']			= 'Valor';
$_['text_column_type']				= 'Tipo';
$_['text_column_date_added']		= 'Fecha de Registro';
$_['text_confirm_void']				= '¿Está seguro que desea anular el pago?';
$_['text_confirm_capture']			= '¿Está seguro que desea capturar el pago?';
$_['text_confirm_refund']			= '¿Está seguro que desea reembolsar el pago?';

// Entry
$_['entry_certificate_path']		= 'Certificado (path)';
$_['entry_certificate_key_path']	= 'Clave privada (path)';
$_['entry_certificate_key_pw']		= 'Contraseña (Private key)';
$_['entry_certificate_ca_path']		= 'CA path';
$_['entry_merchant_id']				= 'ID de tienda';
$_['entry_user_id']					= 'ID de usuario';
$_['entry_password']				= 'Contraseña';
$_['entry_total']					= 'Total';
$_['entry_sort_order']				= 'Orden de lista';
$_['entry_geo_zone']				= 'Zona geográfica';
$_['entry_status']					= 'Estado';
$_['entry_debug']					= 'Registro de errores';
$_['entry_auto_settle']				= 'Tipo de liquidación';
$_['entry_status_success_settled']	= 'Éxito - Resuelto';
$_['entry_status_success_unsettled']= 'Éxito - S/ Resolver';
$_['entry_status_decline']			= 'Declinado';
$_['entry_status_void']				= 'Anulado';
$_['entry_status_refund']			= 'Reembolsado';
$_['entry_enable_card_store']		= 'Activar el almacenamiento tarjeta';
$_['entry_cards_accepted']			= 'Tarjetas aceptadas';

// Help
$_['help_total']					= 'El valor total en el pedido de compra que se debe alcanzar para activar este tipo de pago.';
$_['help_certificate']				= 'Los certificados y las claves privadas deben ser almacenados fuera de sus carpetas públicas';
$_['help_card_select']				= 'El usuario debe elegir su tipo de tarjeta de crédito antes de ser redirigido.';
$_['help_notification']				= 'Usted necesita proporcionar esta URL (First Data) para obtener las notificaciones de pago.';
$_['help_debug']					= 'Habilitar para guardar los datos sensibles a un archivo de registro. Usted debe desactivar siempre, al menos que se indique lo contrario.';
$_['help_settle']					= 'Si utiliza la opción Pre-Autenticación, debe completar una nueva autenticación dentro de 3-5 días de lo contrario su transacción será dada de baja.';

// Tab
$_['tab_account']					= 'Información (API)';
$_['tab_order_status']				= 'Estados';
$_['tab_payment']					= 'Ajustes de Pago';

// Button
$_['button_capture']				= 'Capturado';
$_['button_refund']					= 'Reembolsado';
$_['button_void']					= 'Anulado';

// Error
$_['error_merchant_id']				= 'El ID de tienda es requerido.';
$_['error_user_id']					= 'El ID de usuario es requerido.';
$_['error_password']				= 'La Contraseña es requerido.';
$_['error_certificate']				= 'El Certificado (path) es requerido.';
$_['error_key']						= 'El Certificado (key) es requerido.';
$_['error_key_pw']					= 'La contraseña (Certificate key) es requerido.';
$_['error_ca']						= 'El Certificado de Autoridad (CA) es requerido.';