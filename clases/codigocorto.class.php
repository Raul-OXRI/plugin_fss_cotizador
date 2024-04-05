<?php
class PRO_Shortcode {

  public function __construct() {
    add_shortcode('PRO', array($this, 'imprimirShortcode'));
  }

  public function imprimirShortcode($atts) {
    // Get the product ID from the shortcode
    $id = $atts['id'];

    // Get product information with specified ID
    global $wpdb;
    $tabla_productos = "{$wpdb->prefix}productos";
    $tabla_detalles = "{$wpdb->prefix}productos_detalle";

    $query = $wpdb->prepare("SELECT * FROM $tabla_productos WHERE ProductoId = %d", $id);
    $producto = $wpdb->get_row($query, ARRAY_A);

    // Error message if product not found
    if (!$producto) {
      return '<p>Error: No se encontró el producto con el ID especificado.</p>';
    }

    // Get product details
    $query_detalles = $wpdb->prepare("SELECT * FROM $tabla_detalles WHERE ProductoId = %d", $id);
    $detalles_producto = $wpdb->get_row($query_detalles, ARRAY_A);

    // Build HTML for product information
    $output = '<div class="producto">';
    $output .= '<h3>' . esc_html($producto['Nombre']) . '</h3>';
    $output .= '<p><strong>Precio:</strong> ' . esc_html($detalles_producto['Precio']) . '</p>';
    // Add more details as needed
    //$output .= '<p><strong>Descripción:</strong> ' . esc_html($detalles_producto['Descripcion']) . '</p>';
    //$output .= '<img src="' . esc_url($detalles_producto['Imagen']) . '" alt="' . esc_html($producto['Nombre']) . '">';

    $output .= '</div>';

    return $output;
  }

}

new PRO_Shortcode();
