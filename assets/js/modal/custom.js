$(document).ready(function() {
    let inst = $('[data-remodal-id=modal]').remodal();
    $.ajax({
        type: "GET",
        url: MapasCulturais.createUrl('agent/verify-email'),
        dataType: "json",
        success: function (res) {
            if(res.result == false){
                inst.open();
            }
        }
    });

    $("#remodal-btn-confirm").click(function (e) { 
        e.preventDefault();
        inst.close();
    });
});