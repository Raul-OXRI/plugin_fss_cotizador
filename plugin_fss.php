<?php
/**
 * Plugin name: Plugin Cotizador
 * Plugin rul: https://github.com/Raul-OXRI/plugin_fss_cotizador_cotizador
 * Description: El funcionamiento del plugin es sacar el costo aproximado de cotizacion
 * Author: José Raúl Botzoc Mérida
 * Version: 2.0
 */

require_once dirname(__FILE__) . '/clases/codigocorto.class.php';

 function Activar(){
    global $wpdb;
    $sql ="CREATE TABLE IF NOT EXISTS {$wpdb->prefix}productos(
        `ProductoId` INT NOT NULL AUTO_INCREMENT,
        `Nombre` VARCHAR(45) NULL,
        `ShortCode` VARCHAR(45) NULL,
        PRIMARY KEY (`ProductoId`));";
    $wpdb->query($sql);
    
    $sql2 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}productos_detalle(
        `DetalleId` INT NOT NULL AUTO_INCREMENT,
        `ProductoId` INT NULL,
        `Precio` DECIMAL(10, 2) NULL,
        PRIMARY KEY (`DetalleId`));";
    $wpdb->query($sql2);
 }

 function Desactivar(){
    flush_rewrite_rules();
 }

 register_activation_hook(__FILE__,'Activar');
 register_deactivation_hook(__FILE__,'Desactivar');

 add_action('admin_menu', 'CrearMenu');

 function CrearMenu(){
    add_menu_page(
        'Cotizador de prodcutos',//Titulo de la pagina
        'Menu de cotizador',// Titulo del menu
        'manage_options', // Capability
        plugin_dir_path(__FILE__).'admin/lista_productos.php', //slug
        null, //function del contenido
         plugin_dir_url(__FILE__).'admin/img/icon.png',//icono
         '1' //priority
    );
 }

 function EncontrarBootstrapJS($hook){
    if ($hook != "plugin_fss_cotizador/admin/lista_productos.php"){
        return;
    }
    wp_enqueue_script('bootstrapJs',plugins_url('admin/bootstrap/js/bootstrap.min.js', __FILE__), array('jquery'));
 }
 add_action('admin_enqueue_scripts','EncontrarBootstrapJS');

 function EncontrarBootstrapCSS($hook){
    if ($hook != "plugin_fss_cotizador/admin/lista_productos.php") {
        return;
    }
    wp_enqueue_style('bootstrapCSS', plugins_url('admin/bootstrap/css/bootstrap.min.css', __FILE__));
 }
 add_action('admin_enqueue_scripts', 'EncontrarBootstrapCSS');

 function EncontrarJS($hook){
    if ($hook != "plugin_fss_cotizador/admin/lista_productos.php") {
        return;
    }
    wp_enqueue_script('JsExterno', plugins_url('admin/js/lista_productos.js', __FILE__),array('jquery'));
    wp_localize_script('JsExterno', 'SolicitudesAjax',[
        'url' => admin_url('admin-ajax.php'),
        'seguridad' => wp_create_nonce('seg')
    ]);
 }
 add_action('admin_enqueue_scripts','EncontrarJS');

 function EliminarProduto(){
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'seg')) {
        die('No tiene permiso para ejecutar ese ajax');
    }
    $id = $_POST['id'];
    global $wpdb;
    $tabla = "{$wpdb->prefix}productos";
    $tabla2 = "{$wpdb->prefix}productos_detalle";
    $wpdb->delete($tabla,array('ProductoId' => $id));
    $wpdb->delete($tabla2,array('ProductoId' => $id));
    return true;
 }
 add_action('wp_ajax_peticioneliminar','EliminarProduto');

// para genrear el shorcode


function imprimirshortcode($atts){
    $shortcode = new PRO_Shortcode();

    // Call the printing method of the class
    return $shortcode->imprimirShortcode($atts);
}

add_shortcode("PRODUCTOS", "imprimirshortcode");