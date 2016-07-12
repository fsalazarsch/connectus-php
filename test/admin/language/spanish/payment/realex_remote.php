<?php
// Heading
$_['heading_title']					= 'Realex Remote';

// Text
$_['text_payment']					= 'Método de Pago';
$_['text_success']					= '¡El método de pago Realex fue actualizado con éxito!';
$_['text_edit']                     = 'Editar Realex Remote';
$_['text_card_type']				= 'Tipo de tarjeta';
$_['text_enabled']					= 'Habilitado';
$_['text_use_default']				= 'Uso principal';
$_['text_merchant_id']				= 'ID de tienda';
$_['text_subaccount']				= 'Subcuenta';
$_['text_secret']					= 'Clave secreta';
$_['text_card_visa']				= 'Visa';
$_['text_card_master']				= 'Mastercard';
$_['text_card_amex']				= 'American Express';
$_['text_card_switch']				= 'Switch/Maestro';
$_['text_card_laser']				= 'Laser';
$_['text_card_diners']				= 'Diners';
$_['text_capture_ok']				= 'Pago capturado con éxito';
$_['text_capture_ok_order']			= 'Pago capturado con éxito, actualizar el estado del pedido - Cerrado';
$_['text_rebate_ok']				= 'Reembolso con éxito';
$_['text_rebate_ok_order']			= 'Reembolso con éxito, actualiza el estado del pedido para reembolsado.';
$_['text_void_ok']					= 'La anulación fue un éxito, actualizar el estado del pedido para anulado.';
$_['text_settle_auto']				= 'Auto';
$_['text_settle_delayed']			= 'Retrasar';
$_['text_settle_multi']				= 'Multi';
$_['text_ip_message']				= 'Coloque la dirección de IP del almacén antes de estar en modo real.';
$_['text_payment_info']				= 'Información de pago';
$_['text_capture_status']			= 'Pago capturado';
$_['text_void_status']				= 'Pago anulado';
$_['text_rebate_status']			= 'Pago reembolsado';
$_['text_order_ref']				= 'Ref. de pedido';
$_['text_order_total']				= 'Total de Autorizados';
$_['text_total_captured']			= 'Total de Capturados';
$_['text_transactions']				= 'Transacciones';
$_['text_confirm_void']				= '¿Está seguro que desea anular el pago?';
$_['text_confirm_capture']			= '¿Está seguro que desea capturar el pago?';
$_['text_confirm_rebate']			= '¿Está seguro que deseas reembolsar el pago?';
$_['text_realex_remote']			= '<a target="_BLANK" href="http://www.realexpayments.co.uk/partner-refer?id=opencart"><img src="view/image/payment/realex.png" alt="Realex" title="Realex" style="border: 1px solid #EEEEEE;" /></a>';

// Column
$_['text_column_amount']			= 'Valor';
$_['text_column_type']				= 'Tipo';
$_['text_column_date_added']		= 'Fecha de Registro';

// Entry
$_['entry_merchant_id']				= 'ID de tienda';
$_['entry_secret']					= 'Clave secreta';
$_['entry_rebate_password']			= 'Contraseña de reembolso';
$_['entry_total']					= 'Total';
$_['entry_sort_order']				= 'Orden de lista';
$_['entry_geo_zone']				= 'Zona geográfica';
$_['entry_status']					= 'Estado';
$_['entry_debug']					= 'Registro de errores';
$_['entry_auto_settle']				= 'Tipo de liquidación';
$_['entry_tss_check']				= 'Verificar TSS';
$_['entry_card_data_status']		= 'Registro de tarjetas';
$_['entry_3d']						= 'Activar 3D secure';
$_['entry_liability_shift']			= 'Aceptar sin responsabilidad escenarios cambiantes';
$_['entry_status_success_settled']	= 'Éxito - Resuelto';
$_['entry_status_success_unsettled'] = 'Éxito - S/ Resolver';
$_['entry_status_decline']			= 'Declinado';
$_['entry_status_decline_pending']	= 'Declinado - Autenticación offline';
$_['entry_status_decline_stolen']	= 'Declinado - tarjeta perdida o robada';
$_['entry_status_decline_bank']		= 'Declinado - Error de banco';
$_['entry_status_void']				= 'Anulado';
$_['entry_status_rebate']			= 'Reembolsado';

// Help
$_['help_total']					= 'El valor total en el pedido de compra que se debe alcanzar para activar este tipo de pago.';
$_['help_card_select']				= 'El usuario debe elegir su tipo de tarjeta de crédito antes de ser redirigido.';
$_['help_notification']				= 'Usted necesita proporcionar esta URL (First Data) para obtener las notificaciones de pago.';
$_['help_debug']					= 'Habilitar para guardar los datos sensibles a un archivo de registro. Usted debe desactivar siempre, al menos que se indique lo contrario.';
$_['help_liability']				= 'La aceptación de la responsabilidad significa que todavía se aceptan pagos, mismo cuando un usuario no utiliza 3D secure.';
$_['help_card_data_status']			= 'Registro de los últimos 4 dígitos de la tarjeta, fecha de validad, nombre, información del banco emisor.';

// Tab
$_['tab_account']					= 'Información (API)';
$_['tab_sub_account']				= 'Cuentas';
$_['tab_order_status']				= 'Estados';
$_['tab_payment']					= 'Ajustes de Pago';

// Button
$_['button_capture']				= 'Capturar';
$_['button_rebate']					= 'Reembolsar';
$_['button_void']					= 'Anular';

// Error
$_['error_merchant_id']				= 'El ID de tienda es requerido.';
$_['error_secret']					= 'El secreto compartido es requerido.';