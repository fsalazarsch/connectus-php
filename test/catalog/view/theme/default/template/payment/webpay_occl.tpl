<form action="<?php echo $action; ?>" method="post" id="payment">
  <input type="hidden" name="TBK_TIPO_TRANSACCION" value="<?php echo $tbk_tipo_transaccion; ?>" />
  <input type="hidden" name="TBK_MONTO" value="<?php echo $tbk_monto; ?>" />
  <input type="hidden" name="TBK_ORDEN_COMPRA" value="<?php echo $tbk_orden_compra; ?>" />
  <input type="hidden" name="TBK_ID_SESION" value="<?php echo $tbk_id_sesion; ?>" /> 
  <input type="hidden" name="TBK_URL_EXITO" value="<?php echo $tbk_url_exito; ?>" />
  <input type="hidden" name="TBK_URL_FRACASO" value="<?php echo $tbk_url_fracaso; ?>" />
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>