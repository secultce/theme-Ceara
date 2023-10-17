<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<?php 

echo "<br/>";

$str = random_bytes(32);
$str = base64_encode($str);
$str = str_replace(["+", "/", "="], "", $str);
$str = substr($str, 0, 32);

echo $str;

$key_filed = "field_".rand(100, 999);

?>

<script>
    var dataPostSendAudit = {
        "user_id": <?php echo rand(300, 140000); ?>,
        // "user_id": 30490,
        "registration": <?php echo rand(2000, 499999); ?>,
        "object_type": "Registratrion",
        "action": "Criou",
        "message": "Criou um registro",
        "key": "<?php echo $key_filed; ?>",
        "value": "<?php echo $str; ?>"
    }

    $.ajax({
        url:'http://localhost:5000/audit',
        type: "POST",
        data: JSON.stringify(dataPostSendAudit),
        dataType: "json",
        contentType: "application/json; charset=utf-8",
        success: function(res){
            console.log({res})
        },
        error: function(err){
            console.log({err})
        } 
    })
    .done(function() {
        console.log( "second success" );
    })
    .fail(function() {
    console.log( "error" );
    })
    .always(function() {
    console.log( "finished" );
    });


</script>