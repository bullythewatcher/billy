<?php
$get_qr_comprobate = get_qr_comprobate($user_data['user_uuid']);
?>
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

                <div class="card">

                    <div class="card-body">

                        <?php
                        if ($get_qr_comprobate === false) {
                            ?>
                            <h2 class="mb-0">Hola, <?php echo $user_data['user_fullname'];?></h2>
                            <img src="<?php echo $get_qr;?>" class="img-fluid w-60" alt="new-chat">
                            <h3 class="mb-1 pt-4">Escanea el código QR con tu teléfono móvil</h3>
                            <p class="mb-0 mb-4">
                                Cuando escanees el código QR, se enviará un mensaje a tu WhatsApp<br> y automáticamente, se configurará tu cuenta.
                            </p>
                            <?php
                        } else {
                            ?>
                            <div class="col-lg-12 text-center align-self-center"><br>
                                <img src="assets/images/logo_btw.png" class="img-fluid rounded" alt="logo" style="width:60%">
                            </div><br>
                            <h3 class="mb-0 pt-0">Hola, <?php echo $user_data['user_fullname'];?></h3>
                            <p class="mb-0 mb-4">
                                El número al cual se enviarán las notificaciones de los reportes es el siguiente:
                            </p>

                            <div class="card">
                                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="">
                                        <h5 class="mb-0">+<?php echo $user_data['user_phone'];?></h5>
                                    </div>
                                    <div class="d-flex mt-2 mt-md-0">
                                        <a class="btn btn-outline-danger active mr-3 " href="javascript:void(0)" onclick="deleteNumber('<?php echo $user_data['user_uuid'];?>')" id="button_delete" name="button_delete"><i class="las la-user-times"></i> Eliminar</a>
                                        <a class="btn btn-outline-danger active mr-3 " href="javascript:void(0)" id="button_process" name="button_process" style="display:none"><i class="las la-user-times"></i> Eliminando Número</a>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-success" role="alert">
                                <div class="iq-alert-icon">
                                    <i class="las la-check-double"></i>
                                </div>
                                <div class="iq-alert-text">En hora buena, ya hemos configurado tu número telefónico.</div>
                            </div>
                            <p class="mb-3">¿Qué deseas hacer?</p>
                            <div class="flex align-items-center list-user-action">
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

                            <?php
                        }
                        ?>

                    </div>
                </div>


            </div>
        </div>
</div>

<!-- Backend Bundle JavaScript -->
<script src="assets/js/backend-bundle.min.js"></script>


<!-- app JavaScript -->
<script src="assets/js/app.js"></script>

    <?php
    if ($get_qr_comprobate === false) {
    ?>
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
    <?php
    } else {
    ?>
        <script>
            function deleteNumber(uuid) {

                $("#button_delete").hide();
                $("#button_process").show();

                var formData = {
                    user_uuid: uuid
                };

                if (formData.account_username !== '') {

                    $.ajax({
                        type: "POST",
                        url: "<?php echo get_server();?>/api/whatsapp-numbers/delete",
                        data: formData,
                        dataType: "json",
                        encode: true,
                    }).done(function (data) {

                        location.href="<?php echo get_server();?>/users/whatsapp-numbers";

                    });

                }

            }
        </script>
    <?php
    }
    ?>

</body>
</html>