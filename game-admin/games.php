
<script type="text/javascript">
function mostrarSelectNuevoJuegoExpansion(){
	if(document.getElementById('field_newgame_seleccionar_esexpansion').value=='0'){
		document.getElementById("frame_newgame_expansion_plataforma").style.display= 'none';
		document.getElementById("frame_newgame_expansion_letra").style.display= 'none';
		document.getElementById("frame_newgame_listado_juegos").style.display= 'none';
		document.getElementById("field_newgame_seleccionar_juego").value= '0';
	
	} else{
		document.getElementById("frame_newgame_expansion_plataforma").style.display= 'inline-block';
		document.getElementById("frame_newgame_expansion_letra").style.display= 'inline-block';
		document.getElementById("frame_newgame_listado_juegos").style.display= 'inline-block';
	}
}

function mostrarSelectEditarJuegoExpansion(){
	if(document.getElementById('field_editgame_seleccionar_esexpansion').value=='0'){
		document.getElementById("frame_editgame_expansion_plataforma").style.display= 'none';
		document.getElementById("frame_editgame_expansion_letra").style.display= 'none';
		document.getElementById("frame_editgame_listado_juegos").style.display= 'none';
		document.getElementById("field_editgame_seleccionar_juego").value= '0';
	
	} else{
		document.getElementById("frame_editgame_expansion_plataforma").style.display= 'inline-block';
		document.getElementById("frame_editgame_expansion_letra").style.display= 'inline-block';
		document.getElementById("frame_editgame_listado_juegos").style.display= 'inline-block';
	}
}

function loadListadoNewGameJuegos(){
	jQuery.ajax({
	type:"POST",
	dataType: 'html',
	url: '<?php echo admin_url('admin-ajax.php'); ?>',
	data: { action: "obtenerListaDeJuegos", plataforma: document.getElementById('form_newgame_expansion_plataforma').value, letra: document.getElementById('form_newgame_expansion_letra').value, origen:'games_newgameexpansion' },
	success:function(response){
		jQuery("#frame_newgame_listado_juegos").html(response);
	}
	});
}	

function loadListadoEditGameJuegos(){
	jQuery.ajax({
	type:"POST",
	dataType: 'html',
	url: '<?php echo admin_url('admin-ajax.php'); ?>',
	data: { action: "obtenerListaDeJuegos", plataforma: document.getElementById('form_editgame_expansion_plataforma').value, letra: document.getElementById('form_editgame_expansion_letra').value, origen:'games_editgameexpansion' },
	success:function(response){
		jQuery("#frame_editgame_listado_juegos").html(response);
	}
	});
}	

//Image Uploader
(function( $ ) {
	'use strict';

	/* global wp, console */
	var file_frame, image_data;

	$(function() {
		/**
		 * If an instance of file_frame already exists, then we can open it
		 * rather than creating a new instance.
		 */
		if ( undefined !== file_frame ) {
			file_frame.open();
			return;
		}

		/**
		 * If we're this far, then an instance does not exist, so we need to
		 * create our own.
		 *
		 * Here, use the wp.media library to define the settings of the Media
		 * Uploader implementation by setting the title and the upload button
		 * text. We're also not allowing the user to select more than one image.
		 */
		file_frame = wp.media.frames.file_frame = wp.media({
			title:    "Insert Media",    // For production, this needs i18n.
			button:   {
				text: "<?php echo __( 'Accept', 'game-admin'); ?>"     // For production, this needs i18n.
			},
			multiple: false
		});

		/**
		 * Setup an event handler for what to do when an image has been
		 * selected.
		 */
		file_frame.on( 'select', function() {

			image_data = file_frame.state().get( 'selection' ).first().toJSON();

			if(image_data['id'] && image_data['url'] && (clickimagen=='creando')){
				document.getElementById("field_newgame_imagen_id").value = image_data['id'];
				document.getElementById("field_newgame_imagen").value = image_data['url'];
				document.getElementById("contenedor_imagen_creando").innerHTML="<img src='"+image_data['url']+"' style='max-width:360px;' >";
			} else if(image_data['id'] && image_data['url'] && (clickimagen=='editando')){
				document.getElementById("field_editgame_imagen_id").value = image_data['id'];
				document.getElementById("field_editgame_imagen").value = image_data['url'];
				document.getElementById("contenedor_imagen_editando").innerHTML="<img src='"+image_data['url']+"' style='max-width:360px;' >";
			}
		});

		// Now display the actual file_frame
		
		$("#boton_newgame_imagen" ).click(function() {
			clickimagen= 'creando';
			file_frame.open();
		});
		$("#boton_editgame_imagen" ).click(function() {
			clickimagen= 'editando';
			file_frame.open();
		});		
		//('#'). file_frame.open();

	});

})( jQuery );

var clickimagen='creando';
 </script>
 
<?php 
wp_enqueue_media();
 ?>
 
<style>
h3 { font-size: 14px; padding-left: 12px; cursor:pointer; }
.enlacejuego{ text-decoration:underline; cursor:pointer; color:#000000; }
.enlacejuego:hover{ color:#a2a2a2; }
</style>

<?php
/* ******************************************************* CODIGO COMPARTIDO ********************************************************* */
/* ******************************************************* CODIGO COMPARTIDO ********************************************************* */
/* ******************************************************* CODIGO COMPARTIDO ********************************************************* */	
?>

<div style="position:relative; float:left;  width:100%; height:auto; text-align:left; font-size: 24px; color:#4a4a4a;">
		<h2>Game Admin</h3>
</div>
<?php

if (isset($_POST['field_list_plataforma']) && ($_POST['field_list_plataforma']!= null)){ $plataforma=$_POST['field_list_plataforma']; }
else if (isset($_POST['field_editgame_list_plataforma']) && ($_POST['field_editgame_list_plataforma']!= null)){ $plataforma=$_POST['field_editgame_list_plataforma']; }
else { $plataforma='-'; }

if (isset($_POST['field_list_letra']) && ($_POST['field_list_letra']!=null)){ $letra=$_POST['field_list_letra']; }
else if (isset($_POST['field_editgame_list_letra']) && ($_POST['field_editgame_list_letra']!=null)){ $letra=$_POST['field_editgame_list_letra']; }
else{ $letra="-"; }

//Almaceno temoralmente los codigos y nombres de plataformas porque es algo que se usa repetidas veces
$resultadosplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` ORDER BY nombre");
foreach ( $resultadosplataformas as $rowplataformas ){ 
	$arrayplataformas[$rowplataformas->id]=$rowplataformas->nombre;
}

/* ********************************************* PROCESAR JUEGOS => AGREGAR *********************************************** */

	if( isset($_POST['field_newgame_submit']) ){
		
		if((isset($_POST['field_newgame_nombre'])) && ($_POST['field_newgame_nombre']!="") && ($_POST['field_newgame_nombre']!=null)) {
				
			if( (!isset($_POST['field_newgame_seleccionar_juego'])) || ($_POST['field_newgame_seleccionar_juego']=='0') ) { $_POST['field_newgame_seleccionar_juego']=null; }
			
			$wpdb->get_results("INSERT INTO `{$wpdb->prefix}gameadmin_juegos` (nombre, ano, idimagen, idexpansion) VALUES ('".$_POST['field_newgame_nombre']."','".$_POST['field_newgame_ano']."','".$_POST['field_newgame_imagen_id']."','".$_POST['field_newgame_seleccionar_juego']."')");
			$lastid = $wpdb->insert_id;
			
			$resultados = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas`");
			foreach ( $resultados as $row ){
				if(isset($_POST["plataforma_juego_id_".$row->id]) && ($_POST["plataforma_juego_id_".$row->id] == true)){
						$wpdb->get_results("INSERT INTO `{$wpdb->prefix}gameadmin_juegoplataforma` (idjuego, idcontenido, idplataforma)  VALUES ('".$lastid."', '0', '".$row->id."') ");
				}
			}
			?>
			<p style="color:green;"><?php echo __( 'Game inserted correctly.', 'game-admin'); ?></p>
			<?php
		} else {
			?>
			<p style="color:red;"><b><?php echo __( 'Error:', 'game-admin'); ?></b> <?php echo __( 'You must fill in the name of the game before adding it.', 'game-admin'); ?></p>
			<?php
		}
	}
	
/* ********************************************* PROCESAR JUEGOS => ACTUALIZAR *********************************************** */		

	if(isset($_POST['field_editgame_submit'])){
		
		
		if( (!isset($_POST['field_editgame_seleccionar_juego'])) ||  ($_POST['field_editgame_seleccionar_juego']=='0') ) { $_POST['field_editgame_seleccionar_juego']=null; }
		//Actualizo nombre y ano
		$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_juegos` SET nombre='".$_POST['field_editgame_nombre']."', ano='".$_POST['field_editgame_ano']."', idimagen='".$_POST['field_editgame_imagen_id']."', idexpansion='".$_POST['field_editgame_seleccionar_juego']."' WHERE  id='".$_POST['field_editgame_list_id']."' ");

		foreach ($arrayplataformas as $clave => $valor) {
			
			$plataforma_field_id="field_editgame_plataforma_id_".$clave;
			
			$wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$_POST['field_editgame_list_id']."' AND idcontenido='0' AND idplataforma='".$clave."' ");	
			if($wpdb->num_rows ==0){
				$existe=false;
			} else {
				$existe= true;
			}
			//si la paltaforma estaba marcada
			if ( (isset($_POST[$plataforma_field_id])) &&  ($existe==false) ) {
				$wpdb->get_results("INSERT INTO `{$wpdb->prefix}gameadmin_juegoplataforma` (idjuego, idcontenido, idplataforma) VALUES ('".$_POST['field_editgame_list_id']."', '0', '".$clave."')");
			} else if( (!isset($_POST[$plataforma_field_id])) && ($existe==true) ) {
				$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$_POST['field_editgame_list_id']."' AND idcontenido='0' AND idplataforma='".$clave."' ");
			}
		}
		?>
		<p style="color:green;"><?php echo __( 'Game edited correctly.', 'game-admin'); ?></p>
		<?php
	}

	/* ********************************************* PROCESAR JUEGOS => ELIMINAR *********************************************** */		

	if(isset($_POST['field_deletegame_submit'])){
		
		$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_juegos` SET idexpansion='0' WHERE  idexpansion='".$_POST['field_editgame_list_id']."' ");
		$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$_POST['field_editgame_list_id']."' ");
		$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$_POST['field_editgame_list_id']."' ");
		$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_juegos` WHERE id='".$_POST['field_editgame_list_id']."' ");		
		
		?>
		<p style="color:green;">Juego eliminado correctamente.</p>
		<?php
	}

/* ******************************************************* FORMULARIO EDITAR JUEGO ********************************************************* */
/* ******************************************************* FORMULARIO EDITAR JUEGO ********************************************************* */
/* ******************************************************* FORMULARIO EDITAR JUEGO ********************************************************* */
if( (!isset($_POST['field_deletegame_submit'])) && isset($_POST['field_list_editarjuegoid']) && ($_POST['field_list_editarjuegoid']!='false') ) { $editar_juego_id=$_POST['field_list_editarjuegoid']; }
else if( (!isset($_POST['field_deletegame_submit'])) && isset($_POST['field_editgame_list_id'])) { $editar_juego_id=$_POST['field_editgame_list_id']; }

if( isset($editar_juego_id) ) {
	
	$resjuego="SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` WHERE id='".$editar_juego_id."'";
	$resjuego = $wpdb->get_row($resjuego);
	
	$resjuegoplataformas=$wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$editar_juego_id."' AND idcontenido='0'");
	foreach ( $resjuegoplataformas as $row ){
		$arrayexistejuegoplataforma[$row->idplataforma]=true;
	}
	?>
	<div class="postbox-container" style="width:100%">
		<div class="postbox">
			<h3><?php echo __( 'Edit game', 'game-admin'); ?></h3>
			<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
			</div>	
			<div class="inside">
				<form id="edit_game_form" method="post" >	
					<p>
						<div style="position:relative; display:inline-block;">
							<label><?php echo __( 'Name of the game', 'game-admin'); ?></label><br/>
							<input name="field_editgame_nombre" id="field_editgame_nombre" value="<?php echo($resjuego->nombre); ?>" />
						</div>
						<div style="position:relative; display:inline-block;">
							<label>A&ntilde;o</label><br/>
							<input name="field_editgame_ano" id="field_editgame_ano" value="<?php echo($resjuego->ano); ?>" size="4" maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/>
						</div>					
					</p>
				<p>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Image', 'game-admin'); ?></label><br/>
						<div id="contenedor_imagen_editando" style="position:relative; display:inline-block;">
							<?php
							if(isset($resjuego->idimagen) && ($resjuego->idimagen!=0)){
								$urlimagen=wp_get_attachment_url($resjuego->idimagen);
								?>
								<img src="<?php echo($urlimagen); ?>" style="max-width:360px;" >
								<?php
							}
							?>
						</div>
						</br>
						<input id="field_editgame_imagen" name="field_editgame_imagen" <?php if(isset($urlimagen)){ echo("value='".$urlimagen."'"); } ?> type="text" size="36" disabled />
						<input id="field_editgame_imagen_id" name="field_editgame_imagen_id"  type="text"  <?php if(isset($resjuego->idimagen)){ echo("value='".$resjuego->idimagen."'"); } ?> hidden  />
						<input id="boton_editgame_imagen" type="button" value="Seleccionar" /><br>
						<font style="font-size:10px;"><?php echo __( 'Note: If no image is selected, when one is requested by a GameAdmin Widget, it will be used the first featured image of the first review or content found for this game.', 'game-admin'); ?></font>
					</div>
				</p>					
				<p>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Platforms', 'game-admin'); ?></label><br/>
					</div>
				</p>
				<p>
					<div style="position:relative; display:inline-block; width:100%;">							
						<?php
						foreach ($arrayplataformas as $clave => $valor) {
							$plataforma_field_id="field_editgame_plataforma_id_".$clave;
							?>
							<div style="position:relative; display:inline-block; margin-bottom:8px;">		
							<input <?php if(isset($arrayexistejuegoplataforma[$clave])){ echo('checked'); } ?> type="checkbox" id="<?php echo($plataforma_field_id); ?>" name="<?php echo($plataforma_field_id); ?>" value='false' onclick="javascript: this.value=this.checked" >				
							<?php
							echo($valor);
							?>
							&nbsp;&nbsp;
							</div>
							<?php
						}
						?>
					</div>
				</p>				
				<?php
				if(($resjuego->idexpansion == 0) || ($resjuego->idexpansion == null)){
					?>
					<p>
						<div style="position:relative; display:inline-block;">
							<label>&iquest;<?php echo __( 'Is this game an expansion / DLC?', 'game-admin'); ?></label><br/>
							<select id="field_editgame_seleccionar_esexpansion" name="field_editgame_seleccionar_esexpansion" style="cursor:pointer; min-width:250px; height:30px;" onchange="mostrarSelectEditarJuegoExpansion();">
								<option value="0" selected ><?php echo __( 'No', 'game-admin'); ?></option>
								<option value="1" ><?php echo __( 'Yes', 'game-admin'); ?></option>
							</select>
						</div>
						<div id="frame_editgame_expansion_plataforma"  style="position:relative; display:none;">
							<label><?php echo __( 'Search original game by platform', 'game-admin'); ?></label><br/>
							<select  id="form_editgame_expansion_plataforma" name="form_editgame_expansion_plataforma" style="cursor:pointer; min-width:250px;" onchange="loadListadoEditGameJuegos();">  
								<option value="todas"><?php echo __( 'Any', 'game-admin'); ?></option>
									<?php
									foreach ($arrayplataformas as $clave => $valor) {
										?>
										<option value="<?php echo($clave); ?>"> <?php echo($valor); ?> </option>
										<?php						
									}
									?>
							</select>						
						</div>
						<div id="frame_editgame_expansion_letra" style="position:relative; display:none;">
							<label><?php echo __( 'Search main game by letter', 'game-admin'); ?></label><br/>
							<select id="form_editgame_expansion_letra" name="form_editgame_expansion_letra" style="cursor:pointer; min-width:250px;" onchange="loadListadoEditGameJuegos();">  
								<option value="todas" selected><?php echo __( 'Any', 'game-admin'); ?></option>
								<option value="0" > 0-9 </option>
								<option value="a" > A </option>
								<option value="b" > B </option>
								<option value="c" > C </option>
								<option value="d" > D </option>
								<option value="e" > E </option>
								<option value="f" > F </option>
								<option value="g" > G </option>
								<option value="h" > H </option>
								<option value="i" > I </option>
								<option value="j" > J </option>
								<option value="k" > K </option>
								<option value="l" > L </option>								
								<option value="m" > M </option>
								<option value="n" > N </option>
								<option value="ñ" > &Ntilde; </option>
								<option value="o" > O </option>
								<option value="p" > P </option>
								<option value="q" > Q </option>
								<option value="r" > R </option>
								<option value="s" > S </option>
								<option value="t" > T </option>
								<option value="u" > U </option>
								<option value="v" > V </option>
								<option value="w" > W </option>
								<option value="x" > X </option>
								<option value="y" > Y </option>
								<option value="z" > Z </option>
							</select>							
						</div>					
					</p>					
					<p>
						<div id="frame_editgame_listado_juegos" style="position:relative; width:100%; display:none;">
							<label><?php echo __( 'Select original game', 'game-admin'); ?></label><br/>
							<select  size="2" id="field_editgame_seleccionar_juego" name="field_editgame_seleccionar_juego" style="height:30px; cursor:pointer; width:100%;">
								<option style="color:grey;" value="0" selected><?php echo __( 'None: Do not associate with any game', 'game-admin'); ?></option>
							</select>
						</div>				
					</p>						
					<?php
				} else {
					$resoriginal="SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` WHERE id='".$resjuego->idexpansion."'";
					$resoriginal = $wpdb->get_row($resoriginal);
					
					$arraycadenaplataformas="";
					$resoriginalplataformas=$wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$resoriginal->id."' AND idcontenido='0'");
					foreach ( $resoriginalplataformas as $row ){
						if($arraycadenaplataformas!=""){ $arraycadenaplataformas= $arraycadenaplataformas.", "; }
						$arraycadenaplataformas.=$arrayplataformas[$row->idplataforma];
					}				
					?>
					<p>
						<div style="position:relative; display:inline-block;">
							<label>&iquest;<?php echo __( 'Is this game an expansion / DLC?', 'game-admin'); ?></label><br/>
							<select id="field_editgame_seleccionar_esexpansion" name="field_editgame_seleccionar_esexpansion" style="cursor:pointer; min-width:250px; height:30px;" onchange="mostrarSelectEditarJuegoExpansion();">
								<option value="0" ><?php echo __( 'No', 'game-admin'); ?></option>
								<option value="1" selected ><?php echo __( 'Yes', 'game-admin'); ?></option>
							</select>
						</div>
						<div id="frame_editgame_expansion_plataforma"  style="position:relative; display:inline-block;">
							<label><?php echo __( 'Search original game by platform', 'game-admin'); ?></label><br/>
							<select  id="form_editgame_expansion_plataforma" name="form_editgame_expansion_plataforma" style="cursor:pointer; min-width:250px;" onchange="loadListadoEditGameJuegos();">  
								<option value="todas">Cualquiera</option>
									<?php
									foreach ($arrayplataformas as $clave => $valor) {
										?>
										<option value="<?php echo($clave); ?>"> <?php echo($valor); ?> </option>
										<?php						
									}
									?>
							</select>						
						</div>
						<div id="frame_editgame_expansion_letra" style="position:relative; display:inline-block;">
							<label><?php echo __( 'Search main game by letter', 'game-admin'); ?></label><br/>
							<select id="form_editgame_expansion_letra" name="form_editgame_expansion_letra" style="cursor:pointer; min-width:250px;" onchange="loadListadoEditGameJuegos();">  
								<option value="todas" selected><?php echo __( 'Any', 'game-admin'); ?></option>
								<option value="0" > 0-9 </option>
								<option value="a" > A </option>
								<option value="b" > B </option>
								<option value="c" > C </option>
								<option value="d" > D </option>
								<option value="e" > E </option>
								<option value="f" > F </option>
								<option value="g" > G </option>
								<option value="h" > H </option>
								<option value="i" > I </option>
								<option value="j" > J </option>
								<option value="k" > K </option>
								<option value="l" > L </option>								
								<option value="m" > M </option>
								<option value="n" > N </option>
								<option value="ñ" > &Ntilde; </option>
								<option value="o" > O </option>
								<option value="p" > P </option>
								<option value="q" > Q </option>
								<option value="r" > R </option>
								<option value="s" > S </option>
								<option value="t" > T </option>
								<option value="u" > U </option>
								<option value="v" > V </option>
								<option value="w" > W </option>
								<option value="x" > X </option>
								<option value="y" > Y </option>
								<option value="z" > Z </option>
							</select>							
						</div>					
					</p>					
					<p>
						<div id="frame_editgame_listado_juegos" style="position:relative; width:100%; display:inline-block;">
							<label><?php echo __( 'Select original game', 'game-admin'); ?></label><br/>
							<select  size="2" id="field_editgame_seleccionar_juego" name="field_editgame_seleccionar_juego" style="height:30px; cursor:pointer; width:100%;">
								<option value="<?php echo($resoriginal->id); ?>" selected><?php echo($resoriginal->nombre); ?>&nbsp;&nbsp;<span style='color:#6e6e6e; font-size:10px;'><?php echo("(".$resoriginal->ano."), ".$arraycadenaplataformas); ?></span></option>
							</select>
						</div>				
					</p>					
					<?php
				}
				?>
				<p>
					<div style="position:relative; display:inline-block;">
						<input hidden name="field_editgame_list_id" id="field_editgame_list_id" value="<?php echo($resjuego->id); ?>" />
						<input hidden name="field_editgame_list_letra" id="field_editgame_list_letra" value="<?php echo($letra); ?>" />
						<input hidden name="field_editgame_list_plataforma" id="field_editgame_list_plataforma" value="<?php echo($plataforma); ?>" />
						<input type="submit" name="field_editgame_submit" value="<?php echo __( 'Update', 'game-admin'); ?>"> </input>
						<input type="submit" name="field_deletegame_submit" value="<?php echo __( 'Delete', 'game-admin'); ?>"> </input>
					</div>
				</p>			
				</form>
			</div>
		</div>
	</div>	
	
	<?php
}

/* ******************************************************* LISTA DE JUEGOS ********************************************************* */
/* ******************************************************* LISTA DE JUEGOS ********************************************************* */
/* ******************************************************* LISTA DE JUEGOS ********************************************************* */
?>
<div class="postbox-container" style="width:100%">
	<div class="postbox">
		<h3><?php echo __( 'Game list', 'game-admin'); ?></h3>
		<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
		</div>	
		<div class="inside">
			<form id="list_games_form" method="post" >	
				<p>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Platform', 'game-admin'); ?></label><br/>
						<select  id="field_list_plataforma" name="field_list_plataforma" style="cursor:pointer;">  
							<option value="todas" <?php if($plataforma=="todas"){ echo("selected");  } ?> ><?php echo __( 'Any', 'game-admin'); ?></option>
							<?php
							$resultados = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` ORDER BY nombre");
							foreach ( $resultados as $row ) {
								?>
								<option value="<?php echo($row->id); ?>" <?php if($plataforma==$row->id){ echo("selected");  } ?>  > <?php echo($row->nombre); ?> </option>
								<?php	
							}	
							?>
						</select>						
					</div>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Letter', 'game-admin'); ?></label><br/>
						<select  id="field_list_letra" name="field_list_letra" style="cursor:pointer;">  
								<option value="todas" <?php if($letra=="todas"){ echo("selected");  } ?> ><?php echo __( 'Any', 'game-admin'); ?></option>
								<option value="0" <?php if($letra=="0"){ echo("selected");  } ?> > 0-9 </option>
								<option value="a" <?php if($letra=="a"){ echo("selected");  } ?> > A </option>
								<option value="b" <?php if($letra=="b"){ echo("selected");  } ?> > B </option>
								<option value="c" <?php if($letra=="c"){ echo("selected");  } ?> > C </option>
								<option value="d" <?php if($letra=="d"){ echo("selected");  } ?> > D </option>
								<option value="e" <?php if($letra=="e"){ echo("selected");  } ?> > E </option>
								<option value="f" <?php if($letra=="f"){ echo("selected");  } ?> > F </option>
								<option value="g" <?php if($letra=="g"){ echo("selected");  } ?> > G </option>
								<option value="h" <?php if($letra=="h"){ echo("selected");  } ?> > H </option>
								<option value="i" <?php if($letra=="i"){ echo("selected");  } ?> > I </option>
								<option value="j" <?php if($letra=="j"){ echo("selected");  } ?> > J </option>
								<option value="k" <?php if($letra=="k"){ echo("selected");  } ?> > K </option>
								<option value="l" <?php if($letra=="l"){ echo("selected");  } ?> > L </option>								
								<option value="m" <?php if($letra=="m"){ echo("selected");  } ?> > M </option>
								<option value="n" <?php if($letra=="n"){ echo("selected");  } ?> > N </option>
								<option value="ñ" <?php if($letra=="ñ"){ echo("selected");  } ?> > &Ntilde; </option>
								<option value="o" <?php if($letra=="o"){ echo("selected");  } ?> > O </option>
								<option value="p" <?php if($letra=="p"){ echo("selected");  } ?> > P </option>
								<option value="q" <?php if($letra=="q"){ echo("selected");  } ?> > Q </option>
								<option value="r" <?php if($letra=="r"){ echo("selected");  } ?> > R </option>
								<option value="s" <?php if($letra=="s"){ echo("selected");  } ?> > S </option>
								<option value="t" <?php if($letra=="t"){ echo("selected");  } ?> > T </option>
								<option value="u" <?php if($letra=="u"){ echo("selected");  } ?> > U </option>
								<option value="v" <?php if($letra=="v"){ echo("selected");  } ?> > V </option>
								<option value="w" <?php if($letra=="w"){ echo("selected");  } ?> > W </option>
								<option value="x" <?php if($letra=="x"){ echo("selected");  } ?> > X </option>
								<option value="y" <?php if($letra=="y"){ echo("selected");  } ?> > Y </option>
								<option value="z" <?php if($letra=="z"){ echo("selected");  } ?> > Z </option>
						</select>						
					</div>
					<div style="position:relative; display:inline-block;">
						<input hidden name="field_list_editarjuegoid" id="field_list_editarjuegoid" value="false" />
						<input type="submit" name="field_list_submit" value="<?php echo __( 'Show', 'game-admin'); ?>"> </input>
					</div>
				</p>				
			</form>
			<?php

			//listar solamente si se ha seleccionado en algun momento plataforma o letra
			if(($plataforma!='-') || ($letra!='-')) {
				$sql="SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` ORDER BY NOMBRE";
				$resultados = $wpdb->get_results($sql);
				foreach ( $resultados as $row ){
					$mostrar=true;
					//tener solo en cuenta si se ha seleccionado letra
					if($letra!='-'){
						$letrajuego=substr ($row->nombre, 0,1);
						if( ($letra!="todas" )  &&  (strcasecmp($letrajuego, $letra) != 0) ){
							$mostrar=false;
						}
						if( ($letra=="0" )  && (!preg_match('/[A-Za-z]/',$letrajuego)) ){
							$mostrar=true;
						}
					}
					//tener solo en cuenta si se ha seleccionado plataforma
					if($plataforma!='-'){
						if( ($mostrar==true) && ($plataforma!="todas") ){
							$resultadosp = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego ='".$row->id."' AND idcontenido='0' AND idplataforma ='".$plataforma."' ");
							if($wpdb->num_rows ==0){
								$mostrar=false;
							}
						}
					}
					if($mostrar==true){
						$resultadosjuegoplataforma = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego ='".$row->id."' AND idcontenido='0' ");
						$cadenaplataformas="";
						foreach ( $resultadosjuegoplataforma as $rowjuegoplataforma ){
							
							if($cadenaplataformas!=""){ $cadenaplataformas= $cadenaplataformas.", "; }
							$cadenaplataformas= $cadenaplataformas.$arrayplataformas[$rowjuegoplataforma->idplataforma];
						}
						?>
						<div style="position:relative; display:block; background-color:#e5e5e5; padding:8px;">
							<span class="enlacejuego" onclick="document.getElementById('field_list_editarjuegoid').value = '<?php echo($row->id); ?>'; document.getElementById('list_games_form').submit();"><?php echo($row->nombre); ?></span>&nbsp;&nbsp;<span style='color:#6e6e6e; font-size:10px;'><?php echo("(".$row->ano."), ".$cadenaplataformas); ?></span>
						</div>					
						<?php
					}
				}
			}
			?>
		</div>
	</div>
</div>


<?php
/* ************************************************ FORMULARIOS DE AGREGAR JUEGO ************************************************** */
/* ************************************************ FORMULARIOS DE AGREGAR JUEGO ************************************************** */
/* ************************************************ FORMULARIOS DE AGREGAR JUEGO ************************************************** */
?>
<div class="postbox-container" style="width:100%">
	<div class="postbox">
		<h3><?php echo __( 'Add new game', 'game-admin'); ?></h3>
		<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
		</div>	
		<div class="inside">
			<form id="new_game_form" method="post" >	
				<p>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Game name', 'game-admin'); ?></label><br/>
						<input name="field_newgame_nombre" id="field_newgame_nombre" />
					</div>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Release year', 'game-admin'); ?></label><br/>
						<input name="field_newgame_ano" id="field_newgame_ano" size="4" maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/>
					</div>				
				</p>
				<p>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Image', 'game-admin'); ?></label><br/>
						<div id="contenedor_imagen_creando" style="position:relative; display:inline-block;"></div>
						</br>
						<input id="field_newgame_imagen" name="field_newgame_imagen" type="text" size="36"  disabled />
						<input id="field_newgame_imagen_id" name="field_newgame_imagen_id"  type="text" hidden  />
						<input id="boton_newgame_imagen" type="button" value="Seleccionar" /><br>
						<font style="font-size:10px;"><?php echo __( 'Note: If no image is selected, when one is requested by a GameAdmin Widget, it will be used the first featured image of the first review or content found for this game.', 'game-admin'); ?></font>
					</div>
				</p>
				<p>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Platforms', 'game-admin'); ?></label><br/>
					</div>
				</p>
				<p>
					<div style="position:relative; display:inline-block; width:100%;">							
						<?php			
						$table_name = $wpdb->prefix . 'gameadmin_plataformas';
						$resultados = $wpdb->get_results("SELECT * FROM ".$table_name." ORDER BY nombre");
						foreach ( $resultados as $row ){
							$plataforma_field_id="plataforma_juego_id_".$row->id;
							?>
							<div style="width:10%; float:left; display:inline-block;">
							<input type="checkbox" id="<?php echo($plataforma_field_id); ?>" name="<?php echo($plataforma_field_id); ?>" value='false' onclick="javascript: this.value=this.checked" >&nbsp;&nbsp;					
							<?php
							echo($row->nombre);
							?>
							</div>
							<?php
						}
						?>
					</div>
				</p>
				<p>
					<div style="position:relative; display:inline-block;">
						<label>&iquest;<?php echo __( 'Is this game an expansion / DLC?', 'game-admin'); ?></label><br/>
						<select id="field_newgame_seleccionar_esexpansion" name="field_newgame_seleccionar_esexpansion" style="cursor:pointer; min-width:250px; height:30px;" onchange="mostrarSelectNuevoJuegoExpansion();">
							<option value="0" selected><?php echo __( 'No', 'game-admin'); ?></option>
							<option value="1"><?php echo __( 'Yes', 'game-admin'); ?></option>
						</select>
					</div>
					<div id="frame_newgame_expansion_plataforma"  style="position:relative; display:none;">
						<label><?php echo __( 'Search original game by platform', 'game-admin'); ?></label><br/>
						<select  id="form_newgame_expansion_plataforma" name="form_newgame_expansion_plataforma" style="cursor:pointer; min-width:250px;" onchange="loadListadoNewGameJuegos();">  
							<option value="todas"><?php echo __( 'Any', 'game-admin'); ?></option>
								<?php
								foreach ($arrayplataformas as $clave => $valor) {
									?>
									<option value="<?php echo($clave); ?>"> <?php echo($valor); ?> </option>
									<?php						
								}
								?>
						</select>						
					</div>
					<div id="frame_newgame_expansion_letra" style="position:relative; display:none;">
						<label><?php echo __( 'Search original game by letter', 'game-admin'); ?></label><br/>
						<select id="form_newgame_expansion_letra" name="form_newgame_expansion_letra" style="cursor:pointer; min-width:250px;" onchange="loadListadoNewGameJuegos();">  
							<option value="todas" selected><?php echo __( 'Any', 'game-admin'); ?></option>
							<option value="0" > 0-9 </option>
							<option value="a" > A </option>
							<option value="b" > B </option>
							<option value="c" > C </option>
							<option value="d" > D </option>
							<option value="e" > E </option>
							<option value="f" > F </option>
							<option value="g" > G </option>
							<option value="h" > H </option>
							<option value="i" > I </option>
							<option value="j" > J </option>
							<option value="k" > K </option>
							<option value="l" > L </option>								
							<option value="m" > M </option>
							<option value="n" > N </option>
							<option value="ñ" > &Ntilde; </option>
							<option value="o" > O </option>
							<option value="p" > P </option>
							<option value="q" > Q </option>
							<option value="r" > R </option>
							<option value="s" > S </option>
							<option value="t" > T </option>
							<option value="u" > U </option>
							<option value="v" > V </option>
							<option value="w" > W </option>
							<option value="x" > X </option>
							<option value="y" > Y </option>
							<option value="z" > Z </option>
						</select>							
					</div>					
				</p>
				<p>
					<div id="frame_newgame_listado_juegos" style="position:relative; width:100%; display:none;">
						<label><?php echo __( 'Select original game', 'game-admin'); ?></label><br/>
						<select  size="2" id="field_newgame_seleccionar_juego" name="field_newgame_seleccionar_juego" style="height:30px; cursor:pointer; width:100%;">
							<option style="color:grey;" value="0" selected><?php echo __( 'None: Do not associate with any game', 'game-admin'); ?></option>
						</select>
					</div>				
				</p>
				<p>
					<div style="position:relative; display:inline-block;">
						<input type="submit" name="field_newgame_submit" value="<?php echo __( 'Add', 'game-admin'); ?>"> </input>
					</div>
				</p>		
			</form>
		</div>
	</div>
</div>
