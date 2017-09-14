<?php

function obtenerOrdenesDependenciaDisponibles(){

	global $wpdb;
	$parentid=esc_sql($_POST['parentid']);
	$juegoid=esc_sql($_POST['juego']);
	$postid=esc_sql($_POST['postid']);
	$tipo=esc_sql($_POST['tipo']);	
	if ($juegoid == 'ninguno') { die(); }
	
	if ($parentid == '0'){
		$publicacionesorden = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$juegoid."' AND idparent='".$parentid."' AND idtipo='".$tipo."' AND idpost!='".$postid."' ORDER BY orden ", OBJECT);
	} else {
		$publicacionesorden = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$juegoid."' AND idparent='".$parentid."' AND idpost!='".$postid."' ORDER BY orden ", OBJECT);
	}
		
	?>
	<label><b><?php echo __('Order', 'game-admin'); ?></b></label><br/>
	<select id="form_seleccionar_orden" name="form_seleccionar_orden" style="cursor:pointer; min-width:70px; height:30px;">
		<?php
		for ( $i= 1; $i <= 30; $i++){
			$posiciondisponible=true;
			foreach ($publicacionesorden as $publicacionorden) {
				if($publicacionorden->orden == $i){
					$posiciondisponible=false;
				}
			}
			if($posiciondisponible==true){
				?>
				<option value="<?php echo($i); ?>" ><?php echo($i); ?></option>
				<?php
			}
		}
		?>
	</select>
	<?php
	
	die();	

}

add_action('wp_ajax_obtenerOrdenesDependenciaDisponibles', 'obtenerOrdenesDependenciaDisponibles');	