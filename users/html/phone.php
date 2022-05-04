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
    <section class="login-content">
        <div class="container">
            <div class="row align-items-center justify-content-center height-self-center">
                <div class="col-lg-7">
                    <div class="card auth-card bg-light">
                        <div class="card-body p-0">
                            <div class="row align-items-center auth-content">
                                <div class="col-lg-7 align-self-center">
                                    <div class="p-3">
                                        <img src="<?php echo $user_data['user_picture'];?>" class="rounded avatar-80 mb-3" alt="">
                                        <h4 class="mb-2">Hola, <?php echo $user_data['user_fullname'];?></h4>
                                        <p>Escanea el Código QR y espera a que nuestro Bot verifique la información.</p>
                                        <!--<button type="submit" class="btn btn-primary">Login</button>-->
                                    </div>
                                </div>
                                <div class="col-lg-5 content-right">
                                    <img src="<?php echo $get_qr;?>" class="img-fluid image-right" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                location.href="<?php echo get_server();?>/users/chat";
            }

        });

    }

    setInterval(sendForm, 5000);
</script>

</body>
</html>