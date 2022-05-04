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
        <div class="container-fluid h-100">
            <div class="row align-self-center h-100">
                <div class="col-sm-12 text-center align-self-center">
                    <h2 class="mb-4">Hola, <?php echo $user_data['user_fullname'];?></h2>
                    <img src="assets/images/logo_btw.png" class="img-fluid" alt="logo">
                    <h4 class="mt-4 mb-3">Billy, The Bully Watcher, cuida de los tuyos!</h4>
                    <p class="mb-3">¿Qué deseas hacer?</p>
                    <div class="flex align-items-center list-user-action">
                        <a class="btn btn-lg bg-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Configurar WhatsApp" href="<?php echo get_server()."/users/whatsapp-numbers";?>">
                            <i class="lab la-whatsapp"></i>
                        </a>
                        <a class="btn btn-lg bg-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Configurar Twitch" href="<?php echo get_server()."/users/twitch";?>">
                            <i class="lab la-twitch"></i>
                        </a>
                        <a class="btn btn-lg bg-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Reportes" href="<?php echo get_server()."/users/reports";?>">
                            <i class="las la-bell"></i>
                        </a>
                        <a class="btn btn-lg bg-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cerrar Sesión" href="<?php echo get_server()."/users/logout";?>">
                            <i class="las la-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- Backend Bundle JavaScript -->
<script src="assets/js/backend-bundle.min.js"></script>


<!-- app JavaScript -->
<script src="assets/js/app.js"></script>

</body>
</html>