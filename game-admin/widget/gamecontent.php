<?php
// Creating the widget 
class ga_gamecontent_widget extends WP_Widget {
		
	function __construct() {

		$widget_ops = array( 'classname' => 'ga_gamecontent', 'description' => __('List of Game Admin contents', 'ga_gamecontent') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'ga-gamecontent-widget' );
		parent::__construct( 'ga-gamecontent-widget', __('GameContent Widget (Game Admin)', 'ga_gamecontent'), $widget_ops, $control_ops );

	}
	
	public function obtener_nota_media($idjuego, $idtipo) {

		global $wpdb;
		$contenidos = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$idjuego."' AND idtipo='".$idtipo."' AND idparent='0' "); $suma=0; $numnotas=0;
		foreach ($contenidos as $contenido){ $suma+=$contenido->nota; $numnotas+=1;  }
		if($numnotas==0) { return false; } else { return($suma/$numnotas); }

	}
	
	public function imprimir_contenidos($idjuego, $idtipo, $displayreviewratings, $restipo=null, $contenidos=null, $arrayplataformas=null, $padre=0, $nivel=0, $cadenaindice="") {
		
		global $wpdb; $array_of_options = get_option( 'gameadmin_options' );
		
		if($padre==0){
			//LINEAFULL //Almaceno temoralmente los codigos y nombres de plataformas porque es algo que se usa repetidas veces
			$resultadosplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` "); foreach ( $resultadosplataformas as $rowplataformas ){  $arrayplataformas[$rowplataformas->id]=$rowplataformas->nombre; }
			$contenidos = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$idjuego."' ORDER BY orden");
			
			$restipo = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` WHERE id='".$idtipo."' ");
		} 
		//Calculo el margen dependiendo del nivel actual
		$i=0; $espacio=0; while($i<$nivel){ $espacio+=5; $i++; }
		
		foreach ($contenidos as $contenido) {

			if ( (get_post_status($contenido->idpost)=='publish') && ((($contenido->idparent!= 0) && ($contenido->idparent== $padre))  ||  (($contenido->idparent== 0) && ($contenido->idparent== $padre) && ($contenido->idtipo== $idtipo))) ) {
				if($padre==0){
					$cadenaplataformas="";
					$rescontenidoplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$contenido->idjuego."' AND idcontenido='".$contenido->idpost."' ");
					foreach ( $rescontenidoplataformas as $rescontenidoplataforma ) {
						if($cadenaplataformas!=""){ $cadenaplataformas .= ", "; }
						$cadenaplataformas .= $arrayplataformas[$rescontenidoplataforma->idplataforma];			
					}	
					if( ($displayreviewratings==1) && ($restipo->tienenota==1)){
						?>

						<div style="width: 100%; padding-left:<?php echo($espacio); ?>px; margin-top:5px; position: relative; display: inline-block;">
							<div style="width: 80%; left: 0px; position: relative;  display: inline-block;">
								<a style="font-weight: 600; font-size: 12px;" href="<?php echo(get_permalink($contenido->idpost)); ?>"><?php if($contenido->textoenlace!=''){ echo($contenido->textoenlace); } else{ echo(get_the_title($contenido->idpost)); } ?></a>
								<?php
								if( ($cadenaplataformas!="") && ($restipo->asociarplataformas==1) ){ ?><br/><span class="ga-gc-plataformas"> <?php echo($cadenaplataformas); ?> </span><?php }
								?>
							</div>				
							<div class="ga-gc-nota">
								<?php echo($contenido->nota); ?>
							</div>
						</div>				
						<?php
					} else {
						?>
						<div style="width: 100%; padding-left:<?php echo($espacio); ?>px; margin-top:5px; position: relative; display: inline-block;">
							<a  style="font-weight: 600;     font-size: 12px;"  href="<?php echo(get_permalink($contenido->idpost)); ?>"><?php if($contenido->textoenlace!=''){ echo($contenido->textoenlace); } else{ echo(get_the_title($contenido->idpost)); }  ?></a>
							<?php
							if( ($cadenaplataformas!="") && ($restipo->asociarplataformas==1) ){ ?><br/><span class="ga-gc-plataformas"> <?php echo($cadenaplataformas); ?> </span><?php }
							?>							
						</div>
						<?php					
					}
					$this->imprimir_contenidos($idjuego, $idtipo, $displayreviewratings, $restipo, $contenidos, $arrayplataformas, $contenido->idpost, $nivel+1);
				} else {
					?>
					<div style="width: 100%; padding-left:<?php echo($espacio); ?>px; margin-top:5px; position: relative; display: inline-block; ">
						<a  href="<?php echo(get_permalink($contenido->idpost)); ?>"><?php echo("<b>".$cadenaindice.$contenido->orden."."."</b> "); if($contenido->textoenlace!=''){ echo($contenido->textoenlace); } else{ echo(get_the_title($contenido->idpost)); }  ?></a>
						</a>
					</div>
					<?php
					$this->imprimir_contenidos($idjuego, $idtipo, $displayreviewratings, $restipo, $contenidos, $arrayplataformas, $contenido->idpost, $nivel+1, $cadenaindice.$contenido->orden.".");
				}
			}
		}
	
	}
	
	
	// Creating widget front-end
	public function widget( $args, $instance ) {
		if(is_single()){
			global $wp_query, $wpdb; $postactual = $wp_query->post; $array_of_options = get_option( 'gameadmin_options' );
			
			$rowcontenido=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".$postactual->ID."' ");			
			if($rowcontenido){

				$rowjuego=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` WHERE id='".$rowcontenido->idjuego."' ");

				$title = apply_filters( 'widget_title', $instance['title'] );
				// before and after widget arguments are defined by themes
				echo $args['before_widget'];
				if ( (! empty( $title )) && ($instance['displayheader']==1) ) { echo $args['before_title'] . $title  . $args['after_title']; }
				//LINEAFULL //Almaceno temoralmente los codigos y nombres de plataformas porque es algo que se usa repetidas veces
				$resultadosplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` "); foreach ( $resultadosplataformas as $rowplataformas ){  $arrayplataformas[$rowplataformas->id]=$rowplataformas->nombre; }					
				
				if($rowjuego->idimagen!='') { $urlimagen=wp_get_attachment_url($rowjuego->idimagen);} 
				else { $urlimagen=wp_get_attachment_url( get_post_thumbnail_id( $postactual->ID )); }

				?>
				<div class="ga-gc-widget" >
					<div style="width: 100%; position: relative; overflow: hidden; box-sizing: border-box;">
						<?php 
						if ($urlimagen!=null) {
							?>
							<img class="ga-gc-header-image" src="<?php echo($urlimagen); ?>" ></img>	
							<?php
						} 
						?>	
					</div>
					<div class="ga-gc-title-header">
						<span><?php echo ( '<b>'.$rowjuego->nombre.'</b>'); ?></span>		
					</div>		
					<div class="ga-gc-cell ga-gc-cell-strong" >
						<?php echo( __( 'Released on ').$rowjuego->ano); ?>
					</div>			
					<div class="ga-gc-cell ga-gc-cell-strong">
							<?php 
							$resjuegoplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$rowjuego->id."' AND idcontenido=0 ");
							$cadenaplataformas="";
							foreach ( $resjuegoplataformas as $rowjuegoplataformas ) {
									if($cadenaplataformas!=""){ $cadenaplataformas .= ", "; }
									$cadenaplataformas .= $arrayplataformas[$rowjuegoplataformas->idplataforma];			
							}
							echo(__("PLATFORMS: ").$cadenaplataformas);
							?>
					</div>
					<?php
					if($rowjuego->idexpansion!=0){
						$juegooriginal=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` WHERE id='".$rowjuego->idexpansion."' ");
						if(isset($juegooriginal->id)){	
							?>
							<div class="ga-gc-cell" >
								<b><?php echo(__("Expansion of:")); ?></b>
								<?php
								$juegooriginalcontenido=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$juegooriginal->id."' AND esmaincontenido='1' "); 
								if(isset($juegooriginalcontenido->idpost)){
									?>
									<a href="<?php echo(get_permalink($juegooriginalcontenido->idpost)); ?>"><?php echo("<b>".$juegooriginal->nombre."</b>") ?></a>
									<?php
								} else {
									echo("<b>".$juegooriginal->nombre."</b>");
								}
								?>
							</div>
							<?php						
						}
					}

					$resultadotipos = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` ORDER BY orden");
					foreach ($resultadotipos as $tipo) {
						$filascontenidos = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$rowjuego->id."' AND idtipo='".$tipo->id."' ORDER BY orden");
						if(count($filascontenidos)>0){
							if(($tipo->tienenota==1) && $instance['ratingcolorheader']) {
							
								$nota = $this->obtener_nota_media($rowcontenido->idjuego, $tipo->id);
								if($nota){
									$porcentajenota=(($nota-$array_of_options['worstrating'])*100)/($array_of_options['bestrating']-$array_of_options['worstrating']);
									if ($porcentajenota<=39){ $imagenfondo=plugins_url(plugin_dir_path('game-admin')).'game-admin/imagenes/0_4.png'; }
									else if ($porcentajenota<=59){ $imagenfondo=plugins_url(plugin_dir_path('game-admin')).'game-admin/imagenes/4_5.png'; }
									else if ($porcentajenota<=69){ $imagenfondo=plugins_url(plugin_dir_path('game-admin')).'game-admin/imagenes/5_7.png'; }
									else if ($porcentajenota<=85){ $imagenfondo=plugins_url(plugin_dir_path('game-admin')).'game-admin/imagenes/7_85.png'; }
									else { $imagenfondo=plugins_url(plugin_dir_path('game-admin')).'game-admin/imagenes/85_10.png'; }				
								}
								?>
								<div class="ga-gc-cell ga-gc-cell-header" style="background-image:url('<?php echo($imagenfondo); ?>');">
									<?php echo($tipo->nombre); ?>
								</div>					
								<?php
							} else {
								?>
								<div class="ga-gc-cell ga-gc-cell-header" >
									<?php echo($tipo->nombre);  ?>
								</div>					
								<?php					
							}
							?>
							<div class="ga-gc-cell ga-gc-cell-contenidos" >
								<?php
								$this->imprimir_contenidos($rowcontenido->idjuego, $tipo->id, $instance['displayreviewratings']);
								?>
							</div>							
							<?php
						}
					}
					
					
					
					$filasexpansiones = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` WHERE idexpansion='".$rowjuego->id."' ORDER BY ano");
					if(count($filasexpansiones)>0){
						?>
						<div class="ga-gc-cell ga-gc-cell-header" >
							<?php echo("Expansiones");  ?>
						</div>
						<div class="ga-gc-cell ga-gc-cell-contenidos" >
						<?php					
						foreach ($filasexpansiones as $expansion) {
							$filacontenido=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$expansion->id."' AND esmaincontenido='1' "); 
							if(isset($filacontenido->idpost)){
								?>
								<span style="display:block; margin-top:5px;"><a href="<?php echo(get_permalink($filacontenido->idpost)); ?>"><?php echo("<b>".$expansion->nombre."</b>") ?></a></span>
								<?php
							} else {
								?>
								<span style="display:block; margin-top:5px;"><?php echo __($expansion->nombre); ?></span>
								<?php
							}
						}
						?>
						</div>
						<?php
					}
					?>
				</div>
				<?php
				
				echo $args['after_widget'];
			}
		}
	}
			
	/* ************************************************************************************************************************************************************************************ */
	/* ********************************************************************************** Widget Backend ********************************************************************************** */
	/* ************************************************************************************************************************************************************************************ */
	public function form( $instance ) {
	
		if ( isset( $instance[ 'title' ] ) ) { $title = esc_attr($instance[ 'title' ]); $displayheader = esc_attr($instance['displayheader']); $displayreviewratings = esc_attr($instance['displayreviewratings']); $ratingcolorheader = esc_attr($instance['ratingcolorheader']); }
		else { $title = __( 'New title', 'wpb_widget_domain' ); $displayheader = 0; $displayreviewratings = 1; $ratingcolorheader = 1; }		
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php _e('Display widget title'); ?>"><?php _e('Display widget title:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('displayheader'); ?>" name="<?php echo $this->get_field_name('displayheader'); ?>">
				<option value="0" <?php if($displayheader==0){ echo('selected'); }?> ><?php _e('Disabled'); ?></option>
				<option value="1" <?php if($displayheader==1){ echo('selected'); }?> ><?php _e('Enabled'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php _e('Display rating for reviews'); ?>"><?php _e('Display rating for reviews:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('displayreviewratings'); ?>" name="<?php echo $this->get_field_name('displayreviewratings'); ?>">
				<option value="0" <?php if($displayreviewratings==0){ echo('selected'); }?> ><?php _e('Disabled'); ?></option>
				<option value="1" <?php if($displayreviewratings==1){ echo('selected'); }?> ><?php _e('Enabled'); ?></option>
			</select>
		</p>			
		<p>
			<label for="<?php _e('Variable heading color for reviews'); ?>"><?php _e('Variable heading color for reviews:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('ratingcolorheader'); ?>" name="<?php echo $this->get_field_name('ratingcolorheader'); ?>">
				<option value="0" <?php if($ratingcolorheader==0){ echo('selected'); }?> ><?php _e('Disabled'); ?></option>
				<option value="1" <?php if($ratingcolorheader==1){ echo('selected'); }?> ><?php _e('Enabled'); ?></option>
			</select>
		</p>		
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	$instance['displayheader'] = ( ! empty( $new_instance['displayheader'] ) ) ? strip_tags($new_instance['displayheader']) : '0';
	$instance['displayreviewratings'] = ( ! empty( $new_instance['displayreviewratings'] ) ) ? strip_tags($new_instance['displayreviewratings']) : '0';
	$instance['ratingcolorheader'] = ( ! empty( $new_instance['ratingcolorheader'] ) ) ? strip_tags($new_instance['ratingcolorheader']) : '0';
	return $instance;
	}
}


// Register and load the widget
function ga_gamecontent_load_widget() {
	register_widget( 'ga_gamecontent_widget' );
}
add_action( 'widgets_init', 'ga_gamecontent_load_widget' );