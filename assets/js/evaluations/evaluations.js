$(document).ready(function () {
    // PNotify.prototype.options.styling = "bootstrap3";
    $("#btn-submit-evaluations").click(function (e) { 
        e.preventDefault();
        var formEvaluation = $("#form-force-evaluations").serialize();

        $.ajax({
            type: "POST",
            url: '../avaliacoes/force',
            data: formEvaluation,
            dataType: "JSON",
            success: function (res) {
                console.log(res)
            },
            error: function (err) {
                console.log(err)
                new PNotify({
                    title: 'Ops!',
                    text: err.responseJSON.data.message,
                    icon: 'error',
                    type: 'error',
                });
            }
        });


    });
});