ALTER TABLE `connectu_adm_test`.`detalle_envio` 
ADD COLUMN `estado_open` INT NULL DEFAULT 0 AFTER `fecha`,
ADD COLUMN `estado_click` INT NULL DEFAULT 0 AFTER `estado_open`,
ADD COLUMN `estado_spam` INT NULL DEFAULT 0 AFTER `estado_click`;
