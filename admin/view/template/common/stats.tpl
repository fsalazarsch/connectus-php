<div id="stats">
  <ul>
    <li>
      <div><?php echo $text_complete_status; ?> <span class="pull-right"><?php echo $complete_status; ?>%</span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $complete_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $complete_status; ?>%"> <span class="sr-only"><?php echo $complete_status; ?>%</span> </div>
      </div>
    </li>
    <li>
      <div><?php echo $text_processing_status; ?> <span class="pull-right"><?php echo $processing_status; ?>%</span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $processing_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $processing_status; ?>%"> <span class="sr-only"><?php echo $processing_status; ?>%</span> </div>
      </div>
    </li>
    <li>
      <div><a style="color: #C1D2D7;" href="http://assertsoft.cl/developer/connectus/platx/admin/index.php?route=common/dashboard_client&amp;token=f5e3775fb8372abff4776c44bb6fcd1c"><i class="fa fa-shopping-cart"></i>  Comprar más creditos...</a></div>
    </li>
  </ul>
</div>
