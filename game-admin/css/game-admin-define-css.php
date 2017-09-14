<style>	
.ga-gc-widget{ background-color:<?php echo($array_of_options['background-color']); ?>; border-top-left-radius:4px; border-top-right-radius:4px; color:<?php echo($array_of_options['text-color']); ?>;}

.ga-gc-header-image{ width: 100%; opacity: 0.8; filter:alpha(opacity=80); -ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=80)'; -khtml-opacity: 0.8; -moz-opacity: 0.8; }
.ga-gc-title-header { border-top: 1px solid #000; color:<?php echo($array_of_options['gc-text-header-color']); ?>; padding:5px; background-color:<?php echo($array_of_options['gc-header-background-color']); ?>; }
.ga-gc-title-header span { padding: 5px; font-size: 18px !important; display: inline-block !important; height: auto; bottom: 0px; }


.ga-gc-cell{ border-top: 1px solid #000;  padding: 5px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; box-shadow: rgba(255,255,255,0.1) 0 1px 0 inset; line-height: 1.5;}
.ga-gc-cell-strong{ font-weight: 600; }
.ga-gc-cell-header{ background-color:<?php echo($array_of_options['gc-header-background-color-2']); ?>; color:<?php echo($array_of_options['gc-text-header-color']); ?>; font-size: 0.71429rem; line-height: 1; font-size:12px; font-weight: 600; text-transform: uppercase; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000; }

.ga-gc-cell-contenidos { font-size: 0.71429rem; line-height: 1.3; font-weight: 600; text-transform: uppercase; }
.ga-gc-cell a{ color:<?php echo($array_of_options['link-color']); ?>; }
.ga-gc-cell a:hover{ color:<?php echo($array_of_options['link-hover-color']); ?>; }

.ga-gc-nota{ width: 20%; display: inline-block; position: relative; color:<?php echo($array_of_options['text-color-2']); ?>; vertical-align: top; font-size: 16px; text-align: center; }
.ga-gc-plataformas{  color:<?php echo($array_of_options['text-color-2']); ?>; font-size:10px !important; letter-spacing: -0.3px; }


.ga-block-divider {  margin: 10px 0px 10px 0px; width:100%; height: 1px; background: rgba(102, 102, 102, 0.3); }

.ga-after-box-title{ color:<?php echo($array_of_options['re-header-text-color']); ?>; font-weight:600; font-size: 16px; }
.ga-text-color-2{ color:<?php echo($array_of_options['text-color-2']); ?>; }

.ga-rating-block{  padding: 10px; box-sizing: border-box; text-align: left; width:100%; background-color:<?php echo($array_of_options['background-color']); ?>; color:<?php echo($array_of_options['text-color']); ?>; display: table; text-align: justify;}
.ga-rating-block-center{  padding: 10px; box-sizing: border-box; text-align: center; width:100%; background-color:<?php echo($array_of_options['background-color']); ?>; color:<?php echo($array_of_options['text-color']); ?>;}
.ga-rating-box-right { display:inline-block; width:auto; min-width:100px; text-align: center; text-align: center; padding-top:8px; float:right; padding-left: 10px; }
.ga-rating-box-left { display:inline-block; width:auto; min-width:100px; text-align: center; text-align: center; padding-top:8px; float:left; padding-right: 10px; }
.ga-rating-box-center { display:inline-block; width:auto; min-width:100px; text-align: center; text-align: center; padding-top:8px; }
.ga-container-nota { display:inline-block; font-weight:bold; font-size:36px; background:<?php echo($array_of_options['re-rating-background-color']); ?>; border-radius:0%; <?php if($array_of_options['re-border']!='0'){ ?>border: 1px <?php echo($array_of_options['re-border']); ?> <?php echo($array_of_options['re-rating-border-color']); ?>;<?php } ?> box-sizing: border-box; min-width:80px; text-align: center; padding: 1.42857rem; }
.ga-review-description{ display:block; font-size: 12px; font-weight: 600; text-transform: uppercase; text-align: center;}

.ga-rhi{ position:absolute; top:10px; padding: 1.42857rem; color:<?php echo($array_of_options['rhi-text-color']); ?>; background:<?php echo($array_of_options['rhi-background-color']); ?>; <?php if($array_of_options['rhi-border']!='0'){ ?>border: 1px <?php echo($array_of_options['rhi-border']); ?> <?php echo($array_of_options['rhi-border-color']); ?>;<?php } ?> z-index:100; font-size: 36px; font-weight: bold; }
.ga-rhi-stars{ position:absolute; padding: 7px 7px 7px 7px; top:10px; color:<?php echo($array_of_options['rhi-text-color']); ?>; background:<?php echo($array_of_options['rhi-background-color']); ?>; <?php if($array_of_options['rhi-border']!='0'){ ?>border: 1px <?php echo($array_of_options['rhi-border']); ?> <?php echo($array_of_options['rhi-border-color']); ?>;<?php } ?> z-index:100; }
.ga-rhi-stars-inside-rating{  font-family: FontAwesome; position: relative; display: inline-block; font-size:18px;  text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.75);   }

div.ga-rhi-stars-inside-rating:before { content: "\f006\ \f006\ \f006\ \f006\ \f006"; }
div.ga-rhi-stars-inside-rating:after { position: absolute; left: 0; }

.ga-r-0:before { content: "" !important; }
.ga-r-05:after { content: "\f089\ \00a0"; }
.ga-r-1:after { content: "\f005"; }
.ga-r-15:after { content: "\f005\ \f089\ \00a0"; }
.ga-r-2:after { content: "\f005\ \f005"; }
.ga-r-25:after { content: "\f005\ \f005\ \f089\ \00a0"; }
.ga-r-3:after { content: "\f005\ \f005\ \f005"; }
.ga-r-35:after { content: "\f005\ \f005\ \f005\ \f089\ \00a0"; }
.ga-r-4:after { content: "\f005\ \f005\ \f005\ \f005"; }
.ga-r-45:after { content: "\f005\ \f005\ \f005\ \f005\ \f089\ \00a0"; }
.ga-r-5:after { content: "\f005\ \f005\ \f005\ \f005\ \f005"; }	
</style>