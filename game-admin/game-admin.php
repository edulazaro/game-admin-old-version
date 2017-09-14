<?php
/*
Plugin Name: Game Admin
Description: Plugin to organize games data.
Author: <a href="http://www.kenodo.com">Kenodo</a>
Version: 1.0.0
*/

define( 'GAMEADMIN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( GAMEADMIN_PLUGIN_DIR . 'ajax/obtenerListaDeJuegos.php');
require_once( GAMEADMIN_PLUGIN_DIR . 'ajax/obtenerDatosDeJuego.php');
require_once( GAMEADMIN_PLUGIN_DIR . 'ajax/obtenerOrdenesDependenciaDisponibles.php');
require_once( GAMEADMIN_PLUGIN_DIR . 'widget/gamecontent.php');

    //Set up localisation. First loaded overrides strings present in later loaded file
    $locale = apply_filters( 'plugin_locale', get_locale(), 'game-admin' );
    load_textdomain( 'game-admin', WP_LANG_DIR . "/game-admin-$locale.mo" );
	load_plugin_textdomain('game-admin', false, dirname(plugin_basename(__FILE__ )) . '/languages/');

	
function game_admin_load_plugin_textdomain() {
	$domain = 'game-admin';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}	
add_action( 'init', 'game_admin_load_plugin_textdomain' );	
	
/* *************************************************************** CREAR PAGINAS *************************************************************** */
/* *************************************************************** CREAR PAGINAS *************************************************************** */
/* *************************************************************** CREAR PAGINAS *************************************************************** */

function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
		'Game Admin',
        'Game Admin',
        'edit_posts',
        'game-admin/games.php',
        '',
        plugins_url(plugin_dir_path('game-admin').'game-admin/imagenes/icono.png' ),
        6
    );
	
	add_submenu_page( 'game-admin/games.php', 'Games', __('Games', 'game-admin'), 'edit_posts', 'game-admin/games.php', '');
	add_submenu_page( 'game-admin/games.php', 'Platforms', __('Platforms', 'game-admin'), 'manage_options', 'game-admin/platforms.php', '');
	add_submenu_page( 'game-admin/games.php', 'Types', __('Content types', 'game-admin'), 'manage_options', 'game-admin/types.php', '');
	add_submenu_page( 'game-admin/games.php', 'Options', __('Options', 'game-admin'), 'manage_options', 'game-admin/options.php', '');
	
}

add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/* ********************************************************************** ESTILOS ********************************************************************** */
/* ********************************************************************** ESTILOS ********************************************************************** */
/* ********************************************************************** ESTILOS ********************************************************************** */


function game_admin_style_define_header(){
	$array_of_options = get_option( 'gameadmin_options');
	include_once (dirname( __FILE__ ).'/css/game-admin-define-css.php');
}

function game_admin_load_plugin_css() {

	$array_of_options = get_option( 'gameadmin_options' );
	
	switch ($array_of_options['colorscheme']) { 
		case 'dark': wp_register_style( 'game-admin', plugins_url( 'game-admin/css/game-admin-dark.css' )); wp_enqueue_style('game-admin'); break;
		case 'bright': wp_register_style( 'game-admin', plugins_url( 'game-admin/css/game-admin-bright.css' )); wp_enqueue_style('game-admin'); break;
		case 'clean': wp_register_style( 'game-admin', plugins_url( 'game-admin/css/game-admin-clean.css' )); wp_enqueue_style('game-admin'); break;
		case 'define': add_action('wp_head', 'game_admin_style_define_header'); wp_enqueue_style('game-admin'); break;
	}

	switch ($array_of_options['include-font-awesome']) { 
		case 'gameadmin': wp_register_style('game-admin-font-awesome', plugins_url('game-admin/css/font-awesome.min.css')); wp_enqueue_style( 'game-admin-font-awesome' ); break;
		case 'bootstrapcdn': wp_register_style('game-admin-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css'); wp_enqueue_style( 'game-admin-font-awesome' ); break;
	}	
}

function color_picker_assets($hook) {
	if (is_admin() && (strpos($hook, 'game-admin/') !== false)) { wp_enqueue_style( 'wp-color-picker' ); wp_enqueue_script( 'my-script-handle', plugins_url('js/game-admin-scripts.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); }
	else { return; }
}

add_action('wp_enqueue_scripts', 'game_admin_load_plugin_css');
add_action( 'admin_enqueue_scripts', 'color_picker_assets' );

/* *************************************************************** INSTALL *************************************************************** */
/* *************************************************************** INSTALL *************************************************************** */
/* *************************************************************** INSTALL *************************************************************** */

/* Install script. */
function jal_install () {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name1 = $wpdb->prefix . 'gameadmin_juegoplataforma';
	$table_name2 = $wpdb->prefix . 'gameadmin_plataformas';
	$table_name3 = $wpdb->prefix . 'gameadmin_juegos';

	//Consultas separadas porque si no, dbDela falla
	$sql1 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}gameadmin_juegoplataforma` (
		idjuego int(11) NOT NULL,
		idcontenido int(11) DEFAULT 0,
		idplataforma int(11) NOT NULL,
		PRIMARY KEY (idjuego, idcontenido, idplataforma)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8; ";
	$sql2 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}gameadmin_plataformas` (
		id int(11) NOT NULL AUTO_INCREMENT,
		nombre varchar(100),
		PRIMARY KEY (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8; ";
	$sql3 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}gameadmin_juegos` (
		id int(11) NOT NULL AUTO_INCREMENT,
		nombre varchar(100),
		ano int(4),
		idimagen int(11),
		idexpansion int(11),
		PRIMARY KEY (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8; ";
	$sql4 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}gameadmin_contenidos` (
		idpost int(11) NOT NULL,
		idjuego int(11) NOT NULL,
		idtipo int(11) NOT NULL,
		idparent int(11) NOT NULL DEFAULT 0,
		textoenlace varchar(240),
		plataformas varchar(30) NOT NULL DEFAULT 'multi',
		orden int(11) NOT NULL DEFAULT 0,
		nota float,
		resumen text(800),
		esmaincontenido int(11) NOT NULL DEFAULT 0,
		PRIMARY KEY (idpost)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8; ";
	$sql5 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}gameadmin_tipos` (
		id int(11) NOT NULL AUTO_INCREMENT,
		nombre varchar(100),
		orden int(11) NOT NULL DEFAULT 0,
		tienenota tinyint(1) NOT NULL DEFAULT 0,
		asociarplataformas tinyint(1) NOT NULL DEFAULT 0,
		PRIMARY KEY (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8; ";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($sql1); dbDelta($sql2); dbDelta($sql3); dbDelta($sql4); dbDelta($sql5);
	
	$array_of_options = array(
		'colorscheme' => 'dark',
		'background-color' => '#1d1e20',
		'text-color' => '#dfdfdf',
		'text-color-2' => '#e03800',		
		'link-color' => '#fff',
		'link-hover-color' => '#919191',
		'include-font-awesome' => 'gameadmin',
		'gc-header-background-color' => '#32333b',
		'gc-header-background-color-2' => '#24252b',
		'gc-text-header-color' => '#fff',
		'rating-header-image' => 'right',
		'rhi-display' => 'number',
		'rhi-text-color' => '#ff6600',
		'rhi-background-color' => '#1d1e20',
		'rhi-border' => 'dashed',
		'rhi-border-color' => '#979797',
		're-header-text-color' => '#ffffff',
		're-rating-background-color' => '#11212b',
		're-border' => 'dashed',			
		're-rating-border-color' => '#26272b',
		'worstrating' => '0',
		'bestrating' => '10',		
		'includerating' => '1',
		'reviewstructureddata' => '0',
		'showratingdescription' => '1',	
		'ratingboxalignment' => 'right',
		'ga-reviewdescription-0' => 'Abysmal', 'ga-reviewdescription-10' => 'Terrible', 'ga-reviewdescription-20' => 'Bad', 'ga-reviewdescription-30' => 'Poor', 'ga-reviewdescription-40' => 'Mediocre',
		'ga-reviewdescription-50' => 'Fair', 'ga-reviewdescription-60' => 'Good', 'ga-reviewdescription-70' => 'Great', 'ga-reviewdescription-80' => 'Superb', 'ga-reviewdescription-90' => 'Essential', 'ga-reviewdescription-100' => 'Perfect',
		'header-image-crop-single' => '',
		'header-image-crop-archive' => ''		
		
	);
	if( !get_option('gameadmin_options') ) { update_option( 'gameadmin_options', $array_of_options ); }
	if( !get_option('gameadmin_version') ) { update_option( 'gameadmin_version', '1.0' ); }
	
}

register_activation_hook( __FILE__, 'jal_install' );

/* *************************************************************** FORMULARIO ADMINISTRAR EDITAR POST *************************************************************** */
/* *************************************************************** FORMULARIO ADMINISTRAR EDITAR POST *************************************************************** */
/* *************************************************************** FORMULARIO ADMINISTRAR EDITAR POST *************************************************************** */

add_action( 'add_meta_boxes', 'dmetaboxadd_metabox_gameadmin' );

function dmetaboxadd_metabox_gameadmin() {
	add_meta_box( 'metabox_gameadmin_contenido', 'Asociar a juego', 'metabox_gameadmin_contenido', 'post', 'normal', 'high' );
}


/*-----------------------------------------------------------------------------*
*  Función para imprimir las publicaciones en un select ordenadas en jerarquia *
-------------------------------------------------------------------------------*/
function imprimir_publicaciones_de_juego_ordenadas_en_select ($postid, $idjuego, $idtipo, $gameadmin_parent=0, $currentparent=0, $nivel=0, $arrayplataformas=null){
	global $wpdb;
	
	if(!$arrayplataformas){ $resultadosplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` "); foreach ( $resultadosplataformas as $rowplataformas ){  $arrayplataformas[$rowplataformas->id]=$rowplataformas->nombre; } }
	
	if($nivel==0) { $rescontenidos = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$idjuego."' AND idtipo='".$idtipo."' AND idparent='".$currentparent."' ORDER BY orden"); }
	else { $rescontenidos = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$idjuego."' AND idparent='".$currentparent."' ORDER BY orden"); }
	
	foreach ( $rescontenidos as $rowcontenido ){
		$i=0; $espacios=""; while($i<$nivel){ $espacios.="&nbsp;&nbsp;"; $i++; }
		
		if($nivel==0){
			$cadenaplataformas="";
			$resjuegoplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$rowcontenido->idjuego."' AND idcontenido='".$rowcontenido->idpost."' ");
			foreach ( $resjuegoplataformas as $rowjuegoplataformas ) {
				if($cadenaplataformas!=""){ $cadenaplataformas .= ", "; }
				$cadenaplataformas .= $arrayplataformas[$rowjuegoplataformas->idplataforma];			
			}
		}
		?>
		<option <?php if($rowcontenido->idpost==$postid){ echo("disabled"); } ?>  value="<?php echo($rowcontenido->idpost); ?>" <?php if($rowcontenido->idpost==$gameadmin_parent){ echo("selected"); } ?>> <?php  echo($espacios.get_the_title($rowcontenido->idpost)); if($nivel==0){ echo(" (".$cadenaplataformas.")"); } ?></option>
		<?php
		imprimir_publicaciones_de_juego_ordenadas_en_select ($postid, $idjuego, $idtipo, $gameadmin_parent, $rowcontenido->idpost, $nivel+1, $arrayplataformas);
	}	
}

/*-----------------------------------------------------------------------------*
*  Función para obtener el tipo parent de una publicacion 					   *
-------------------------------------------------------------------------------*/
function obtener_tipo_parent_publicacion ($idparent){
	global $wpdb;
	$row = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".$idparent."'");
	if (isset($row->idparent)){
		if($row->idparent==0) { $resultado=$row->idtipo; } else{ $resultado=obtener_tipo_parent_publicacion ($row->idparent);  }
		return $resultado;
	} else {
		return 1;
	}
}




function metabox_gameadmin_contenido( $post ) {
	
	global $wpdb;
	
	wp_enqueue_script('jquery');
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

	?>
	<script type="text/javascript">
		
		function loadListadoJuegos(){
			jQuery.ajax({
			type:"POST",
			dataType: 'html',
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			data: { action: "obtenerListaDeJuegos", plataforma: document.getElementById('form_filtrar_plataforma').value, letra: document.getElementById('form_filtrar_letra').value },
			success:function(response){
				jQuery("#frame_listado_juegos").html(response);
			}
			});
		}	
		
		function obtenerDatosDeJuego(){
			jQuery.ajax({ type:"POST", dataType: 'html', url: '<?php echo admin_url('admin-ajax.php'); ?>',
			data: { action: "obtenerDatosDeJuego", juego: document.getElementById('form_seleccionar_juego').value, idtipo: document.getElementById('form_seleccionar_tipo').value, postid:<?php echo($post->ID); ?> },
			success:function(response){ jQuery("#frame_datos_juego").html(response); }
			});
			if(document.getElementById('form_seleccionar_juego').value == 'ninguno') { document.getElementById('frame_seleccionar_tipo').style.display= 'none'; } else { document.getElementById('frame_seleccionar_tipo').style.display= 'inline-block';  }
		}
		
		function obtenerOrdenesDependenciaDisponibles(){
			if(document.getElementById('form_seleccionar_padre').value=='0'){
				document.getElementById('form_seleccionar_plataforma').value='todas';
				document.getElementById("frame_plataformas").style.display= 'inline-block';
			} else{
				document.getElementById("frame_plataformas").style.display= 'none';
			}
			jQuery.ajax({
			type:"POST",
			dataType: 'html',
			url: '<?php echo admin_url('admin-ajax.php'); ?>',
			data: { action: "obtenerOrdenesDependenciaDisponibles", postid:<?php echo($post->ID); ?>, juego: document.getElementById('form_seleccionar_juego').value, parentid: document.getElementById('form_seleccionar_padre').value, tipo: document.getElementById('form_seleccionar_tipo').value},
			success:function(response){
				jQuery("#frame_seleccionar_orden").html(response);
			}
			});
		}
		function cambiarVisibilidadFormularioPlataformas(){
			if(document.getElementById('form_seleccionar_plataforma').value=='seleccionar' ){ document.getElementById('frame_seleccionar_plataformas').style.display= 'inline-block'; }
			else{ document.getElementById('frame_seleccionar_plataformas').style.display= 'none'; }
		}
		
		function validarNota(e, id){
			var tecla = (document.all) ? e.keyCode : e.which;
			var te = String.fromCharCode(tecla);
			var patron_init =/[0-9\.]/;
			var patron =/(^[0-9]{1}[0-9|\.]?[0-9]?)/;
			if (patron_init.test(te)){
				var match = patron.exec(document.getElementById(id).value + te);
				if(match[1]){
					document.getElementById(id).value=match[1];
					return false
				}
				return false
			}
			return false;
		}		
	</script>
	
	<p>
	<input hidden name="form_post_id_check" id="form_post_id_check" value="<?php echo($post->ID); ?>" />
	<div style="position:relative; display:inline-block;">
		<label><b><?php echo __('Filter platform', 'game-admin'); ?></b></label><br/>
		<select  id="form_filtrar_plataforma" name="form_filtrar_plataforma" style="cursor:pointer;" onchange="loadListadoJuegos(); document.getElementById('frame_datos_juego').innerHTML = '';">  
			<option value="todas"><?php echo __('Any', 'game-admin'); ?></option>
				<?php
				$arrayplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` ORDER BY nombre");
				foreach ( $arrayplataformas as $row ) {
					?>
					<option value="<?php echo($row->id); ?>"> <?php echo($row->nombre); ?> </option>
					<?php						
				}
				?>
		</select>					
	</div>
	<div style="position:relative; display:inline-block;">
		<label><b><?php echo __('Filter by letter', 'game-admin'); ?></b></label><br/>
		<select id="form_filtrar_letra" name="form_filtrar_letra" style="cursor:pointer;" onchange="loadListadoJuegos(); document.getElementById('frame_datos_juego').innerHTML = '';">  
			<option value="todas"><?php echo __('Any', 'game-admin'); ?></option>
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
	
	<?php
	//Obtengo los datos de la publicacion de la base de datos
	$gameadmin_contenido=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".$post->ID."' ");	
	
	if (isset($gameadmin_contenido->idjuego)){
			
		//Obtengo datos del tipo del post actual
		if($gameadmin_contenido->idtipo != 0){ $gameadmin_contenido->tipos=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` WHERE id='".$gameadmin_contenido->idtipo."' "); }
		
		$rowjuego = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` WHERE id='".$gameadmin_contenido->idjuego."'");
		//LINEAFULL //Almaceno temoralmente los codigos y nombres de plataformas porque es algo que se usa repetidas veces
		$resultadosplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_plataformas` "); foreach ( $resultadosplataformas as $rowplataformas ){  $arrayplataformas[$rowplataformas->id]=$rowplataformas->nombre; }				
		$cadenaplataformas="";
		$resjuegoplataformas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$rowjuego->id."' AND idcontenido='0' ");
		foreach ( $resjuegoplataformas as $rowjuegoplataformas ) {
				if($cadenaplataformas!=""){ $cadenaplataformas .= ", "; }
				$cadenaplataformas .= $arrayplataformas[$rowjuegoplataformas->idplataforma];			
		}
		//Obtener publicaciones por si se quieren establecer dependencias
		$publicaciones = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$rowjuego->id."'  ", OBJECT);
	
		?>
		<p>
		<div id="frame_listado_juegos" style="position:relative; display:inline-block; width:100%;">
			<label><b><?php echo __('Select game', 'game-admin'); ?></b></label><br/>
			<select size="2" id="form_seleccionar_juego" name="form_seleccionar_juego" style="cursor:pointer;  height:200px; width:100%;" onchange="obtenerDatosDeJuego()">
				<option selected value="<?php echo($rowjuego->id); ?>"><?php echo($rowjuego->nombre." (".$cadenaplataformas.")"); ?></option>
				<option style="color:grey;" value="ninguno"><?php echo __('None: do not associate a game', 'game-admin'); ?></option>
			</select>
		</div>
		</p>
		<div id="frame_seleccionar_tipo" style="position:relative; width:100%; display:inline-block; ">
			<div style="position:relative; display:inline-block; width:60%;">
				<label><b><?php echo __('Select type', 'game-admin'); ?></b></label><br/>
				<select id="form_seleccionar_tipo" name="form_seleccionar_tipo" style="cursor:pointer; width:100%; vertical-align: baseline;" onchange="obtenerDatosDeJuego()">
					<option value="0" <?php if($gameadmin_contenido->idtipo == 0){ echo('selected'); } ?>><?php echo __('DEFAULT'); ?></option>
					<?php
					$resultadotipos = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` ORDER BY orden");
					foreach ($resultadotipos as $tipo) {
						?>
						<option value="<?php echo($tipo->id); ?>" <?php if($gameadmin_contenido->idtipo == $tipo->id){ echo('selected'); } ?>><?php echo($tipo->nombre); ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div style="position:relative; display:inline-block; width:38%;">
				<label><b><?php echo __('Is it the main content of the game? (One per game)', 'game-admin'); ?></b></label><br/>
				<select id="form_seleccionar_esmaincontenido" name="form_seleccionar_esmaincontenido" style="cursor:pointer; width:100%; vertical-align: baseline;">
					<option value="0" <?php if($gameadmin_contenido->esmaincontenido == 0){ echo('selected'); } ?>><?php echo __('No', 'game-admin'); ?></option>
					<option value="1" <?php if($gameadmin_contenido->esmaincontenido == 1){ echo('selected'); } ?>><?php echo __('Yes', 'game-admin'); ?></option>
				</select>
			</div>			
			<div style="position:relative; display:inline-block; width:100%; margin-top:15px;">
				<label><b><?php echo __('Text link', 'game-admin'); ?></b></label><br/>
				<input name="form_textoenlace" id="form_textoenlace" value="<?php if(isset($gameadmin_contenido->textoenlace)){ echo($gameadmin_contenido->textoenlace); } ?>" style="width:100%;" maxlength="240" />
			</div>				
		</div>
		<div id="frame_datos_juego" style="position:relative; display:inline-block; width:100%; margin-top:20px;">

			<div style="position:relative; display:inline-block; width:100%;">
				<label><b><?php echo __('Dependence', 'game-admin'); ?></b></label><br/>
				<select size="2"   id="form_seleccionar_padre" name="form_seleccionar_padre" style="cursor:pointer; height:120px; width:100%;" onchange="obtenerOrdenesDependenciaDisponibles()">
					<option value="0" <?php if($gameadmin_contenido->idparent==0){ echo("selected"); } ?>><?php echo __('None / Base / Single', 'game-admin'); ?></option>
					<?php
					if ($publicaciones) {
						imprimir_publicaciones_de_juego_ordenadas_en_select ($post->ID, $gameadmin_contenido->idjuego, $gameadmin_contenido->idtipo, $gameadmin_contenido->idparent);
					}					
					?>			
				</select>
			</div>
			<?php
			if( ($gameadmin_contenido->idtipo==0) || ($gameadmin_contenido->tipos->asociarplataformas == 1) ){
				?>
				<p>			
				<div id="frame_plataformas" style="position:relative; width:100%; <?php if($gameadmin_contenido->plataformas=='parent'){ echo('display:none;'); } else { echo('display:inline-block;'); } ?>">
					<label><b><?php echo __('Platform for this content', 'game-admin'); ?></b></label><br/>
					<select  id="form_seleccionar_plataforma" name="form_seleccionar_plataforma" style="cursor:pointer; min-width:190px;" onchange="cambiarVisibilidadFormularioPlataformas()">
						<option value="todas" <?php if($gameadmin_contenido->plataformas == 'multi'){ echo('selected'); } ?>><?php echo __('Multi: All', 'game-admin'); ?></option>
						<option value="seleccionar" <?php if($gameadmin_contenido->plataformas == 'seleccionar'){ echo('selected'); } ?>><?php echo __('Multi: Select', 'game-admin'); ?></option>
						<option value="ninguna" <?php if($gameadmin_contenido->plataformas == 'ninguna'){ echo('selected'); } ?>><?php echo __('None', 'game-admin'); ?></option>
						<?php
						$plataformasjuego = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$gameadmin_contenido->idjuego."' AND idcontenido='0' ORDER BY idplataforma", OBJECT);
						foreach($plataformasjuego as $plataformajuego){
							?>
							<option value="<?php echo($plataformajuego->idplataforma); ?>" <?php if($gameadmin_contenido->plataformas == $plataformajuego->idplataforma){ echo('selected'); } ?>><?php echo __('Unique: ', 'game-admin'); ?> <?php echo($arrayplataformas[$plataformajuego->idplataforma]); ?></option>
							<?php
						}
						?>					
					</select>
				</div>
				</p>
				<p>
				<div id="frame_seleccionar_plataformas"style="position:relative; width:100%; <?php if($gameadmin_contenido->plataformas!='seleccionar'){ echo("display:none;"); } else{ echo("display:inline-block;"); } ?> ">
					<label><b><?php echo __('Select platforms', 'game-admin'); ?></b></label><br/><br/>	
					<?php
					
					$plataformascontenido = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$gameadmin_contenido->idjuego."' AND idcontenido='".$post->ID."' ", OBJECT);
					foreach ($plataformascontenido as $plataformacontenido) {
						$plataformascontenidoexistentes[$plataformacontenido->idplataforma]= true;
					}
					
					$plataformasjuego = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$gameadmin_contenido->idjuego."' AND idcontenido='0' ORDER BY idplataforma", OBJECT);
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
				</div>		
				</p>
			<?php
			}
			?>
			<p>
			<div id="frame_seleccionar_orden" style="position:relative; display:inline-block;">

					<label><b><?php echo __('Order', 'game-admin'); ?></b></label><br/>
					<select id="form_seleccionar_orden" name="form_seleccionar_orden" style="cursor:pointer; min-width:70px; height:30px;">
						<?php
						$publicacionesorden = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idjuego='".$gameadmin_contenido->idjuego."' AND idparent='".$gameadmin_contenido->idparent."' AND idpost!='".$post->ID."' ORDER BY orden ", OBJECT);
						
						for ( $i= 1; $i <= 60; $i++){
							$posiciondisponible=true;
							foreach ($publicacionesorden as $publicacionorden) {
								if( $gameadmin_contenido->idparent == 0){
									if( ($publicacionorden->orden == $i) && ($gameadmin_contenido->idtipo == $publicacionorden->idtipo) ){
										$posiciondisponible=false;
									}
								} else {
								
									if( $publicacionorden->orden == $i ){
										$posiciondisponible=false;
									}
								}
							}
							if($posiciondisponible==true){
								?>
								<option value="<?php echo($i); ?>" <?php if($gameadmin_contenido->orden==$i){ echo("selected"); } ?> ><?php echo($i); ?></option>
								<?php
							}
						}
						?>
					</select>				
			</div>			
			</p>
			<p>
				<?php
				if($gameadmin_contenido->idtipo!=0){ $restipo = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` WHERE id='".$gameadmin_contenido->idtipo."' "); }
				?>
				<div id="frame_nota" style="position:relative; <?php if(($gameadmin_contenido->idtipo!=0) && ($restipo->tienenota==1)){ echo('display:block;'); } else{ echo('display:none;'); } ?>" >				
					<div style="position:relative; display:inline-block;">
						<label><b><?php echo __('Rating', 'game-admin'); ?></b></label><br/>
						<input name="form_seleccionar_nota" id="form_seleccionar_nota" value="<?php if(isset($gameadmin_contenido->nota)){ echo($gameadmin_contenido->nota); } ?>" maxlength="40" onkeypress='return validateCharacterNumber(event, "form_seleccionar_nota"); '/>
					</div>
					<div style="position:relative; display:block;">
						<label><b><?php echo __('Review summary', 'game-admin'); ?></b></label><br/>
						<textarea name="form_ga_resumen" id="form_ga_resumen" style="width: 100%;" maxlength="800" rows="4" cols="50"><?php echo($gameadmin_contenido->resumen); ?></textarea>				
					</div>
				</div>
			</p>
		</div>
		<?php
	} else {
		?>
		<div id="frame_listado_juegos" style="position:relative; display:inline-block; width:100%;">
			<p>
			<div style="position:relative; display:inline-block; width:100%">
				<label><b><?php echo __('Select game', 'game-admin'); ?></b></label><br/>
				<select  size="2" id="form_seleccionar_juego" name="form_seleccionar_juego" style="height:30px; cursor:pointer; width:100%;">
					<option style="color:grey;" value="ninguno"><?php echo __('None: no not associate to any game', 'game-admin'); ?></option>
				</select>
			</div>		
			</p>
		</div>
		<div id="frame_seleccionar_tipo" style="position:relative; width:100%; display:none;">
			<div style="position:relative; display:inline-block; width:60%;">
				<label><b><?php echo __('Select type', 'game-admin'); ?></b></label><br/>
				<select id="form_seleccionar_tipo" name="form_seleccionar_tipo" style="cursor:pointer; width:100%; vertical-align: baseline;" onchange="obtenerDatosDeJuego()">
					<?php
					$resultadotipos = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` ORDER BY orden");
					foreach ($resultadotipos as $tipo) {
						?>
						<option value="<?php echo($tipo->id); ?>"><?php echo($tipo->nombre); ?></option>
						<?php
					}
					?>	
				</select>	
			</div>
			<div style="position:relative; display:inline-block; width:38%;">
				<label><b><?php echo __('Is it the main content of the game? (One per game)', 'game-admin'); ?></b></label><br/>
				<select id="form_seleccionar_esmaincontenido" name="form_seleccionar_esmaincontenido" style="cursor:pointer; width:100%; vertical-align: baseline;">
					<option value="0"><?php echo __('No', 'game-admin'); ?></option>
					<option value="1"><?php echo __('Yes', 'game-admin'); ?></option>
				</select>
			</div>				
			<div style="position:relative; display:block; width:100%; margin-top:15px;">
				<label><b><?php echo __('Text link', 'game-admin'); ?></b></label><br/>
				<input name="form_textoenlace" id="form_textoenlace" style="width:100%;" maxlength="240" />
			</div>			
		</div>
		
		<div id="frame_datos_juego" style="position:relative; display:inline-block; width:100%;">
	
		</div>
		<?php	
	}

}


/* *************************************************************** FORMULARIO ADMINISTRAR EDITAR POST GUARDAR *************************************************************** */
/* *************************************************************** FORMULARIO ADMINISTRAR EDITAR POST GUARDAR *************************************************************** */
/* *************************************************************** FORMULARIO ADMINISTRAR EDITAR POST GUARDAR *************************************************************** */

add_action( 'save_post', 'metabox_gameadmin_contenido_save' );

function metabox_gameadmin_contenido_save( $post_id ) {

	if(get_post_type ($post_id)!='post'){ return; }
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; // Bail if we're doing an auto save
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return; // if our nonce isn't there, or we can't verify it, bail

	global $wpdb;

	//SI CAMBIA EL ID DEL POST, QUE PUEDE PASAR
	if($post_id != $_POST['form_post_id_check']) {
		$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_juegoplataforma` SET idcontenido='".$post_id."' WHERE  idcontenido='".$_POST['form_post_id_check']."' ");
		$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_contenidos` SET idpost='".$post_id."' WHERE  idpost='".$_POST['form_post_id_check']."' ");
		$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_contenidos` SET idparent='".$post_id."' WHERE  idparent='".$_POST['form_post_id_check']."' ");
	}
	
	//ELIMINAR
	if ( (!isset($_POST['form_seleccionar_juego'])) || ($_POST['form_seleccionar_juego'] == 'ninguno') ) {
		$row_contenido=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".$post_id."' ");
		if($row_contenido->idpost){
			$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idcontenido='".$post_id."' ");
			$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".$post_id."' ");
			$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_contenidos` SET idparent='0', plataformas='ninguna' WHERE  idparent='".$post_id."' ");			
		}
	//ACTUALIZAR O INSERTAR
	} else {
		
		//Si es contenido principal, pongo el del resto de contenidos del juego a cero
		if($_POST['form_seleccionar_esmaincontenido'] == 1){ $wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_contenidos` SET esmaincontenido='0' WHERE idjuego='".$_POST['form_seleccionar_juego']."' "); }
		
		//Si es un contenido heredado, plataforma en parent (heredado)
		if($_POST['form_seleccionar_padre'] != 0){ $_POST['form_seleccionar_plataforma']='parent'; } 
		// Contenido sin plataformas debido al tipo
		else if(!isset($_POST['form_seleccionar_plataforma'])) { $_POST['form_seleccionar_plataforma']= 'ninguna';}
		//Contenido de plataforma unica
		else if( ($_POST['form_seleccionar_plataforma']!='todas') && ($_POST['form_seleccionar_plataforma']!='seleccionar') && ($_POST['form_seleccionar_plataforma']!='ninguna') ){  $plataformaunicaid=$_POST['form_seleccionar_plataforma']; $_POST['form_seleccionar_plataforma']='unica';  }
		
		//COMPRUEBO SI EXISTE EL CONTENIDO PARA ACTUALIZARLO O INSERTARLO
		$row_contenido=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".$post_id."' ");
		//ACTUALIZAR CONTENIDOS
		if($row_contenido->idpost){
			$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_contenidos` SET idjuego='".$_POST['form_seleccionar_juego']."', idtipo='".$_POST['form_seleccionar_tipo']."', idparent='".$_POST['form_seleccionar_padre']."', textoenlace='".$_POST['form_textoenlace']."', plataformas='".$_POST['form_seleccionar_plataforma']."', orden='".$_POST['form_seleccionar_orden']."', nota='".$_POST['form_seleccionar_nota']."', resumen='".$_POST['form_ga_resumen']."', esmaincontenido='".$_POST['form_seleccionar_esmaincontenido']."' WHERE  idpost='".$post_id."' ");
			$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idcontenido='".$post_id."' AND idcontenido!='0' ");	
		}
		//INSERTAR CONTENIDO
		else{
			$wpdb->get_results("INSERT INTO `{$wpdb->prefix}gameadmin_contenidos` (idpost,idjuego,idtipo,idparent, textoenlace, plataformas, orden, nota, resumen, esmaincontenido) VALUES ('".$post_id."','".$_POST['form_seleccionar_juego']."','".$_POST['form_seleccionar_tipo']."','".$_POST['form_seleccionar_padre']."','".$_POST['form_textoenlace']."','".$_POST['form_seleccionar_plataforma']."','".$_POST['form_seleccionar_orden']."','".$_POST['form_seleccionar_nota']."', '".$_POST['form_ga_resumen']."', '".$_POST['form_seleccionar_esmaincontenido']."')");
		}
		//COMO AL ACTUALIZAR BORRO LAS PLATAFORMAS; SIEMPRE LAS INSERTO DE CERO
		if($_POST['form_seleccionar_plataforma']=='seleccionar') {
			$plataformascontenido = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$_POST['form_seleccionar_juego']."' AND idcontenido='0' ", OBJECT);
			foreach ($plataformascontenido as $plataformacontenido) {
				$plataforma_field_id="form_seleccionar_plataforma_id_".$plataformacontenido->idplataforma;
				if( isset($_POST[$plataforma_field_id]) && ($_POST[$plataforma_field_id]== true) ){
					$wpdb->get_results("INSERT INTO `{$wpdb->prefix}gameadmin_juegoplataforma` (idjuego, idcontenido, idplataforma)  VALUES ('".$_POST['form_seleccionar_juego']."', '".$post_id."', '".$plataformacontenido->idplataforma."') ");
				}
			}
		} else if($_POST['form_seleccionar_plataforma']=='todas') {
			$plataformascontenido = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idjuego='".$_POST['form_seleccionar_juego']."' AND idcontenido='0' ", OBJECT);
			foreach ($plataformascontenido as $plataformacontenido) {
				$wpdb->get_results("INSERT INTO `{$wpdb->prefix}gameadmin_juegoplataforma` (idjuego, idcontenido, idplataforma)  VALUES ('".$_POST['form_seleccionar_juego']."', '".$post_id."', '".$plataformacontenido->idplataforma."') ");
			}
		} else if($_POST['form_seleccionar_plataforma']=='unica') {
			$wpdb->get_results("INSERT INTO `{$wpdb->prefix}gameadmin_juegoplataforma` (idjuego, idcontenido, idplataforma)  VALUES ('".$_POST['form_seleccionar_juego']."', '".$post_id."', '".$plataformaunicaid."') ");
		}		
	}
}

/* *************************************************************************************************************************************************************** */
/* ************************************************************************** DELETE POST ACTION ***************************************************************** */
/* *************************************************************************************************************************************************************** */

add_action( 'delete_post', 'gameadmin_contenido_delete' );
function gameadmin_contenido_delete( $post_id ) {
	global $wpdb;
	$row_contenido=$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".$post_id."' ");
	if($row_contenido->idpost){
		$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_juegoplataforma` WHERE idcontenido='".$post_id."' ");
		$wpdb->get_results("DELETE FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".$post_id."' ");
		$wpdb->get_results("UPDATE `{$wpdb->prefix}gameadmin_contenidos` SET idparent='0', plataformas='ninguna' WHERE  idparent='".$post_id."' ");			
	}	
}    


/* *************************************************************** INSERTAR NOTA AL FINAL DEL POST *************************************************************** */
/* *************************************************************** INSERTAR NOTA AL FINAL DEL POST *************************************************************** */
/* *************************************************************** INSERTAR NOTA AL FINAL DEL POST *************************************************************** */
function insertaDatosNota($content) {
	global $wpdb;
	$array_of_options = get_option( 'gameadmin_options' );

	
	if( !is_feed() && !is_home() && is_single() && ($array_of_options['includerating']==1) && ($rescontenido = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".get_the_ID ()."'"))) {
		
		if( $rescontenido->idparent!=0 ){ $idtipofinal=obtener_tipo_parent_publicacion ($rescontenido->idparent); } 
		else { $idtipofinal=$rescontenido->idtipo; }
		$restipo = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` WHERE id='".$idtipofinal."'");
		if($restipo->tienenota==1){
		
			$porcentajenota=(($rescontenido->nota-$array_of_options['worstrating'])*100)/($array_of_options['bestrating']-$array_of_options['worstrating']);
			$porcentajenotamensaje="";
			
			switch (true) { case ( ($porcentajenota >= 0) && ($porcentajenota < 10) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-0']; break;
				case ( ($porcentajenota >= 10) && ($porcentajenota < 20) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-10']; break;
				case ( ($porcentajenota >= 20) && ($porcentajenota < 30) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-20']; break;
				case ( ($porcentajenota >= 30) && ($porcentajenota < 40) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-30']; break;
				case ( ($porcentajenota >= 40) && ($porcentajenota < 50) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-40']; break;
				case ( ($porcentajenota >= 50) && ($porcentajenota < 60) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-50']; break;
				case ( ($porcentajenota >= 60) && ($porcentajenota < 70) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-60']; break;
				case ( ($porcentajenota >= 70) && ($porcentajenota < 80) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-70']; break;
				case ( ($porcentajenota >= 80) && ($porcentajenota < 90) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-80']; break;
				case ( ($porcentajenota >= 90) && ($porcentajenota < 100) ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-90']; break;
				case ( $porcentajenota == 100 ): $porcentajenotamensaje= $array_of_options['ga-reviewdescription-100']; break;
			}
			switch ($array_of_options['ratingboxalignment']) {
				case 'right':  $garatingblockclase='ga-rating-block';        $garatingboxclase='ga-rating-box-right';  break;
				case 'left':   $garatingblockclase='ga-rating-block';        $garatingboxclase='ga-rating-box-left';   break;
				case 'center': $garatingblockclase='ga-rating-block-center'; $garatingboxclase='ga-rating-box-center'; break;
			}
			
			$resjuego = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_juegos` WHERE id='".$rescontenido->idjuego."'");
			
			if($array_of_options['reviewstructureddata']==1) {
				if(has_post_thumbnail()){ $urlimagen=wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID ()), array( 300,300 ), false, '' )[0]; }
				else if ( $resjuego->idimagen!=0) { $urlimagen=wp_get_attachment_url($resjuego->idimagen); }
				
				if($urlimagen){ $content= '<div itemscope itemtype="http://schema.org/Review"><meta itemprop="thumbnailUrl" content="'.$urlimagen.'"/><meta itemprop="datePublished" content="'.get_the_time( 'c').'"><div temprop="reviewBody">'.$content.'</div>'; }
				else{ $content= '<div itemscope itemtype="http://schema.org/Review"><meta itemprop="datePublished" content="'.get_the_time( 'c').'"><div temprop="reviewBody">'.$content.'</div>'; }
				$content.= '<div class="ga-block-divider" ></div>';
				$content.= '<div class="'.$garatingblockclase.'">';
					$content.= '<span style="display:block; text-align: left;"><span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing"><span class="ga-after-box-title" itemprop="name">'.$resjuego->nombre.'</span></span> <b>['.$resjuego->ano.']</b></span>';
					$content.= '<div class="'.$garatingboxclase.'">';
						$content.= "<div class='ga-container-nota ga-text-color-2'>";
							$content.= '<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" style="vertical-align: middle;" ><meta itemprop="worstRating" content="'.$array_of_options['worstrating'].'"/><span itemprop="ratingValue" style="font-size:36px; vertical-align: middle;" >'.$rescontenido->nota.'</span><meta itemprop="bestRating" content="'.$array_of_options['bestrating'].'"/></span>';
						$content.= "</div>";
						if($array_of_options['showratingdescription']==1){
							$content.= "<span class='ga-review-description ga-text-color-2'>".$porcentajenotamensaje."</span>";
						}
					$content.= "</div>";								
					$content.= '<span style="display:block; padding-top:8px;">'.$rescontenido->resumen.'</span>';
					$content.= '<span style="display:block; padding-top:8px;">'.__('Analizado por ', 'game-admin').'<span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name" class="ga-text-color-2" style="font-weight:600;">'.get_the_author().'</span></span></span>';
				$content.= "</div>";			
				$content.='</div>';
			}
			else {
			
				$content.= '<div class="ga-block-divider" ></div>';
				$content.= '<div class="'.$garatingblockclase.'">';
						$content.= '<span style="display:block; text-align: left;"><span class="ga-after-box-title">'.$resjuego->nombre.'</span> <b>('.$resjuego->ano.')</b></span>';
						$content.= '<div class="'.$garatingboxclase.'">';
							$content.= '<div class="ga-container-nota ga-text-color-2" >'.$rescontenido->nota.'</div>';
							if($array_of_options['showratingdescription']==1){
								$content.= "<span class='ga-review-description ga-text-color-2'>".$porcentajenotamensaje."</span>";
							}
						$content.= "</div>";								
						$content.= '<span style="display:block; padding-top:8px;">'.$rescontenido->resumen.'</span>';
						$content.= '<span style="display:block; padding-top:8px;">'.__('Analizado por ', 'game-admin').'<span class="ga-text-color-2" style="font-weight:600;">'.get_the_author().'</span></span>';
				$content.= "</div>";			

			}
		}
		
	}
return $content;
}
add_filter ('the_content', 'insertaDatosNota');


function insertaDatosNotaHeader($postthumbnail) {
	global $wpdb;
	$array_of_options = get_option( 'gameadmin_options' );
	if(
	(($array_of_options['rhi-display']=='singlearchive') || (($array_of_options['rhi-display']=='single') && is_single())  || (($array_of_options['rhi-display']=='archive') && !is_single())) 
	&& !is_feed() && ($rescontenido = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_contenidos` WHERE idpost='".get_the_ID ()."'"))
	) {
	
		if( $rescontenido->idparent!=0 ){ $idtipofinal=obtener_tipo_parent_publicacion ($rescontenido->idparent); } 
		else { $idtipofinal=$rescontenido->idtipo; }
		
		$restipo = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}gameadmin_tipos` WHERE id='".$idtipofinal."'");
		if($restipo->tienenota==1){
		
			if($array_of_options['rhi-display-as']=='number'){
				?>
				<div class="ga-rhi" style="<?php echo($array_of_options['rhi-position'].":10px;"); ?>" ><?php echo($rescontenido->nota); ?></div>
				<?php
			} else if($array_of_options['rhi-display-as']=='stars'){
				$porcentajenota=(($rescontenido->nota-$array_of_options['worstrating'])*100)/($array_of_options['bestrating']-$array_of_options['worstrating']);
				if(($porcentajenota !="") && ($porcentajenota !=null)  ){
					?>
					<div class="ga-rhi-stars" style="<?php echo($array_of_options['rhi-position'].":10px;"); ?>">
						<?php
						if(  ($porcentajenota >= 0) && ($porcentajenota < 10)){ ?> <div class="ga-rhi-stars-inside-rating ga-r-0"></div> <?php }
						else if( ($porcentajenota >= 10) && ($porcentajenota < 20)){ ?> <div class="ga-rhi-stars-inside-rating ga-r-05"></div> <?php }
						else if(($porcentajenota >= 20) && ($porcentajenota <30) ){ ?> <div class="ga-rhi-stars-inside-rating ga-r-1"></div> <?php }
						else if(($porcentajenota >= 30) && ($porcentajenota <40) ){  ?> <div class="ga-rhi-stars-inside-rating ga-r-15"></div> <?php } 						
						else if(($porcentajenota >= 40) && ($porcentajenota < 50)){ ?> <div class="ga-rhi-stars-inside-rating ga-r-2"></div> <?php }
						else if(($porcentajenota >= 50) && ($porcentajenota < 60)){ ?> <div class="ga-rhi-stars-inside-rating ga-r-25"></div> <?php }
						else if(($porcentajenota >= 60) && ($porcentajenota < 70 )){ ?> <div class="ga-rhi-stars-inside-rating ga-r-3"></div> <?php }
						else if(($porcentajenota >= 70) && ($porcentajenota < 80)){ ?> <div class="ga-rhi-stars-inside-rating ga-r-35"></div> <?php }
						else if(($porcentajenota >= 80) && ($porcentajenota < 90)){ ?> <div class="ga-rhi-stars-inside-rating ga-r-4"></div> <?php }
						else if($porcentajenota >= 90 && $porcentajenota < 100){ ?> <div class="ga-rhi-stars-inside-rating ga-r-45"></div> <?php }
						else if($porcentajenota >= 100 ){ ?> <div class="ga-rhi-stars-inside-rating ga-r-5"></div> <?php }
						?>
					</div>
					<?php					
				}				
			}
		}
	}
	
	if (is_single() && ($array_of_options['header-image-crop-single']!='')){
		?>
        <a href="<?php the_permalink(); ?>">
            <?php
			$img_url = wp_get_attachment_url( get_post_thumbnail_id(),'full'); //get img URL
            ?><div style="width: 100%; height: <?php echo($array_of_options['header-image-crop-single']); ?>px; position: relative; overflow: hidden;"><img style="min-height: 100%; min-width: 100%; position: absolute; top: -9999px; bottom: -9999px; left: -9999px; margin: auto; right: -9999px;	" src="<?php echo $img_url; ?>" /></div>
        </a>
		<?php		
	} else if (!is_single() && ($array_of_options['header-image-crop-archive']!='')){
		?>
        <a href="<?php the_permalink(); ?>">
            <?php
			$img_url = wp_get_attachment_url( get_post_thumbnail_id(),'full'); //get img URL
            ?><div style="width: 100%; height: <?php echo($array_of_options['header-image-crop-archive']); ?>px; position: relative; overflow: hidden;"><img style="min-height: 100%; min-width: 100%; position: absolute; top: -9999px; bottom: -9999px; left: -9999px; margin: auto; right: -9999px;	"  src="<?php echo $img_url; ?>" /></div>
        </a>
		<?php		
	} else { return $postthumbnail; }

}
//add_filter( 'post_thumbnail_html', 'insertaDatosNotaHeader' );

?>