<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Billy, The Bully Watcher</title>
    <base href="<?php echo get_server();?>/">
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
    <link rel="stylesheet" href="assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="assets/css/backend.css?v=1.1.0">
    <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" href="assets/vendor/remixicon/fonts/remixicon.css">
    <link rel="stylesheet" href="assets/vendor/material-icons/css/material-icons.css">
</head>
<body class="color-light">
<!-- loader Start -->
<div id="loading">
    <div id="loading-center">
    </div>
</div>
<!-- loader END -->
<div class="wrapper">
    <?php include("html/inc.navbar.php");?>
    <div class="content-page">
        <div class="chat-tab-box default-box d-flex align-items-center">
            <div class="text-center mx-auto d-block pb-5">
                <h2 class="mb-0">Hola, <?php echo $user_data['user_fullname'];?></h2>
                <img src="<?php echo $get_qr;?>" class="img-fluid w-60" alt="new-chat">
                <h3 class="mb-1 pt-4">Escanea el código QR con tu teléfono móvil</h3>
                <p class="mb-0 mb-4">
                    Cuando escanees el código QR, se enviará un mensaje a tu WhatsApp
                </p>
                <button type="button" class="btn btn-primary default-chat-btn">
                    Contact List
                </button>
            </div>
        </div>
</div>

<!-- Backend Bundle JavaScript -->
<script src="assets/js/backend-bundle.min.js"></script>


<!-- app JavaScript -->
<script src="assets/js/app.js"></script>

<script>
    function sendForm() {

        var formData = {
            uuid: "<?php echo $user_data['user_uuid']?>"
        };

        $.ajax({
            type: "POST",
            url: "<?php echo get_server();?>/users/qr-comprobate",
            data: formData,
            dataType: "json",
            encode: true,
        }).done(function (data) {

            if (data === true) {
                location.href="<?php echo get_server();?>/users/whatsapp-numbers";
            }

        });

    }

    setInterval(sendForm, 5000);
</script>

</body>
</html>