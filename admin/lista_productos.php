<?php
    global $wpdb;
    $tabla = "{$wpdb->prefix}productos";
    $tabla2 = "{$wpdb->prefix}productos_detalle";
    if (isset($_POST['btnguardar'])) {
        $nombre = $_POST['txtnombre'];
        $query = "SELECT ProductoId FROM $tabla ORDER BY ProductoId DESC limit 1";
        $resultado = $wpdb->get_results($query,ARRAY_A);
        $proximoId = $resultado[0]['ProductoId'] + 1;
        $shortcode = "[PRO id='$proximoId']";

        $datos = [
            'ProductoId' => null,
            'Nombre' => $nombre,
            'ShortCode' => $shortcode
        ];
        
        $respuesta = $wpdb->insert($tabla,$datos);

        if ($respuesta) {
            $precio = $_POST['txtprecio'];

            $datos2 = [
                'DetalleId' => null,
                'ProductoId' => $proximoId,
                'Precio' => $precio,
            ];
            $wpdb -> insert($tabla2, $datos2);

        }

    }



    $query = "SELECT * FROM $tabla";
    $lista_producto = $wpdb->get_results($query,ARRAY_A);
    if (empty($lista_producto)) {
        $lista_producto = array();
    }
?>

<div class="wrap">
    <?php
        echo "<h1 class='wp-heading-inline'>" . get_admin_page_title() . "</h1>";
    ?>
    <a id="btnnuevo" class="page-title-action">Añadir nuevo</a>
    <br><br><br>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <th>Nombre del producto</th>
            <th>ShortCode</th>
            <th>Acciones</th>
        </thead>
        <tbody id="the-list">
            <?php
                foreach ($lista_producto as $key => $value) {
                    $id = $value['ProductoId'];
                    $nombre = $value['Nombre'];
                    $shortcode = $value['ShortCode'];

                    echo "
                        <tr>
                            <td>$nombre</td>
                            <td>$shortcode</td>
                            <td>
                                <a data-id='$id' class='page-title-action'>Borrar</a>
                            </td>
                        </tr>
                    ";
                }
            ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div class="modal fade" id="modalnuevo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo producto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
        <div class="modal-body">
            <div class="form-group">
                <label for="txtnombre" class="col-sm-5 col-form-label">Nombre del producto</label>
                <div class="col-sm-8">
                    <input type="text" id="txtnombre" name="txtnombre" style="width:100%">
                </div>
            </div>
            <br><h4> Producto </h4><hr>
            <table>
                <tr>  
                    <td>
                        <label for="txtprecio" class="col-form-label" style="margin-right:5px">Precio</label>
                    </td>
                    <td>
                        <input type="number" name="txtprecio" id="txtprecio" class="form-control name_list" min="0" step="1000">
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" name="btnguardar" id="btnguardar">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
