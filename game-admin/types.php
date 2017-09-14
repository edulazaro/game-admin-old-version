<style>
h3 { font-size: 14px; padding-left: 12px; cursor:pointer; }
.enlacetipo{ text-decoration:underline; cursor:pointer; color:#000000; }
.enlacetipo:hover{ color:#a2a2a2; }
 </style>
 
 <?php
 
foreach ($_POST as $clave => $valor) { $_POST[$clave] = esc_sql($_POST[$clave]); }
 
/* ******************************************************* PROCESAR TIPOS => AGREGAR ********************************************************* */
/* ******************************************************* PROCESAR TIPOS => AGREGAR ********************************************************* */
/* ******************************************************* PROCESAR TIPOS => AGREGAR ********************************************************* */

if( isset($_POST['field_nuevotipo_submit']) ){

	if((isset($_POST['field_nuevotipo_nombre'])) && ($_POST['field_nuevotipo_nombre']!="") && ($_POST['field_nuevotipo_nombre']!=null)) {
		$table_name = $wpdb->prefix . 'gameadmin_tipos';
		$wpdb->get_results("INSERT INTO `{$wpdb->prefix}gameadmin_tipos` (nombre, orden, tienenota, asociarplataformas) VALUES ('".$_POST['field_nuevotipo_nombre']."', '".$_POST['field_nuevotipo_orden']."', '".$_POST['field_nuevotipo_tienenota']."', '".$_POST['field_nuevotipo_asociarplataformas']."')");
		?>
		<p style="color:green;"><?php echo __('Content type added correctly.', 'game-admin'); ?></p> 
		<?php
	} else {
		?>
		<p style="color:red;"><b><?php echo __('Error:', 'game-admin'); ?></b> <?php echo __('Please complete all required fields of the new content type.', 'game-admin'); ?></p>
		<?php
	}
}	
	
/* ********************************************* PROCESAR PLATAFORMAS => ACTUALIZAR / ELIMINAR *********************************************** */
/* ********************************************* PROCESAR PLATAFORMAS => ACTUALIZAR / ELIMINAR *********************************************** */
/* ********************************************* PROCESAR PLATAFORMAS => ACTUALIZAR / ELIMINAR *********************************************** */

if(isset($_POST['field_edittipo_submit_actualizar'])){

	//Borro las plataformas asociadas a contenidos de este tipo
	if($_POST['field_edittipo_asociarplataformas']==0){
			$contenidosdeltipo= $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idtipo='".$_POST['field_edittipo_id']."' ");
			foreach ($contenidosdeltipo as $contenidodeltipo) {
				$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE (idcontenido = '".$contenidodeltipo->idpost."' ) ");
			
			}			
	}

	
	$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_tipos` SET nombre='".$_POST['field_edittipo_nombre']."', orden='".$_POST['field_edittipo_orden']."', tienenota='".$_POST['field_edittipo_tienenota']."', asociarplataformas='".$_POST['field_edittipo_asociarplataformas']."' WHERE  id='".$_POST['field_edittipo_id']."' ");
	?>
	<p style="color:green;"><?php echo __( 'Data stored correctly.', 'game-admin'); ?></p>
	<?php			
}

if(isset($_POST['field_edittipo_submit_eliminar'])){

	$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_tipos` WHERE (id = '".$_POST['field_edittipo_id']."' ) ");
	$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_contenidos` SET idtipo='0' WHERE  idtipo='".$_POST['field_edittipo_id']."' ")
	?>
	<p style="color:green;"><?php echo __( 'Content type successfully removed. The publications have been updated.', 'game-admin'); ?></p>
	<?php			
}

/* *********************************************************** CODIGO COMPARTIDO ************************************************************* */
/* *********************************************************** CODIGO COMPARTIDO ************************************************************* */
/* *********************************************************** CODIGO COMPARTIDO ************************************************************* */

$arraytipos = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` ORDER BY orden");

?>
<div style="position:relative; float:left;  width:100%; height:auto; text-align:left; font-size: 24px; color:#4a4a4a;">
	<h2><?php echo __( 'Content Types', 'game-admin'); ?></h3>
</div>
<?php

/* ******************************************************* FORMULARIO EDITAR TIPO ********************************************************* */
/* ******************************************************* FORMULARIO EDITAR TIPO ********************************************************* */
/* ******************************************************* FORMULARIO EDITAR TIPO ********************************************************* */

if (isset($_POST['field_editar_tipoid'])) {
		
	$tipo_editando= $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` WHERE id='".$_POST['field_editar_tipoid']."' ");
	
	?>
	<div class="postbox-container" style="width:100%">
		<div class="postbox">
			<h3><?php echo __( 'Edit content type', 'game-admin'); ?></h3>
			<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
			</div>	
			<div class="inside">
				<form id="edit_tipo_form" method="post" >	
					<input id="field_edittipo_id" name="field_edittipo_id" value="<?php echo($tipo_editando->id); ?>" style="display:none;"/>
					<p>
						<div style="position:relative; display:inline-block;">
							<label><?php echo __( 'Name', 'game-admin'); ?></label><br/>
							<input name="field_edittipo_nombre" id="field_edittipo_nombre" value="<?php echo($tipo_editando->nombre); ?>" />
						</div>
						<div style="position:relative; display:inline-block;">
							<label><?php echo __( 'Order', 'game-admin'); ?></label><br/>
							<select name="field_edittipo_orden" style="width:100px;">
								<?php
								for ( $i= 1; $i <= 30; $i++){
									$posiciondisponible=true;
									foreach ($arraytipos as $tipo) { if(($tipo->orden == $i) && ($tipo_editando->orden!=$i)) { $posiciondisponible=false; } }
									if($posiciondisponible==true){
										?>
										<option value="<?php echo($i); ?>" <?php if($tipo_editando->orden==$i){ echo('selected'); } ?> ><?php echo($i); ?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
						<div style="position:relative; display:inline-block;">
							<label><?php echo __( 'Assign platforms', 'game-admin'); ?></label><br/>
							<select id="field_edittipo_asociarplataformas" name="field_edittipo_asociarplataformas" >
								<option value="1" <?php if($tipo_editando->asociarplataformas==1){ echo('selected'); } ?>><?php echo __( 'Assign platform selection to this content type', 'game-admin'); ?></option>
								<option value="0" <?php if($tipo_editando->asociarplataformas==0){ echo('selected'); } ?>><?php echo __( 'Do not assign platform selection to this content type', 'game-admin'); ?></option>	
							</select>
						</div>						
						<div style="position:relative; display:inline-block;">
							<label>Puntuaci&oacute;n</label><br/>
							<select id="field_edittipo_tienenota" name="field_edittipo_tienenota" >
								<option value="0" <?php if($tipo_editando->tienenota==0){ echo('selected'); } ?>><?php echo __( 'Not available', 'game-admin'); ?></option>
								<option value="1" <?php if($tipo_editando->tienenota==1){ echo('selected'); } ?>><?php echo __( 'Available', 'game-admin'); ?></option>
							</select>
						</div>															
					</p>
					<p>
						<div style="position:relative; display:inline-block;">
							<input hidden name="field_edittipo_id" id="field_edittipo_id" value="<?php echo($tipo_editando->id); ?>" />
							<input type="submit" name="field_edittipo_submit_actualizar" value="<?php echo __( 'Update', 'game-admin'); ?>"> </input>
							<input type="submit" name="field_edittipo_submit_eliminar" value="<?php echo __( 'Delete', 'game-admin'); ?>"> </input>
						</div>
					</p>				
				</form>
			</div>
		</div>
	</div>	
	
	<?php
}

/* ********************************************* Listar tipos *********************************************** */
/* ********************************************* Listar tipos *********************************************** */
/* ********************************************* Listar tipos *********************************************** */

if($arraytipos){
	?>

	<div class="postbox-container" style="width:100%">
		<div class="postbox">
			<h3><?php echo __( 'List of content types (click to edit)', 'game-admin'); ?></h3>
			<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
			</div>	
			<div class="inside">
				<form id="editar_tipo_form" method="post" ><input id="field_editar_tipoid" name="field_editar_tipoid" style="display:none;"/></form>
				<p>
					<div style="position:relative; display:block; background-color:#e5e5e5; padding:8px;     margin-top: 30px;">
						<?php
						foreach ( $arraytipos as $row ){
							?>
							<div style="position:relative; display:block; padding:8px;">
								<span class="enlacetipo" onclick="document.getElementById('field_editar_tipoid').value = '<?php echo($row->id); ?>'; document.getElementById('editar_tipo_form').submit();"><?php echo($row->nombre); ?></span>
							</div>					
							<?php	
						}
						?>
					</div>
				</p>
			</div>
		</div>
	</div>
	<?php
}

/* ********************************************* FORMULARIOS DE AGREGAR TIPO *********************************************** */
/* ********************************************* FORMULARIOS DE AGREGAR TIPO *********************************************** */
/* ********************************************* FORMULARIOS DE AGREGAR TIPO *********************************************** */

?>
<div class="postbox-container" style="width:100%">
	<div class="postbox">
		<h3><?php echo __( 'Add new content type', 'game-admin'); ?></h3>
		<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
		</div>	
		<div class="inside">
			<form method="post" >	
				<p>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Name', 'game-admin'); ?></label><br/>
						<input name="field_nuevotipo_nombre" />
					</div>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Order', 'game-admin'); ?></label><br/>
						<select name="field_nuevotipo_orden">
							<?php
							for ( $i= 1; $i <= 30; $i++){
								$posiciondisponible=true;
								foreach ($arraytipos as $tipo) { if( $tipo->orden == $i) { $posiciondisponible=false; } }
								if($posiciondisponible==true){
									?>
									<option value="<?php echo($i); ?>" ><?php echo($i); ?></option>
									<?php
								}
							}
							?>
						</select>
					</div>
						<div style="position:relative; display:inline-block;">
							<label><?php echo __( 'Associated platforms', 'game-admin'); ?></label><br/>
							<select id="field_nuevotipo_asociarplataformas" name="field_nuevotipo_asociarplataformas" >
								<option value="1" selected><?php echo __( 'Associate platforms to contents of this type', 'game-admin'); ?></option>
								<option value="0" ><?php echo __( 'Do not associate platforms to contents of this type', 'game-admin'); ?></option>
							</select>
						</div>							
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Rating', 'game-admin'); ?></label><br/>
						<select id="field_nuevotipo_tienenota" name="field_nuevotipo_tienenota">
							<option value="0" selected><?php echo __( 'Not available', 'game-admin'); ?></option>
							<option value="1"><?php echo __( 'Available', 'game-admin'); ?></option>
						</select>
					</div>					
					<div style="position:relative; display:inline-block;">
						<input type="submit" name="field_nuevotipo_submit" value="<?php echo __( 'Submit', 'game-admin'); ?>"> </input>
					</div>
				</p>				
			</form>
		</div>
	</div>
</div>