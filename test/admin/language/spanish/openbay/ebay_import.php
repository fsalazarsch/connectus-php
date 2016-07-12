<?php
// Heading
$_['heading_title']                     = 'Importar Artículo';
$_['text_openbay']                      = 'OpenBay Pro';
$_['text_ebay']                         = 'eBay';

// Text
$_['text_sync_import_line1']            = '<strong>¡Atención!</strong> Esto importará todos sus productos desde eBay y construirá una estructura con la categoría en su tienda. Se recomienda que elimine todas las categorías y productos antes de ejecutar esta opción. <br />La estructura de categorías es la de eBay, no la categorías de su tienda (si tiene una tienda en eBay). Usted puede cambiar el nombre, eliminar y editar las categorías importadas, sin afectar sus productos en eBay.';
$_['text_sync_import_line3']            = 'Usted necesita asegurarse de que su servidor puede aceptar y procesar grandes cantidades de datos. 1000 artículos eBay son unos 40Mb, usted tendrá que calcular lo que usted requiere. Si la llamada falla, entonces es probable que su configuración es demasiado pequeño. Su memoria PHP tiene que estar por sobre los 128Mb.';
$_['text_sync_server_size']             = 'Actualmente el servidor puede aceptar: ';
$_['text_sync_memory_size']             = 'Límite de memoria en PHP: ';
$_['text_import_confirm']				= 'Usted importará todos sus artículos de eBay como nuevos productos, ¿Quiere continuar? Esta acción no se puede deshacer, asegúrese de tener una copia de seguridad de su BD.';
$_['text_import_notify']				= 'Su solicitud de importación ha sido enviado para su procesamiento. La importación tendrá una duración de aproximadamente 1 hora por 1000 artículos.';
$_['text_import_images_msg1']           = 'imágenes pendientes de importación/copiar desde eBay. Actualizar esta página, para ver si el número no disminuye después.';
$_['text_import_images_msg2']           = 'clic aquí';
$_['text_import_images_msg3']           = 'y espere. Información acerca de por qué sucedió esto, se puede encontrar <a href="http://shop.openbaypro.com/index.php?route=information/faq&topic=8_45" target="_blank">aquí</a>';

// Entry
$_['entry_import_item_advanced']        = 'Obtener datos avanzados';
$_['entry_import_categories']         	= 'Importar categorías';
$_['entry_import_description']			= 'Importar descripción';
$_['entry_import']						= 'Importar artículos';

// Buttons
$_['button_import']						= 'Importar';
$_['button_complete']					= 'Completar';

// Help
$_['help_import_item_advanced']        	= 'Tendrá hasta 10 veces más tiempo para importar artículos. Importar pesos, tamaños, ISBN y toda la información disponible.';
$_['help_import_categories']         	= 'Construye una estructura de la categoría en su tienda desde la categoría de eBay.';
$_['help_import_description']         	= 'Esto importará todo, incluyendo el código HTML, número de visitas, etc..';

// Error
$_['error_import']                   	= 'Error, no se pudo cargar.';
$_['error_maintenance']					= 'Su tienda está en modo de mantenimiento. La importación no funcionará.';
$_['error_ajax_load']					= 'Error, no se pudo conectar con el servidor.';
$_['error_validation']					= 'Usted necesita de registrarse para obtener su API token y activar el módulo.';