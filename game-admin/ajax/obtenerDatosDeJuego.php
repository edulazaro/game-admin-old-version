<?php

function obtenerDatosDeJuego(){

	global $wpdb;
	//LINEAFULL //Almaceno temoralmente los codigos y nombres de plataformas porque es algo que se usa repetidas veces
	$resultadosplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` "); foreach ( $resultadosplataformas as $rowplataformas ){  $arrayplataformas[$rowplataformas->id]=$rowplataformas->nombre; }
	$juegoid=esc_sql($_POST['juego']);
	$idtipo=esc_sql($_POST['idtipo']);
	$postid=esc_sql($_POST['postid']);
	if($juegoid == 'ninguno') { die(); }
	
	$publicaciones = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$juegoid."'  ", OBJECT);
	
	//Obtengo datos del tipo del post actual
	if ($idtipo!=0){ $tipo_seleccionado=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` WHERE id='".$idtipo."' "); }
	
	?>	
	<div style="position:relative; display:inline-block; width:100%;">
		<p>
		<label><b><?php echo __('Dependence', 'game-admin'); ?></b></label><br/>
		<select size="2" id="form_seleccionar_padre" name="form_seleccionar_padre" style="cursor:pointer; height:120px; width:100%;" onchange="obtenerOrdenesDependenciaDisponibles()">
			<option value="0"><?php echo __('None / Base / Single', 'game-admin'); ?></option>
				<?php
				if ($publicaciones) {
						imprimir_publicaciones_de_juego_ordenadas_en_select ($postid, $juegoid, $idtipo);
				}					
				?>	
		</select>
		</p>
	</div>
	<?php
	if( ($idtipo==0) || ($tipo_seleccionado->asociarplataformas == 1) ){
	?>
		<p>	
		<div id="frame_plataformas" style="position:relative; display:inline-block; ">
			<label><b><?php echo __('Platform for this content', 'game-admin'); ?></b></label><br/>
			<select id="form_seleccionar_plataforma" name="form_seleccionar_plataforma" style="cursor:pointer; min-width:190px;" onchange="cambiarVisibilidadFormularioPlataformas()">
				<option value="todas"><?php echo __('Multi: All', 'game-admin'); ?></option>
				<option value="seleccionar"><?php echo __('Multi: Select', 'game-admin'); ?></option>
				<option value="ninguna"><?php echo __('None', 'game-admin'); ?></option>
				<option id="form_seleccionar_plataforma_heredar" style="display:none;" value="parent">Heredar</option>
				<?php
				$plataformasjuego = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$juegoid."' AND idcontenido='0' ORDER BY idplataforma", OBJECT);
				foreach($plataformasjuego as $plataformajuego){
					?>
					<option value="<?php echo($plataformajuego->idplataforma); ?>"><?php echo __('Unique: ', 'game-admin'); ?> <?php echo($arrayplataformas[$plataformajuego->idplataforma]); ?></option>
					<?php
				}
				?>			
			</select>			
		</div>
		</p>
		<p>		
				
		<div id="frame_seleccionar_plataformas"style="position:relative; display:inline-block; display:none; width:100%;">
			<p>
			<label><b><?php echo __('Select platforms', 'game-admin'); ?></b></label><br/><br/>	
			<?php
			$plataformascontenido = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$juegoid."' AND idcontenido='".$postid."' ", OBJECT);
			foreach ($plataformascontenido as $plataformacontenido) {
				$plataformascontenidoexistentes[$plataformacontenido->idplataforma]= true;
			}
			$plataformasjuego = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$juegoid."' AND idcontenido='0' ORDER BY idplataforma", OBJECT);
			foreach($plataformasjuego as $plataformajuego){
				$plataforma_field_id="form_seleccionar_plataforma_id_".$plataformajuego->idplataforma;
				?>
				<div style="position:relative; display:inline-block; margin-bottom:8px;">		
					<input <?php if(isset($plataformascontenidoexistentes[$plataformajuego->idplataforma])){ echo('checked'); } ?> type="checkbox" id="<?php echo($plataforma_field_id); ?>" name="<?php echo($plataforma_field_id); ?>" value='false' onclick="javascript: this.value=this.checked" >				
					<?php
					echo($arrayplataformas[$plataformajuego->idplataforma]);
					?>
					&nbsp;&nbsp;
				</div>
				<?php				
			}
			?>
			</p>
		</div>		
		</p>
	<?php
	}
	?>				
	<p>
	<div id="frame_seleccionar_orden" style="position:relative; display:inline-block;">
		<label><b><?php echo __('Order', 'game-admin'); ?></b></label><br/>
		<?php
		$publicacionesorden = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$juegoid."' AND idparent='0' AND idtipo='".$idtipo."' AND idpost!='".$postid."' ORDER BY orden ", OBJECT);
		?>
		<select id="form_seleccionar_orden" name="form_seleccionar_orden" style="cursor:pointer; min-width:70px; height:30px;">
			<?php
			for ( $i= 1; $i <= 30; $i++){
				$posiciondisponible=true;
				foreach ($publicacionesorden as $publicacionorden) {
					if( $publicacionorden->orden == $i ){
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
	</div>
	
	<?php
	if($idtipo!=0){ $restipo = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` WHERE id='".$idtipo."' "); }
	?>
	<div id="frame_nota" style="position:relative; <?php if( ($idtipo!=0) && ($restipo->tienenota==1) ){ echo('display:block;'); } else{ echo('display:none;'); } ?>" >				
		<div style="position:relative; display:inline-block;">
			<label><b><?php echo __('Rating', 'game-admin'); ?></b></label><br/>
			<input name="form_seleccionar_nota" id="form_seleccionar_nota" maxlength="40" onkeypress='return validateCharacterNumber(event, "form_seleccionar_nota"); '/>
		</div>
		<div style="position:relative; display:block;">
			<label><b><?php echo __('Review summary', 'game-admin'); ?></b></label><br/>
			<textarea name="form_ga_resumen" id="form_ga_resumen" style="width: 100%;" maxlength="800" rows="4" cols="50"></textarea>				
		</div>
	</div>
	</p>
	<?php
	
	die();	
}

add_action('wp_ajax_obtenerDatosDeJuego', 'obtenerDatosDeJuego');

?>