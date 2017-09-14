<style>
h3 { font-size: 14px; padding-left: 12px; cursor:pointer; }
 </style>

<?php
/* ********************************************* PROCESAR PLATAFORMAS => AGREGAR *********************************************** */

	if( isset($_POST['field_nuevaplataforma_submit']) ){
		if((isset($_POST['field_nuevaplataforma_nombre'])) && ($_POST['field_nuevaplataforma_nombre']!="") && ($_POST['field_nuevaplataforma_nombre']!=null)) {
			$table_name = $wpdb->prefix . 'gameadmin_plataformas';
			$wpdb->get_results("INSERT INTO ".$table_name." (nombre) VALUES ('".$_POST['field_nuevaplataforma_nombre']."')");
			?>
			<p style="color:green;"><?php echo __( 'Platform inserted correctly.', 'game-admin'); ?></p>
			<?php
		} else {
			?>
			<p style="color:red;"><b><?php echo __( 'Error:', 'game-admin'); ?></b> <?php echo __( 'You must fill all the data of the new platform.', 'game-admin'); ?></p>
			<?php
		}
	}

/* ********************************************* PROCESAR PLATAFORMAS => ACTUALIZAR / ELIMINAR *********************************************** */		

	if(isset($_POST['field_plataformas_submit'])){		
			$resultados = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` ");
			foreach ( $resultados as $row ){
				$plataforma_field_id="plataforma_id_".$row->id;
				if(isset($_POST[$plataforma_field_id])){
					//Delete
					if($_POST[$plataforma_field_id]==""){
						$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE (idplataforma = '".$row->id."' ) ");
						$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_plataformas` WHERE (id = '".$row->id."' ) ");
					//Update
					} else if($_POST[$plataforma_field_id]!==$row->nombre){
						$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_plataformas` SET nombre='".$_POST[$plataforma_field_id]."' WHERE  id='".$row->id."' ");
					}
				}
			}
			?>
			<p style="color:green;"><?php echo __( 'Data stored correctly.', 'game-admin'); ?></p>
			<?php			
	}		
?>

<?php
/* ********************************************* FORMULARIOS DE ACTUALIZAR PLATAFORMAS *********************************************** */

//Solamente muestro las paltaformas si hay alguna
$resultados = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas`");
$numFilas=count($resultados);
if($numFilas!=0){
	?>
	<div style="position:relative; float:left;  width:100%; height:auto; text-align:left; font-size: 24px; color:#4a4a4a;">
			<h2><?php echo __( 'Platforms', 'game-admin'); ?></h3>
	</div>
	<div class="postbox-container" style="width:100%">
		<div class="postbox">
			<h3><?php echo __( 'List of platforms', 'game-admin'); ?></h3>
			<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
			</div>	
			<div class="inside">
				<form id="plataformas_form" method="post" >
					<p>
					<?php
					$tabla_plataformas=$wpdb->prefix."gameadmin_plataformas";
					$resultados = $wpdb->get_results("SELECT * FROM ".$tabla_plataformas." ORDER BY NOMBRE ");
					foreach ( $resultados as $row ){
						?>
						<div style="position:relative; display:inline-block;">
							<input id="plataforma_id_<?php echo($row->id); ?>" name="plataforma_id_<?php echo($row->id); ?>"   value="<?php echo($row->nombre); ?>"> </input>
						</div>
						<?php	
					}				
					?>
					</p>
					<p>
						<div style="position:relative; display:inline-block;">
							<input type="submit" name="field_plataformas_submit" value="<?php echo __( 'Save', 'game-admin'); ?>"> </input>	
						</div>				
					</p>			
				</form>
				<div style="width:100%; font-size:12px;">
				<?php echo __( '* To delete platforms, leave blank names and save.', 'game-admin'); ?>
				</div>				
			</div>
		</div>
	</div>
	<?php
}
?>

<?php
/* ********************************************* FORMULARIOS DE AGREGAR PLATAFORMA *********************************************** */
?>
<div class="postbox-container" style="width:100%">
	<div class="postbox">
		<h3><?php echo __( 'Add new platform', 'game-admin'); ?></h3>
		<div style="position:relative; float:left;  width:100%; height:1px;  margin-top:0px; margin-bottom: 15px; text-align:left; background-color:#e5e5e5;">
		</div>	
		<div class="inside">
			<form id="new_plataforma_form" method="post" >	
				<p>
					<div style="position:relative; display:inline-block;">
						<label><?php echo __( 'Platform name', 'game-admin'); ?></label><br/>
						<input name="field_nuevaplataforma_nombre" id="field_nuevaplataforma_nombre" />
					</div>
					<div style="position:relative; display:inline-block;">
						<input type="submit" name="field_nuevaplataforma_submit" value="<?php echo __( 'Add', 'game-admin'); ?>"> </input>
					</div>
				</p>				
			</form>
		</div>
	</div>
</div>