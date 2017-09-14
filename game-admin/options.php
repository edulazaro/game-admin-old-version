<?php

//echo(plugin_dir_path( __FILE__ ));
//echo(get_plugin_data());

foreach ($_POST as $clave => $valor) { $_POST[$clave] = esc_sql($_POST[$clave]); }

/* ******************************************************* OPCIONES => GUARDAR ********************************************************* */
/* ******************************************************* OPCIONES => GUARDAR ********************************************************* */
/* ******************************************************* OPCIONES => GUARDAR ********************************************************* */

if( isset($_POST['submit_guardar']) ){

	$array_of_options = array(
		'colorscheme' => $_POST['ga-colorscheme'],
		'background-color' => $_POST['ga-background-color'],
		'text-color' => $_POST['ga-text-color'],
		'text-color-2' => $_POST['ga-text-color-2'],
		'link-color' => $_POST['ga-link-color'],
		'link-hover-color' => $_POST['ga-link-hover-color'],			
		'include-font-awesome' => $_POST['ga-include-font-awesome'],
		'gc-header-background-color' => $_POST['ga-gc-header-background-color'],
		'gc-header-background-color-2' => $_POST['ga-gc-header-background-color-2'],
		'gc-text-header-color' => $_POST['ga-gc-text-header-color'],
	

		'rhi-display' => $_POST['ga-rhi-display'],
		'rhi-position' => $_POST['rhi-position'],
		'rhi-display-as' => $_POST['ga-rhi-display-as'],
		'rhi-border' => $_POST['ga-rhi-border'],
		'rhi-text-color' => $_POST['ga-rhi-text-color'],
		'rhi-background-color' => $_POST['ga-rhi-background-color'],
		'rhi-border-color' => $_POST['ga-rhi-border-color'],
		
		
		're-header-text-color' => $_POST['ga-re-header-text-color'],
		're-rating-background-color' => $_POST['ga-re-rating-background-color'],
		're-border' => $_POST['ga-re-border'],		
		're-rating-border-color' => $_POST['ga-re-rating-border-color'],
		
		'worstrating' => $_POST['ga-gc-worstrating'],
		'bestrating' => $_POST['ga-gc-bestrating'],		
		'includerating' => $_POST['ga-gc-includerating'],
		'reviewstructureddata' => $_POST['ga-gc-reviewstructureddata'],
		'showratingdescription' => $_POST['ga-gc-showratingdescription'],
		'ratingboxalignment' => $_POST['ga-gc-ratingboxalignment'],
		
		'ga-reviewdescription-0' => $_POST['ga-reviewdescription-0'], 'ga-reviewdescription-10' => $_POST['ga-reviewdescription-10'], 'ga-reviewdescription-20' => $_POST['ga-reviewdescription-20'], 'ga-reviewdescription-30' => $_POST['ga-reviewdescription-30'], 'ga-reviewdescription-40' => $_POST['ga-reviewdescription-40'],
		'ga-reviewdescription-50' => $_POST['ga-reviewdescription-50'], 'ga-reviewdescription-60' => $_POST['ga-reviewdescription-60'], 'ga-reviewdescription-70' => $_POST['ga-reviewdescription-70'], 'ga-reviewdescription-80' => $_POST['ga-reviewdescription-80'], 'ga-reviewdescription-90' => $_POST['ga-reviewdescription-90'], 'ga-reviewdescription-100' => $_POST['ga-reviewdescription-100'],
		
		'header-image-crop-single' => $_POST['ga-header-image-crop-single'],
		'header-image-crop-archive' => $_POST['ga-header-image-crop-archive']
		
	);
	update_option( 'gameadmin_options', $array_of_options );
	update_option( 'gameadmin_version', '1.0.0' );
	?>
	<p style="color:green;"><?php echo __( 'Options saved.', 'game-admin'); ?></p>
	<?php
}	




/* ******************************************************* OPCIONES => MOSTRAR ********************************************************* */
/* ******************************************************* OPCIONES => MOSTRAR ********************************************************* */
/* ******************************************************* OPCIONES => MOSTRAR ********************************************************* */
$array_of_options = get_option( 'gameadmin_options' );
?>

<script type="text/javascript">

function mostrarOcultarColores(){

	var myElements = document.querySelectorAll(".div-personalizar-colores");
	
	if(document.getElementById('ga-colorscheme').value=='define'){
		for (var i = 0; i < myElements.length; i++) {
			myElements[i].style.position= 'relative';  myElements[i].style.visibility= 'visible';
		}
	} else{
		for (var i = 0; i < myElements.length; i++) {
			myElements[i].style.position= 'absolute';  myElements[i].style.visibility= 'hidden';
		}	
	}
}

function validarNota(e, id){
	var tecla = (document.all) ? e.keyCode : e.which;
	var te = String.fromCharCode(tecla);
	var cadena = (document.getElementById(id).value + te);
    var RE = /^\d*\.?\d*$/;
    if(RE.test(cadena)){ return true; }else{ return false; }
}	
</script>

<style> h3 { font-size: 14px; padding-left: 12px; cursor:pointer; } .ga-admin-block{ position:relative; display:block; padding:10px; padding-top:0px; } .ga-admin-span-description { position:relative; display:block; padding-bottom:10px; padding-left:10px; font-style:italic; } .enlacetipo{ text-decoration:underline; cursor:pointer; color:#000000; } .enlacetipo:hover{ color:#a2a2a2; } .wp-picker-container{ padding: 2px; line-height: 28px; height: 28px; vertical-align: middle; } .iris-picker{ z-index:100; }</style>
 
<div style="position:relative; float:left;  width:100%; height:auto; text-align:left; font-size: 24px; color:#4a4a4a;">
	<h2><?php echo __( 'GameAdmin Plugin Options', 'game-admin'); ?></h3>
</div>

<form method="post" >

<div class="postbox-container" style="width:100%">
	<div class="postbox">
		<h3><?php echo __( 'General appearance (Styles)', 'game-admin'); ?></h3>
		<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
		</div>	
		<div class="inside">
			<p>
				<b><?php echo __( 'Color scheme', 'game-admin'); ?></b>
			</p>						
			<div class="ga-admin-block">
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Select scheme', 'game-admin'); ?></label><br/>
					<select name="ga-colorscheme" id="ga-colorscheme" onchange="mostrarOcultarColores();">
						<option value="dark" <?php if($array_of_options['colorscheme']=='dark'){ echo('selected'); } ?> ><?php echo __('Dark', 'game-admin'); ?></option>
						<option value="bright" <?php if($array_of_options['colorscheme']=='bright'){ echo('selected'); } ?>><?php echo __('Bright', 'game-admin'); ?></option>
						<option value="define" <?php if($array_of_options['colorscheme']=='define'){ echo('selected'); } ?>><?php echo __('Customize colors', 'game-admin'); ?></option>
						<option value="clean"<?php if($array_of_options['colorscheme']=='clean'){ echo('selected'); } ?> ><?php echo __('None (current WordPress theme)', 'game-admin'); ?></option>
					</select>
				</div>
				<span class="ga-admin-span-description" ><?php echo __('Select one of the included stylesheets, use default colors of your theme or select customize colors to display more options in this panel'); ?></span>
			</div>
			<div class="div-personalizar-colores" style="display:inline-block; <?php if($array_of_options['colorscheme']=='define'){ echo("position:relative; visibility:visible;"); } else { echo("position:absolute; visibility:hidden;"); } ?>">
				<p>
					<b><?php echo __( 'Customize style: General styles', 'game-admin'); ?></b>
				</p>
				<div class="ga-admin-block" style="padding-bottom: 0px;" >
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Background color', 'game-admin'); ?></label><br/>
						<input name="ga-background-color" class="gameadmin-input-color" value="<?php echo($array_of_options['background-color']); ?>"/>
					</div>
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Text color', 'game-admin'); ?></label><br/>
						<input name="ga-text-color" class="gameadmin-input-color" value="<?php echo($array_of_options['text-color']); ?>"/>
					</div>
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Text color (2)', 'game-admin'); ?></label><br/>
						<input name="ga-text-color-2" class="gameadmin-input-color" value="<?php echo($array_of_options['text-color-2']); ?>"/>
					</div>
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Link color', 'game-admin'); ?></label><br/>
						<input name="ga-link-color" class="gameadmin-input-color" value="<?php echo($array_of_options['link-color']); ?>"/>
					</div>
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Link color (hover)', 'game-admin'); ?></label><br/>
						<input name="ga-link-hover-color" class="gameadmin-input-color" value="<?php echo($array_of_options['link-hover-color']); ?>"/>
					</div>
					<span class="ga-admin-span-description" ><?php echo __('Set general colors of some elements included with this plugin'); ?></span>
				</div>				
				<p>
					<b><?php echo __( 'Customize style: Game Content Widget', 'game-admin'); ?></b>
				</p>				
				<div class="ga-admin-block" style="padding-bottom: 0px;" >
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Header background', 'game-admin'); ?></label><br/>
						<input name="ga-gc-header-background-color" class="gameadmin-input-color" value="<?php echo($array_of_options['gc-header-background-color']); ?>"/>
					</div>
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Header background (2)', 'game-admin'); ?></label><br/>
						<input name="ga-gc-header-background-color-2" class="gameadmin-input-color" value="<?php echo($array_of_options['gc-header-background-color-2']); ?>"/>
					</div>
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Header text color', 'game-admin'); ?></label><br/>
						<input name="ga-gc-text-header-color" class="gameadmin-input-color" value="<?php echo($array_of_options['gc-text-header-color']); ?>"/>
					</div>
					<span class="ga-admin-span-description" ><?php echo __('Set some colors of the Game Content Widget included with this plugin'); ?></span>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="postbox-container" style="width:100%">
	<div class="postbox">
		<h3><?php echo __( 'Fonts', 'game-admin'); ?></h3>
		<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
		</div>	
		<div class="inside">
			<p>
				<b><?php echo __( 'Font Awesome', 'game-admin'); ?></b>
			</p>						
			<div class="ga-admin-block">
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Source', 'game-admin'); ?></label><br/>
					<select name="ga-include-font-awesome" id="ga-include-font-awesome">
						<option value="gameadmin" <?php if($array_of_options['include-font-awesome']=='gameadmin'){ echo('selected'); } ?> ><?php echo __('Game Admin', 'game-admin'); ?></option>
						<option value="bootstrapcdn" <?php if($array_of_options['include-font-awesome']=='bootstrapcdn'){ echo('selected'); } ?>><?php echo ('Bootstrap CDN'); ?></option>
						<option value="0" <?php if($array_of_options['include-font-awesome']=='0'){ echo('selected'); } ?>><?php echo __('Already included in my theme', 'game-admin'); ?></option>
					</select>
				</div>
				<span class="ga-admin-span-description" ><?php echo __('Font Awesome and related CSS file is needed for some elements of this plugin; if it is alrady included in your theme, select this option', 'game-admin'); ?></span>
			</div>
		</div>
	</div>
</div>

<div class="postbox-container" style="width:100%">
	<div class="postbox">
		<h3><?php echo __( 'Review configuration', 'game-admin'); ?></h3>
		<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
		</div>
		<div class="inside">
			<p>
				<b><?php echo __( 'General reviews configuration', 'game-admin'); ?></b>
			</p>						
			<div class="ga-admin-block">
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Worst rating', 'game-admin'); ?></label><br/>
					<input name="ga-gc-worstrating" id="ga-gc-worstrating" value="<?php echo($array_of_options['worstrating']); ?>" style="100px" maxlength="20" onkeypress="return validarNota(event, 'ga-gc-worstrating'); "/>
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Best rating', 'game-admin'); ?></label><br/>
					<input name="ga-gc-bestrating" id="ga-gc-bestrating" value="<?php echo($array_of_options['bestrating']); ?>" style="100px" maxlength="20" onkeypress="return validarNota(event, 'ga-gc-bestrating'); "/>
				</div>
				<span class="ga-admin-span-description" ><?php echo __('Input your overall rating range for reviews', 'game-admin'); ?></span>
			</div>
			<div class="ga-admin-block" style="padding-bottom: 0px;" >
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 0% - 9%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-0" id="ga-reviewdescription-0" value="<?php echo($array_of_options['ga-reviewdescription-0']); ?>" style="200px" maxlength="100" />
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 10% - 19%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-10" id="ga-reviewdescription-10" value="<?php echo($array_of_options['ga-reviewdescription-10']); ?>" style="200px" maxlength="100" />
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 20% - 29%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-20" id="ga-reviewdescription-20" value="<?php echo($array_of_options['ga-reviewdescription-20']); ?>" style="200px" maxlength="100" />
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 30% - 39%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-30" id="ga-reviewdescription-30" value="<?php echo($array_of_options['ga-reviewdescription-30']); ?>" style="200px" maxlength="100" />
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 40% - 49%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-40" id="ga-reviewdescription-40" value="<?php echo($array_of_options['ga-reviewdescription-40']); ?>" style="200px" maxlength="100" />
				</div>
			</div>
			<div class="ga-admin-block" >
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 50% - 59%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-50" id="ga-reviewdescription-50" value="<?php echo($array_of_options['ga-reviewdescription-50']); ?>" style="200px" maxlength="100" />
				</div>				
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 60% 69%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-60" id="ga-reviewdescription-60" value="<?php echo($array_of_options['ga-reviewdescription-60']); ?>" style="200px" maxlength="100" />
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 70% - 79%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-70" id="ga-reviewdescription-70" value="<?php echo($array_of_options['ga-reviewdescription-70']); ?>" style="200px" maxlength="100" />
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 80% - 89%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-80" id="ga-reviewdescription-80" value="<?php echo($array_of_options['ga-reviewdescription-80']); ?>" style="200px" maxlength="100" />
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 90% - 99%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-90" id="ga-reviewdescription-90" value="<?php echo($array_of_options['ga-reviewdescription-90']); ?>" style="200px" maxlength="100" />
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Rating description: 100%', 'game-admin'); ?></label><br/>
					<input name="ga-reviewdescription-100" id="ga-reviewdescription-100" value="<?php echo($array_of_options['ga-reviewdescription-100']); ?>" style="200px" maxlength="100" />
				</div>	
				<span class="ga-admin-span-description" ><?php echo __('Input a representative word or comment for each rating range', 'game-admin'); ?></span>
			</div>		
			<p>
				<b><?php echo __('Review details (after post)', 'game-admin'); ?></b>
			</p>
			<div class="ga-admin-block" >
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Include review details and rating after post', 'game-admin'); ?></label><br/>
					<select name="ga-gc-includerating" id="ga-gc-includerating" style="min-width:350px;">
						<option value="1" <?php if($array_of_options['includerating']=='1'){ echo('selected'); } ?>><?php echo __( 'Enable', 'game-admin'); ?></option>
						<option value="0" <?php if($array_of_options['includerating']=='0'){ echo('selected'); } ?>><?php echo __( 'Disable', 'game-admin'); ?></option>
					</select>
				</div>
				<div id="div-ga-gc-reviewstructureddata" style="display:inline-block; padding: 10px; <?php if($array_of_options['includerating']=='1'){ echo("position:relative; visibility:visible;"); } else { echo("position:absolute; visibility:hidden;"); } ?>">
					<label><?php echo __('Schema.org structured data', 'game-admin'); ?></label><br/>
					<select name="ga-gc-reviewstructureddata" id="ga-gc-reviewstructureddata" style="min-width:350px;">
						<option value="1" <?php if($array_of_options['reviewstructureddata']=='1'){ echo('selected'); } ?>><?php echo __( 'Enable', 'game-admin'); ?></option>
						<option value="0" <?php if($array_of_options['reviewstructureddata']=='0'){ echo('selected'); } ?>><?php echo __( 'Disable', 'game-admin'); ?></option>
					</select>
				</div>
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Show rating description', 'game-admin'); ?></label><br/>
					<select name="ga-gc-showratingdescription" id="ga-gc-showratingdescription" style="width:200px;">
						<option value="1" <?php if($array_of_options['showratingdescription']=='1'){ echo('selected'); } ?>><?php echo __( 'Enable', 'game-admin'); ?></option>
						<option value="0" <?php if($array_of_options['showratingdescription']=='0'){ echo('selected'); } ?>><?php echo __( 'Disable', 'game-admin'); ?></option>
					</select>
				</div>
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Rating box alignment', 'game-admin'); ?></label><br/>
					<select name="ga-gc-ratingboxalignment" id="ga-gc-ratingboxalignment" style="width:200px;">
						<option value="right" <?php if($array_of_options['ratingboxalignment']=='right'){ echo('selected'); } ?>><?php echo __( 'Right', 'game-admin'); ?></option>
						<option value="left" <?php if($array_of_options['ratingboxalignment']=='left'){ echo('selected'); } ?>><?php echo __( 'Left', 'game-admin'); ?></option>
						<option value="center" <?php if($array_of_options['ratingboxalignment']=='center'){ echo('selected'); } ?>><?php echo __( 'Center', 'game-admin'); ?></option>
					</select>
				</div>
				<span class="ga-admin-span-description" ><?php echo __('Input if you want to include rating block after each review and if you want to include structured data (only with review details after post active)'); ?></span>
			</div>
			<div class="ga-admin-block div-personalizar-colores" style="<?php if($array_of_options['colorscheme']=='define'){ echo("position:relative; visibility:visible;"); } else { echo("position:absolute; visibility:hidden;"); } ?>">
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Header text color', 'game-admin'); ?></label><br/>
					<input name="ga-re-header-text-color" class="gameadmin-input-color" value="<?php echo($array_of_options['re-header-text-color']); ?>"/>
				</div>
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Rating background color', 'game-admin'); ?></label><br/>
					<input name="ga-re-rating-background-color" class="gameadmin-input-color" value="<?php echo($array_of_options['re-rating-background-color']); ?>"/>
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Border', 'game-admin'); ?></label><br/>
					<select name="ga-re-border" id="ga-re-border" style="width:200px;">
						<option value="0" <?php if($array_of_options['re-border']=='0'){ echo('selected'); } ?>><?php echo __( 'Do not include', 'game-admin'); ?></option>
						<option value="solid" <?php if($array_of_options['re-border']=='solid'){ echo('selected'); } ?>><?php echo __( 'Include: Solid', 'game-admin'); ?></option>
						<option value="dashed" <?php if($array_of_options['re-border']=='dashed'){ echo('selected'); } ?>><?php echo __( 'Include: Dashed', 'game-admin'); ?></option>
						<option value="dotted" <?php if($array_of_options['re-border']=='dotted'){ echo('selected'); } ?>><?php echo __( 'Include: Dotted', 'game-admin'); ?></option>
					</select>					
				</div>					
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Rating border color', 'game-admin'); ?></label><br/>
					<input name="ga-re-rating-border-color" class="gameadmin-input-color" value="<?php echo($array_of_options['re-rating-border-color']); ?>"/>
				</div>
				<span class="ga-admin-span-description" ><?php echo __('Set colors of the rating block after posts (only when custilized colors are set in the color scheme option)'); ?></span>
			</div>			
			<p>
				<b><?php echo __( 'Header image rating', 'game-admin'); ?></b>
			</p>
			<div class="ga-admin-block" >

				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Header image rating', 'game-admin'); ?></label><br/>
					<select name="ga-rhi-display" id="ga-rhi-display" style="width:200px;">
						<option value="0" <?php if($array_of_options['rhi-display']=='0'){ echo('selected'); } ?>><?php echo __( 'Do not include', 'game-admin'); ?></option>
						<option value="single" <?php if($array_of_options['rhi-display']=='single'){ echo('selected'); } ?>><?php echo __( 'Include: Only single posts', 'game-admin'); ?></option>
						<option value="archive" <?php if($array_of_options['rhi-display']=='archive'){ echo('selected'); } ?>><?php echo __( 'Include: Only post archives', 'game-admin'); ?></option>
						<option value="singlearchive" <?php if($array_of_options['rhi-display']=='singlearchive'){ echo('selected'); } ?>><?php echo __( 'Include: Single posts and archives', 'game-admin'); ?></option>
					</select>					
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Postition', 'game-admin'); ?></label><br/>
					<select name="rhi-position" id="ga-rhi-position" style="width:200px;">
						<option value="left" <?php if($array_of_options['rhi-position']=='left'){ echo('selected'); } ?>><?php echo __( 'Include: left', 'game-admin'); ?></option>
						<option value="right" <?php if($array_of_options['rhi-position']=='right'){ echo('selected'); } ?>><?php echo __( 'Include: right', 'game-admin'); ?></option>
					</select>					
				</div>				
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Display rating as', 'game-admin'); ?></label><br/>
					<select name="ga-rhi-display-as" id="ga-rhi-display-as" style="width:200px;">
						<option value="number" <?php if($array_of_options['rhi-display-as']=='number'){ echo('selected'); } ?>><?php echo __( 'Number', 'game-admin'); ?></option>
						<option value="stars" <?php if($array_of_options['rhi-display-as']=='stars'){ echo('selected'); } ?>><?php echo __( 'Stars', 'game-admin'); ?></option>
					</select>					
				</div>
				<span class="ga-admin-span-description" ><?php echo __('Set if you want to include a nice little block with rating inside header images and if it should be visible for single posts or also for listings (categories and archives). Important: the_post_thumbnail standard function is expected to be used in your theme'); ?></span>
			</div>
			<div class="ga-admin-block div-personalizar-colores" style="<?php if($array_of_options['colorscheme']=='define'){ echo("position:relative; visibility:visible;"); } else { echo("position:absolute; visibility:hidden;"); } ?>">
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Text color', 'game-admin'); ?></label><br/>
					<input name="ga-rhi-text-color" class="gameadmin-input-color" value="<?php echo($array_of_options['rhi-text-color']); ?>"/>
				</div>				
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Background color', 'game-admin'); ?></label><br/>
					<input name="ga-rhi-background-color" class="gameadmin-input-color" value="<?php echo($array_of_options['rhi-background-color']); ?>"/>
				</div>
				<div style="position:relative; display:inline-block; padding:10px;">
					<label><?php echo __('Border', 'game-admin'); ?></label><br/>
					<select name="ga-rhi-border" id="ga-rhi-border" style="width:200px;">
						<option value="0" <?php if($array_of_options['rhi-border']=='0'){ echo('selected'); } ?>><?php echo __( 'Do not include', 'game-admin'); ?></option>
						<option value="solid" <?php if($array_of_options['rhi-border']=='solid'){ echo('selected'); } ?>><?php echo __( 'Include: Solid', 'game-admin'); ?></option>
						<option value="dashed" <?php if($array_of_options['rhi-border']=='dashed'){ echo('selected'); } ?>><?php echo __( 'Include: Dashed', 'game-admin'); ?></option>
						<option value="dotted" <?php if($array_of_options['rhi-border']=='dotted'){ echo('selected'); } ?>><?php echo __( 'Include: Dotted', 'game-admin'); ?></option>
					</select>					
				</div>						
				<div style="position:relative; display:inline-block; padding: 10px;">
					<label><?php echo __( 'Border color', 'game-admin'); ?></label><br/>
					<input name="ga-rhi-border-color" class="gameadmin-input-color" value="<?php echo($array_of_options['rhi-border-color']); ?>"/>
				</div>		
				<span class="ga-admin-span-description" ><?php echo __('Customize colors of the the rating block over header images(only when custilized colors are set in the color scheme option)'); ?></span>
			</div>			
		</div>
	</div>

	<div class="postbox-container" style="width:100%">
		<div class="postbox">
			<h3><?php echo __( 'Other options', 'game-admin'); ?></h3>
			<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
			</div>	
			<div class="inside">
				<p>
					<b><?php echo __( 'Header image (thumbnail) crop', 'game-admin'); ?></b>
				</p>						
				<div class="ga-admin-block">
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Crop image (single posts)', 'game-admin'); ?></label><br/>
						<input name="ga-header-image-crop-single" id="ga-header-image-crop-single" value="<?php echo($array_of_options['header-image-crop-single']); ?>" style="100px" maxlength="4" onkeypress="return event.charCode >= 48 && event.charCode <= 57"/>
					</div>
					<div style="position:relative; display:inline-block; padding: 10px;">
						<label><?php echo __( 'Crop image (archive posts)', 'game-admin'); ?></label><br/>
						<input name="ga-header-image-crop-archive" id="ga-header-image-crop-archive" value="<?php echo($array_of_options['header-image-crop-archive']); ?>" style="100px" maxlength="4" onkeypress="return event.charCode >= 48 && event.charCode <= 57"/>
					</div>					
					<span class="ga-admin-span-description" ><?php echo __('Input values in pixels to crop image height in single posts and listings. Leave empty values if you want to use your theme values or default values. Important: the_post_thumbnail standard function is expected to be used in your theme', 'game-admin'); ?></span>
				</div>
			</div>
		</div>
	</div>



<p>
	<div style="position:relative; display:inline-block;">
		<input type="submit" name="submit_guardar" value="<?php echo __( 'Save', 'game-admin'); ?>"> </input>
	</div>
</p>

</form>