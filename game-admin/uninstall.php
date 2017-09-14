<?php

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {	exit(); };  
 
// Drop a custom db tables and options
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}gameadmin_juegoplataforma`" );
$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}gameadmin_plataformas`" );
$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}gameadmin_juegos`" );
$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}gameadmin_contenidos`" );
$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}gameadmin_tipos`" );
delete_option('gameadmin_options');
delete_option( 'gameadmin_version');
?>