<a id="scrollUp" title="Scroll to top"><i class="fa fa-chevron-up"></i></a>

    <script type='text/javascript' src='./js/jquery.js'></script>
    <script type='text/javascript' src='./js/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='./revslider/rs-plugin/js/jquery.themepunch.tools.min.js'></script>
    <script type='text/javascript' src='./revslider/rs-plugin/js/jquery.themepunch.revolution.min.js'></script>

    <script type="text/javascript">
        /******************************************
            -   PREPARE PLACEHOLDER FOR SLIDER  -
        ******************************************/
        var setREVStartSize = function() {
            var tpopt = new Object();
                tpopt.startwidth = 1170;
                tpopt.startheight = 650;
                tpopt.container = jQuery('#rev_slider_10_1');
                tpopt.fullScreen = "off";
                tpopt.forceFullWidth="off";
            tpopt.container.closest(".rev_slider_wrapper").css({height:tpopt.container.height()});tpopt.width=parseInt(tpopt.container.width(),0);tpopt.height=parseInt(tpopt.container.height(),0);tpopt.bw=tpopt.width/tpopt.startwidth;tpopt.bh=tpopt.height/tpopt.startheight;if(tpopt.bh>tpopt.bw)tpopt.bh=tpopt.bw;if(tpopt.bh<tpopt.bw)tpopt.bw=tpopt.bh;if(tpopt.bw<tpopt.bh)tpopt.bh=tpopt.bw;if(tpopt.bh>1){tpopt.bw=1;tpopt.bh=1}if(tpopt.bw>1){tpopt.bw=1;tpopt.bh=1}tpopt.height=Math.round(tpopt.startheight*(tpopt.width/tpopt.startwidth));if(tpopt.height>tpopt.startheight&&tpopt.autoHeight!="on")tpopt.height=tpopt.startheight;if(tpopt.fullScreen=="on"){tpopt.height=tpopt.bw*tpopt.startheight;var cow=tpopt.container.parent().width();var coh=jQuery(window).height();if(tpopt.fullScreenOffsetContainer!=undefined){try{var offcontainers=tpopt.fullScreenOffsetContainer.split(",");jQuery.each(offcontainers,function(e,t){coh=coh-jQuery(t).outerHeight(true);if(coh<tpopt.minFullScreenHeight)coh=tpopt.minFullScreenHeight})}catch(e){}}tpopt.container.parent().height(coh);tpopt.container.height(coh);tpopt.container.closest(".rev_slider_wrapper").height(coh);tpopt.container.closest(".forcefullwidth_wrapper_tp_banner").find(".tp-fullwidth-forcer").height(coh);tpopt.container.css({height:"100%"});tpopt.height=coh;}else{tpopt.container.height(tpopt.height);tpopt.container.closest(".rev_slider_wrapper").height(tpopt.height);tpopt.container.closest(".forcefullwidth_wrapper_tp_banner").find(".tp-fullwidth-forcer").height(tpopt.height);}
        };
        /* CALL PLACEHOLDER */
        setREVStartSize();
        var tpj=jQuery;
        tpj.noConflict();
        var revapi10;
        tpj(document).ready(function() {
        if(tpj('#rev_slider_10_1').revolution == undefined){
            revslider_showDoubleJqueryError('#rev_slider_10_1');
        }else{
           revapi10 = tpj('#rev_slider_10_1').show().revolution(
            {   
                                        dottedOverlay:"none",
                delay:9000,
                startwidth:1170,
                startheight:650,
                hideThumbs:0,
                thumbWidth:100,
                thumbHeight:50,
                thumbAmount:3,
                simplifyAll:"off",
                navigationType:"bullet",
                navigationArrows:"none",
                navigationStyle:"round",
                touchenabled:"on",
                onHoverStop:"on",
                nextSlideOnWindowFocus:"off",
                swipe_threshold: 75,
                swipe_min_touches: 1,
                drag_block_vertical: false,
                keyboardNavigation:"on",
                navigationHAlign:"right",
                navigationVAlign:"center",
                navigationHOffset:50,
                navigationVOffset:20,
                soloArrowLeftHalign:"left",
                soloArrowLeftValign:"center",
                soloArrowLeftHOffset:20,
                soloArrowLeftVOffset:0,
                soloArrowRightHalign:"right",
                soloArrowRightValign:"center",
                soloArrowRightHOffset:20,
                soloArrowRightVOffset:0,
                shadow:0,
                fullWidth:"on",
                fullScreen:"off",
                                        spinner:"spinner2",
                stopLoop:"off",
                stopAfterLoops:-1,
                stopAtSlide:-1,
                shuffle:"off",
                autoHeight:"off",
                forceFullWidth:"off",
                hideThumbsOnMobile:"off",
                hideNavDelayOnMobile:1500,
                hideBulletsOnMobile:"on",
                hideArrowsOnMobile:"off",
                hideThumbsUnderResolution:0,
                                        hideSliderAtLimit:0,
                hideCaptionAtLimit:768,
                hideAllCaptionAtLilmit:0,
                startWithSlide:0                    });
                            }
        }); /*ready*/
    </script>
    <script type='text/javascript'>
        /* <![CDATA[ */
        var waves_script_data = {"menu_padding":"33","menu_wid_margin":"28","blog_art_min_width":"230","pageloader":"0","header_height":"80"};
        /* ]]> */
    </script>
    

    <script type='text/javascript' src='./js/themewaves.js'></script>
    <script type='text/javascript' src='./js/waves-script.js'></script>
    <script type='text/javascript' src='./js/contact-form.js'></script>

    <!--<script type='text/javascript' src='/js/Chart.min.js'></script>
    <script type='text/javascript' src='/js/pace.min.js'></script>-->
    
    
    
    <script type='text/javascript' src='./js/smoothscroll.js'></script>

    <!--
    <script type='text/javascript' src='https://maps.googleapis.com/maps/api/js'></script>
    <script type='text/javascript' src='/js/jquery.jplayer.min.js'></script>

    <script type='text/javascript' src='/js/jquery.comingsoon.js'></script>
    <script type='text/javascript' src='/js/jquery.twentytwenty.js'></script>
    <script type='text/javascript' src='/js/nanoscroller-0.7.2.min.js'></script>
    <script type='text/javascript' src='/js/jquery.isotope.min.js'></script>
    <script type='text/javascript' src='/js/jquery.event.move.js'></script>
    <script type='text/javascript' src='/js/jquery.easy-pie-chart.js'></script>-->
    <script type='text/javascript' src='./js/scripts.js'></script>
