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


    $output = '


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ventana modal</title>
        
    </head>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap");
    
    *{
        box-sizing: border-box;
    }
    
    body{
        background-color: #edeef6;
        font-family: "Montserrat", sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
    
    }
    
    .boton{
        color: white;
        background-color: #80b00b;
        border: none;
        border-radius: 10px;
        padding: 3px;
        font-size: 17px;
    }
    
    .modal-conteiner{
        display: flex;
        background-color: rgba(57, 58, 59, 0.21);
        align-items: center;
        justify-content: center;
        position: fixed;
        pointer-events: none;
        opacity: 0;
        top: 0;
        left: 0;
        height: 100vh;
        width: 100vw;
    }
    
    .show{
        pointer-events: auto;
        opacity: 1;
    }
    
    .modal{
        background-color: #fff;
        width: 600px;
        max-width: 100%;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0, 0);
        text-align: center;
        display: flex;
        flex-direction: column;
    }
    
    .modal-content {
        flex: 1;
        display: flex;
        justify-content: space-between;
    }
    
    .modal-left,
    .modal-right {
        width: 48%; /* El 48% es para dejar espacio para el margen */
        
    }
    
    .close-container {
        margin-top: 20px;
    }
    
    .close {
        color: white;
        background-color: #f44336;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 17px;
    }
    
    </style>
    <body>
        <button class="boton" id="open">Calcular cuotas</button>
    <div class="modal-conteiner" id="modal-conteiner">
        <div class="modal">
            <div class="modal-content">
                <div class="modal-left">
                  <p><strong>Precio:</strong> ' . esc_html($detalles_producto['Precio']) . '</p>
                  <p><strong>Enganche 10%:</strong> ' . esc_html($precio_aumentado) . '</p>
                  <div class="row">
                    <div class="col">
                      <p><strong>Cuotas: </strong></p>
                    </div>
                    <div class="col">
                      <input type="number" name="txtcuotas" id="txtcuotas" class="form-control form-control-sm name_list" style="border: none;" min="0" step="6" max="60">
                    </div>
                  </div>
                </div>
                <div class="modal-right">
                  <br>
                  <p><span id="pago_cuota"></span></p>
                  <br>
                  <p>
                    Si se entregan bien los papeles, se le proporcionará otro trato.
                  </p>
                </div>
            </div>
            <div class="close-container">
                <button class="close" id="close">Cerrar</button>
            </div>
        </div>
    </div>
    
    <script>
        const open = document.getElementById("open");
        const modal_conteiner = document.getElementById("modal-conteiner");
        const close = document.getElementById("close");
    
        open.addEventListener("click",()=>{
            modal_conteiner.classList.add("show");
        });
    
        close.addEventListener("click",()=>{
            modal_conteiner.classList.remove("show");
        });
    </script>
    <script>
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
    </script>
    </body>
    </html>
    



  
  
    ';
   
    return $output;
  }

}

new PRO_Shortcode();
