<ul id="menu">
<?php if ($tipo_usuario == 'cliente') { ?>

    <li id="dashboard"><a href="<?php echo $home_client; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_panel_de_control; ?></span></a></li>

      <!--CONTACTOS MENU-->  
       <li><a class="parent"><i class="fa fa-users fa-fw"></i> <span><?php echo $text_contactos; ?></span></a>
        <ul>
          <li><a href="<?php echo $mis_listas; ?>"><?php echo $text_mis_listas; ?></a></li>
          <li><a href="<?php echo $import_listas; ?>"><?php echo $text_impor_listas; ?></a></li>
        </ul>
      </li>
      
      <!--SMS MENU-->
      
       <li id="catalog"><a class="parent"><i class="fa fa-comments fa-fw"></i> <span><?php echo $text_sms_titulo; ?></span></a>
        <ul>
            <li><a href="<?php echo $enviar_sms; ?>"><?php echo $text_sms_enviar; ?></a></li>

            <li><a href="<?php echo $sms_programados; ?>"><?php echo $text_sms_programados; ?></a></li>
            <li><a href="<?php echo $sms_preferidos; ?>"><?php echo $text_sms_preferidos; ?></a></li>  
            <li><a href="<?php echo $sms_recibidos; ?>"><?php echo $text_sms_recibidos; ?></a></li>
                   
        <li><a href="<?php echo $sms_historial; ?>"><?php echo $text_sms_historial; ?></a></li>  
        </ul>
      </li>
      
      <!--MAIL MENU-->
      
       <li id="catalog"><a class="parent"><i class="fa fa-envelope fa-fw"></i> <span><?php echo $text_mail_titulo; ?></span></a>
        <ul>
          <li><a href="<?php echo $mail_enviar; ?>"><?php echo $text_mail_enviar; ?></a></li>
          <li><a href="<?php echo $mail_programados; ?>"><?php echo $text_mail_programados; ?></a></li>   
          <li><a href="<?php echo $mail_preferidos; ?>"><?php echo $text_mail_preferidos; ?></a></li>         
          <li><a href="<?php echo $mail_desinscritos; ?>"><?php echo $text_mail_desinscritos; ?></a></li>
            <!--<li><a href="<?php echo $mail_rebote; ?>"><?php echo $text_mail_rebote; ?></a></li>-->
            <li><a href="<?php echo $mail_historial; ?>"><?php echo $text_mail_historial; ?></a></li> 

            
        </ul>
      </li>
      
      <!--CONFIGURACIONES MENU-->
      
       <li id="catalog"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_configuracion; ?></span></a>
        <ul>
          <li><a href="<?php echo $admin_usuarios; ?>"><?php echo $text_admin_usuarios; ?></a></li>
          <li><a href="<?php echo $admin_cuenta; ?>"><?php echo $text_mi_cuenta; ?></a></li>         
        </ul>
      </li>
  
<?php }else if ($tipo_usuario == 'administrador' && $como_empresa == false) { ?>



    <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard"></i> <span><?php echo $text_dashboard; ?></span></a></li>

    <li id="adm-empresas"><a class="parent" ><i class="fa fa-building-o fa-fw"></i> <span><?php echo $gestion_empresas; ?></span></a>
      <ul>
        <li><a href="<?php echo $empresas;?>" ><?php echo $text_customer; ?></a></li>
        <!---<li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>-->
        <!--<li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>-->
        <!--<li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>-->
      </ul>
    </li>

    <li id="usuarios"><a class="parent"><i class="fa fa-user fa-fw"></i><span><?php echo $text_users; ?></span></a>
      <ul>
        <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
        <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
        <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
      </ul>
    </li>
    
    <!-- CATALOGO -->

        <!--
      <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
        <ul>
          <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
          <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
          <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
          <li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li>
          <li><a class="parent"><?php echo $text_attribute; ?></a>
            <ul>
              <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
              <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
          <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
          <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
        </ul>
      </li>-->

      <!-- Extensiones  -->


      <!-- 
      <li id="extension"><a class="parent"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_extension; ?></span></a>
        <ul>
          <li><a href="<?php echo $installer; ?>"><?php echo $text_installer; ?></a></li>
          <li><a href="<?php echo $modification; ?>"><?php echo $text_modification; ?></a></li>
          <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
          <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
          <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
          <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
          <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
          <li><a href="<?php echo $fraud; ?>"><?php echo $text_fraud; ?></a></li>
          <?php if ($openbay_show_menu == 1) { ?>
          <li><a class="parent"><?php echo $text_openbay_extension; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_extension; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_orders; ?>"><?php echo $text_openbay_orders; ?></a></li>
              <li><a href="<?php echo $openbay_link_items; ?>"><?php echo $text_openbay_items; ?></a></li>
              <?php if ($openbay_markets['ebay'] == 1) { ?>
              <li><a class="parent"><?php echo $text_openbay_ebay; ?></a>
                <ul>
                  <li><a href="<?php echo $openbay_link_ebay; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
                  <li><a href="<?php echo $openbay_link_ebay_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
                  <li><a href="<?php echo $openbay_link_ebay_links; ?>"><?php echo $text_openbay_links; ?></a></li>
                  <li><a href="<?php echo $openbay_link_ebay_orderimport; ?>"><?php echo $text_openbay_order_import; ?></a></li>
                </ul>
              </li>
              <?php } ?>
              <?php if ($openbay_markets['amazon'] == 1) { ?>
              <li><a class="parent"><?php echo $text_openbay_amazon; ?></a>
                <ul>
                  <li><a href="<?php echo $openbay_link_amazon; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
                  <li><a href="<?php echo $openbay_link_amazon_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
                  <li><a href="<?php echo $openbay_link_amazon_links; ?>"><?php echo $text_openbay_links; ?></a></li>
                </ul>
              </li>
              <?php } ?>
              <?php if ($openbay_markets['amazonus'] == 1) { ?>
              <li><a class="parent"><?php echo $text_openbay_amazonus; ?></a>
                <ul>
                  <li><a href="<?php echo $openbay_link_amazonus; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
                  <li><a href="<?php echo $openbay_link_amazonus_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
                  <li><a href="<?php echo $openbay_link_amazonus_links; ?>"><?php echo $text_openbay_links; ?></a></li>
                </ul>
              </li>
              <?php } ?>
              <?php if ($openbay_markets['etsy'] == 1) { ?>
              <li><a class="parent"><?php echo $text_openbay_etsy; ?></a>
                <ul>
                  <li><a href="<?php echo $openbay_link_etsy; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
                  <li><a href="<?php echo $openbay_link_etsy_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
                  <li><a href="<?php echo $openbay_link_etsy_links; ?>"><?php echo $text_openbay_links; ?></a></li>
                </ul>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </li>

      -->


      <!--
        <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
        <ul>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $order_recurring; ?>"><?php echo $text_order_recurring; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a class="parent"><?php echo $text_voucher; ?></a>
            <ul>
              <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
              <li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_paypal ?></a>
            <ul>
              <li><a href="<?php echo $paypal_search ?>"><?php echo $text_paypal_search ?></a></li>
            </ul>
          </li>
        </ul>
      </li>-->

      <!--
      <li><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_marketing; ?></span></a>
        <ul>
          <li><a href="<?php echo $marketing; ?>"><?php echo $text_marketing; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
        </ul>
      </li>
      -->


      <!--

      <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
        <ul>
          <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
          <li><a class="parent"><?php echo $text_design; ?></a>
            <ul>
              <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>
              <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_users; ?></a>
            <ul>
              <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
              <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
              <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_localisation; ?></a>
            <ul>
              <li><a href="<?php echo $location; ?>"><?php echo $text_location; ?></a></li>
              <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
              <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
              <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
              <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
              <li><a class="parent"><?php echo $text_return; ?></a>
                <ul>
                  <li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
                  <li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
                  <li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
              <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
              <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
              <li><a class="parent"><?php echo $text_tax; ?></a>
                <ul>
                  <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
                  <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
              <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
            </ul>
          </li>
        </ul>
      </li>-->




    <!-- Herramientas -->

    <!--
      <li id="tools"><a class="parent"><i class="fa fa-wrench fa-fw"></i> <span><?php echo $text_tools; ?></span></a>
        <ul>
          <li><a href="<?php echo $upload; ?>"><?php echo $text_upload; ?></a></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
        </ul>
      </li>
    -->

    <!-- Informes -->

    <!--
      <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
        <ul>
          <li><a class="parent"><?php echo $text_sale; ?></a>
            <ul>
              <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
              <li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
              <li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
              <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
              <li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_product; ?></a>
            <ul>
              <li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
              <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_customer; ?></a>
            <ul>
              <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
              <li><a href="<?php echo $report_customer_activity; ?>"><?php echo $text_report_customer_activity; ?></a></li>
              <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
              <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
              <li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_marketing; ?></a>
            <ul>
              <li><a href="<?php echo $report_marketing; ?>"><?php echo $text_marketing; ?></a></li>
              <li><a href="<?php echo $report_affiliate; ?>"><?php echo $text_report_affiliate; ?></a></li>
              <li><a href="<?php echo $report_affiliate_activity; ?>"><?php echo $text_report_affiliate_activity; ?></a></li>
            </ul>
          </li>
        </ul>
      </li>-->


      <li  class='' id="reportes">
        <a class="parent">
            <i class="fa fa-user fa-fw"></i>
            <span><?php echo $text_reportes_titulo; ?></span>
        </a>
        <ul>
            <li class=''>
                <a href="<?php echo $reportes_sms; ?>"><?php echo $text_reportes_sms; ?></a>
            </li>
        </ul>
    </li>
  
<?php }else if ($tipo_usuario == 'administrador' && $como_empresa == true) { ?>


    <li id="dashboard">
        <a href="<?php echo $home_client; ?>">
            <i class="fa fa-dashboard fa-fw"></i>
            <span><?php echo $text_dashboard; ?></span>
        </a>
    </li>


    


    <!--CONTACTOS MENU-->
    <li><a class="parent"><i class="fa fa-users fa-fw"></i> <span><?php echo $text_contactos; ?></span></a>
        <ul>
          <li><a href="<?php echo $mis_listas; ?>"><?php echo $text_mis_listas; ?></a></li>
          <li><a href="<?php echo $import_listas; ?>"><?php echo $text_impor_listas; ?></a></li>
        </ul>
    </li>    
    <!--SMS MENU-->
    <li id="catalog"><a class="parent"><i class="fa fa-comments fa-fw"></i> <span><?php echo $text_sms_titulo; ?></span></a>
        <ul>
            <li><a href="<?php echo $enviar_sms; ?>"><?php echo $text_sms_enviar; ?></a></li>
            <li><a href="<?php echo $sms_programados; ?>"><?php echo $text_sms_programados; ?></a></li>
            <li><a href="<?php echo $sms_preferidos; ?>"><?php echo $text_sms_preferidos; ?></a></li>  
            <li><a href="<?php echo $sms_recibidos; ?>"><?php echo $text_sms_recibidos; ?></a></li>
            
                   
            <li><a href="<?php echo $sms_historial; ?>"><?php echo $text_sms_historial; ?></a></li>  
        </ul>
    </li>    
    <!--MAIL MENU-->
    <li id="catalog"><a class="parent"><i class="fa fa-envelope fa-fw"></i> <span><?php echo $text_mail_titulo; ?></span></a>
        <ul>
          <li><a href="<?php echo $mail_enviar; ?>"><?php echo $text_mail_enviar; ?></a></li>
          <li><a href="<?php echo $mail_programados; ?>"><?php echo $text_mail_programados; ?></a></li>  
          <li><a href="<?php echo $mail_preferidos; ?>"><?php echo $text_mail_preferidos; ?></a></li>         
          <li><a href="<?php echo $mail_desinscritos; ?>"><?php echo $text_mail_desinscritos; ?></a></li>
            <!--<li><a href="<?php echo $mail_rebote; ?>"><?php echo $text_mail_rebote; ?></a></li>-->
            <li><a href="<?php echo $mail_historial; ?>"><?php echo $text_mail_historial; ?></a></li>    
             
        </ul>
    </li>

    
    <li class='menu_destacado'>
        <a href="<?php echo $salir_empresa; ?>" data-placement="right" data-toggle="tooltip" title="<?php echo $cerrar_modo_empresa['completo']; ?>">
            <i class="fa fa-key"></i>
            <span><?php echo $cerrar_modo_empresa['mostrar']; ?></span>
        </a>
    </li>


    <li   id="adm-empresas">
        <a class="parent menu_destacado" >
            <i class="fa fa-users fa-fw"></i>
            <span><?php echo $gestion_empresas; ?>
            </span>
        </a>
        <ul>
          <li class='menu_destacado'><a href="<?php echo $empresas;?>" ><?php echo $text_customer; ?></a></li>
          <!--<li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
          <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
          <li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>-->
        </ul>
    </li>

    <li  class='menu_destacado' id="usuarios">
        <a class="parent">
            <i class="fa fa-user fa-fw"></i>
            <span><?php echo $text_users; ?>
            </span>
        </a>
        <ul>
            <li class='menu_destacado'><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
            <li class='menu_destacado'><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
            <li class='menu_destacado'><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
        </ul>
    </li>

    <li  class='menu_destacado' id="reportes">
        <a class="parent">
            <i class="fa fa-user fa-fw"></i>
            <span><?php echo $text_reportes_titulo; ?></span>
        </a>
        <ul>
            <li class='menu_destacado'>
                <a href="<?php echo $reportes_sms; ?>"><?php echo $text_reportes_sms; ?></a>
            </li>
        </ul>
    </li>


    <!--
  <li id="admin" ><a class="parent"><i class="fa fa-cogs fa-fw"></i><span><?php echo $text_admin; ?></span></a>  
    <ul>

        
      <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
        <ul>
          <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
          <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
          <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
          <li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li>
          <li><a class="parent"><?php echo $text_attribute; ?></a>
            <ul>
              <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
              <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
          <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
          <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
        </ul>
      </li>


      <li id="extension"><a class="parent"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_extension; ?></span></a>
        <ul>
          <li><a href="<?php echo $installer; ?>"><?php echo $text_installer; ?></a></li>
          <li><a href="<?php echo $modification; ?>"><?php echo $text_modification; ?></a></li>
          <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
          <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
          <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
          <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
          <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
          <li><a href="<?php echo $fraud; ?>"><?php echo $text_fraud; ?></a></li>
          <?php if ($openbay_show_menu == 1) { ?>
          <li><a class="parent"><?php echo $text_openbay_extension; ?></a>
            <ul>
              <li><a href="<?php echo $openbay_link_extension; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
              <li><a href="<?php echo $openbay_link_orders; ?>"><?php echo $text_openbay_orders; ?></a></li>
              <li><a href="<?php echo $openbay_link_items; ?>"><?php echo $text_openbay_items; ?></a></li>
              <?php if ($openbay_markets['ebay'] == 1) { ?>
              <li><a class="parent"><?php echo $text_openbay_ebay; ?></a>
                <ul>
                  <li><a href="<?php echo $openbay_link_ebay; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
                  <li><a href="<?php echo $openbay_link_ebay_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
                  <li><a href="<?php echo $openbay_link_ebay_links; ?>"><?php echo $text_openbay_links; ?></a></li>
                  <li><a href="<?php echo $openbay_link_ebay_orderimport; ?>"><?php echo $text_openbay_order_import; ?></a></li>
                </ul> 
              </li>
              <?php } ?>
              <?php if ($openbay_markets['amazon'] == 1) { ?>
              <li><a class="parent"><?php echo $text_openbay_amazon; ?></a>
                <ul>
                  <li><a href="<?php echo $openbay_link_amazon; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
                  <li><a href="<?php echo $openbay_link_amazon_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
                  <li><a href="<?php echo $openbay_link_amazon_links; ?>"><?php echo $text_openbay_links; ?></a></li>
                </ul>
              </li>
              <?php } ?>
              <?php if ($openbay_markets['amazonus'] == 1) { ?>
              <li><a class="parent"><?php echo $text_openbay_amazonus; ?></a>
                <ul>
                  <li><a href="<?php echo $openbay_link_amazonus; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
                  <li><a href="<?php echo $openbay_link_amazonus_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
                  <li><a href="<?php echo $openbay_link_amazonus_links; ?>"><?php echo $text_openbay_links; ?></a></li>
                </ul>
              </li>
              <?php } ?>
              <?php if ($openbay_markets['etsy'] == 1) { ?>
              <li><a class="parent"><?php echo $text_openbay_etsy; ?></a>
                <ul>
                  <li><a href="<?php echo $openbay_link_etsy; ?>"><?php echo $text_openbay_dashboard; ?></a></li>
                  <li><a href="<?php echo $openbay_link_etsy_settings; ?>"><?php echo $text_openbay_settings; ?></a></li>
                  <li><a href="<?php echo $openbay_link_etsy_links; ?>"><?php echo $text_openbay_links; ?></a></li>
                </ul>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </li>
    
   


      <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
        <ul>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $order_recurring; ?>"><?php echo $text_order_recurring; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
 -->

          <!--



                      <li><a class="parent"><?php echo $text_customer; ?></a>
                        <ul>
                          <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
                          <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
                          <!--<li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
                          <li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>
                        </ul>
                      </li>



          -->

          <!--
          <li><a class="parent"><?php echo $text_voucher; ?></a>
            <ul>
              <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
              <li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_paypal ?></a>
            <ul>
              <li><a href="<?php echo $paypal_search ?>"><?php echo $text_paypal_search ?></a></li>
            </ul>
          </li>
        </ul>
      </li>
      <li><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_marketing; ?></span></a>
        <ul>
          <li><a href="<?php echo $marketing; ?>"><?php echo $text_marketing; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
        </ul>
      </li>
      <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
        <ul>
          <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
          <li><a class="parent"><?php echo $text_design; ?></a>
            <ul>
              <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>
              <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_users; ?></a>
            <ul>
              <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
              <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
              <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_localisation; ?></a>
            <ul>
              <li><a href="<?php echo $location; ?>"><?php echo $text_location; ?></a></li>
              <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
              <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
              <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
              <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
              <li><a class="parent"><?php echo $text_return; ?></a>
                <ul>
                  <li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
                  <li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
                  <li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
              <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
              <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
              <li><a class="parent"><?php echo $text_tax; ?></a>
                <ul>
                  <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
                  <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
              <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
            </ul>
          </li>
        </ul>
      </li>
      <li id="tools"><a class="parent"><i class="fa fa-wrench fa-fw"></i> <span><?php echo $text_tools; ?></span></a>
        <ul>
          <li><a href="<?php echo $upload; ?>"><?php echo $text_upload; ?></a></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
          <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
        </ul>
      </li>
      <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
        <ul>
          <li><a class="parent"><?php echo $text_sale; ?></a>
            <ul>
              <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
              <li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
              <li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
              <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
              <li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_product; ?></a>
            <ul>
              <li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
              <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_customer; ?></a>
            <ul>
              <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
              <li><a href="<?php echo $report_customer_activity; ?>"><?php echo $text_report_customer_activity; ?></a></li>
              <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
              <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
              <li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_marketing; ?></a>
            <ul>
              <li><a href="<?php echo $report_marketing; ?>"><?php echo $text_marketing; ?></a></li>
              <li><a href="<?php echo $report_affiliate; ?>"><?php echo $text_report_affiliate; ?></a></li>
              <li><a href="<?php echo $report_affiliate_activity; ?>"><?php echo $text_report_affiliate_activity; ?></a></li>
            </ul>
          </li>
        </ul>
      </li> 
        


        </ul> 
    </li>

  -->

    
  <?php } ?>
</ul>
