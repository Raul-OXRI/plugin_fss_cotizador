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

    $precio_aumentado = $detalles_producto['Precio'] * 0.10;

    // Build HTML for product information
    $output = '<div class="producto">';
    $output .= '<h3>' . esc_html($producto['Nombre']) . '</h3>';
    $output .= '<p><strong>Precio:</strong> ' . esc_html($detalles_producto['Precio']) . '</p>';
    $output .= '<p><strong>Enganche 10%:</strong> ' . esc_html($precio_aumentado) . '</p>';
    $output .= '<p><strong>Cuotas: </strong><input type="number" name="txtcuotas" id="txtcuotas" class="form-control name_list" min="0" step="6" max="60"></p>';
    $output .= '<p><strong>Pago por cuota:</strong> <span id="pago_cuota"></span></p>';
    $output .= '</div>';
    $output .= '<script>
                  document.getElementById("txtcuotas").addEventListener("input", function() {
                    var precio = ' . $detalles_producto['Precio'] . ';
                    var precio_aumentado = ' . $precio_aumentado . ';
                    var cuotas = parseInt(this.value);
                    if (cuotas > 0) {
                      var pago = (precio - precio_aumentado) / cuotas;
                      document.getElementById("pago_cuota").textContent = pago.toFixed(2);
                    } else {
                      document.getElementById("pago_cuota").textContent = "";
                    }
                  });
                </script>';
    return $output;
  }

}

new PRO_Shortcode();
