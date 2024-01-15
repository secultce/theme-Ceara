<?php
?>
<div style="width: 100%; display: block">
    <hr>
    <div style="width: 100%; display: block">
        <h3 for="">Site Editais</h3>
        <button type="button" class="btn btn-primary"
                title="Publicar somente no site de editais"
                id="btn-publish-site" onclick="confirmPublishSite()">
            Publicar no site
        </button>
        <hr>
        <br>
    </div>

</div>

<script>
    //Remover esse codigo script para arquivo .js
    function confirmPublishSite() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success mr-5",
                cancelButton: "btn btn-warning"
            },
            buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "Publicar no site?",
            text: "Ao confirmar, você publicará esse edital como encerrado no site de Editais, confirma essa ação?",
            showCancelButton: true,
            confirmButtonText: "Sim, publicar",
            cancelButtonText: "Não, desistir"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "POST",
                    url: MapasCulturais.createUrl('opportunity','publish_site',  [MapasCulturais.entity.id]),
                    data: { publish_site: 'Sim'},
                    success: function (data, status ){
                        swalWithBootstrapButtons.fire({
                            title: 'Sucesso!',
                            text: data.message
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                })
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
              console.log('')
            }
        });
    }
</script>