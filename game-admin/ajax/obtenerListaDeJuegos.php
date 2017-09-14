<?php

function obtenerListaDeJuegos(){

	global $wpdb;
	//LINEAFULL //Almaceno temoralmente los codigos y nombres de plataformas porque es algo que se usa repetidas veces
	$resultadosplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` "); foreach ( $resultadosplataformas as $rowplataformas ){  $arrayplataformas[$rowplataformas->id]=$rowplataformas->nombre; }
	//LINEAFULL //Obtengo las variables por POST
	foreach ($_POST as $clave => $valor) { $_POST[$clave] = esc_sql($_POST[$clave]); }
	$plataforma=$_POST['plataforma']; $letra=$_POST['letra'];
	if(isset($_POST['origen'])){ $origen=$_POST['origen']; } else { $origen=false;  }
	
	if ($plataforma=='todas') {
		if($letra=='todas'){
			$consulta="SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` ORDER BY nombre";
		} else if ($letra=='0'){
			$consulta="SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` WHERE nombre LIKE '0%' OR nombre LIKE '1%' OR nombre LIKE '2%' OR nombre LIKE '3%' OR nombre LIKE '4%' OR nombre LIKE '5%' OR nombre LIKE '6%' OR nombre LIKE '7%' OR nombre LIKE '8%' OR nombre LIKE '9%' ORDER BY nombre";
		} else {
			$consulta="SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` WHERE LOWER(nombre) LIKE LOWER('".$letra."%') ORDER BY nombre";
		}
	} else {
		if($letra=='todas'){
			$consulta = "SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` INNER JOIN `{$wpdb->prefix}gameadmin_juegoplataforma`  
				ON `{$wpdb->prefix}gameadmin_juegos`.id = `{$wpdb->prefix}gameadmin_juegoplataforma`.idjuego
				WHERE	`{$wpdb->prefix}gameadmin_juegoplataforma`.idplataforma='".$plataforma."'
				AND `{$wpdb->prefix}gameadmin_juegoplataforma`.idcontenido = '0'
				ORDER BY nombre";		
		}  else if ($letra=='0') {
			$consulta = "SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` INNER JOIN `{$wpdb->prefix}gameadmin_juegoplataforma` 
				ON `{$wpdb->prefix}gameadmin_juegos`.id = `{$wpdb->prefix}gameadmin_juegoplataforma`.idjuego
				WHERE	`{$wpdb->prefix}gameadmin_juegoplataforma`.idplataforma='".$plataforma."'
				AND `{$wpdb->prefix}gameadmin_juegoplataforma`.idcontenido = '0'
				AND (`{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '0%' OR `{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '1%' OR `{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '2%' OR `{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '3%' OR `{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '4%' OR `{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '5%' OR `{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '6%' OR `{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '7%' OR `{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '8%' OR `{$wpdb->prefix}gameadmin_juegos`.nombre LIKE '9%') ORDER BY nombre";
		} else{
			$consulta = "SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` INNER JOIN `{$wpdb->prefix}gameadmin_juegoplataforma` 
				ON `{$wpdb->prefix}gameadmin_juegos`.id = `{$wpdb->prefix}gameadmin_juegoplataforma`.idjuego
				WHERE	`{$wpdb->prefix}gameadmin_juegoplataforma`.idplataforma='".$plataforma."' 
				AND LOWER(`{$wpdb->prefix}gameadmin_juegos`.nombre) LIKE LOWER('".$letra."%')
				AND `{$wpdb->prefix}gameadmin_juegoplataforma`.idcontenido = '0'
				ORDER BY nombre";		
		}
	}	
	$resultados = $wpdb->get_results($consulta);
	?>
	<label><b>
	<?php
	if($origen) {
		if( ($origen=='games_newgameexpansion') || ($origen=='games_editgameexpansion') ){ echo('Seleccionar juego original'); }
	} else { echo('Seleccionar juego');  }
	?>
	</b></label><br/>
	
	<select size="2" id="<?php if($origen=='games_newgameexpansion'){ echo('field_newgame_seleccionar_juego'); } else if($origen=='games_editgameexpansion'){ echo('field_editgame_seleccionar_juego'); } else { echo('form_seleccionar_juego'); } ?>" name="<?php if($origen=='games_newgameexpansion'){ echo('field_newgame_seleccionar_juego'); } else if($origen=='games_editgameexpansion'){ echo('field_editgame_seleccionar_juego'); } else { echo('form_seleccionar_juego'); } ?>" style="cursor:pointer; height:200px; width:100%;" onchange="<?php if($origen==false){ echo('obtenerDatosDeJuego()'); } ?>">
		<option style="color:grey;" value="ninguno"><?php echo __('None: no not associate to any game', 'game-admin'); ?></option>	
		<?php
		foreach ( $resultados as $row ) {
			$resjuegoplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$row->id."' AND idcontenido='0' ");
			$cadenaplataformas="";
			foreach ( $resjuegoplataformas as $rowjuegoplataformas ) {
				if($cadenaplataformas!=""){ $cadenaplataformas .= ", "; }
				$cadenaplataformas .= $arrayplataformas[$rowjuegoplataformas->idplataforma];			
			}
			?>
			<option value="<?php echo($row->id); ?>"><?php echo($row->nombre." (".$cadenaplataformas.")"); ?></option>
			<?php
		}
		?>
	</select>

	<?php
	die();
}

add_action('wp_ajax_obtenerListaDeJuegos', 'obtenerListaDeJuegos');

?>