jQuery(document).ready(function ($) {
    $('#btnnuevo').click(function () {
        $("#modalnuevo").modal("show");
    });

    $(document).on('click',"a[data-id]",function(){
        var id = this.dataset.id;
        var url = SolicitudesAjax.url;
        $.ajax({
            type: "POST",
            url: url,
            data:{
                action : "peticioneliminar",
                nonce : SolicitudesAjax.seguridad,
                id: id,
            },
            success:function(){
                alert("Datos borrados");
                location.reload();
            }
        });
});
})
