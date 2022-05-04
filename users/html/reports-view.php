<?php
$get_btw_reports_view = get_btw_reports_view($report_unique);
$get_btw_reports_view_data = get_btw_reports_view_data($report_unique);
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
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo get_server()."/users/dashboard";?>"><i class="ri-home-4-line mr-1 float-left"></i>Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo get_server()."/users/reports";?>">Reportes</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Ver reporte</li>
                            </ol>
                        </nav>

                        <div class="card-body text-center">
                            <div class="media-body profile-detail text-center">
                                <img src="assets/images/logo_btw.png" alt="profile-bg" class="profile-bg img-fluid mb-4">
                                <img src="assets/images/profiles/<?php echo $get_btw_reports_view_data['profile_profile_image_url'];?>" alt="profile-img" class="rounded profile-img img-fluid avatar-70">
                            </div>
                            <div class="p-3">
                                <h5 class="mb-1">@<?php echo $get_btw_reports_view_data['profile_login'];?></h5>
                                <small><code>Reporte generado el <?php echo $get_btw_reports_view_data['report_created_at'];?></code></small>
                            </div>
                        </div>

                        <?php
                        if ($get_btw_reports_view !== false) {
                        ?>
                            <?php
                            foreach ($get_btw_reports_view as $get_btw_report_msg) {
                            ?>
                                <div class="card">
                                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                                        <div class="text-left">
                                            <h5 class="mb-1">@<?php echo $get_btw_report_msg['chat_username'];?></h5>
                                            <p class="mb-0 text-danger"><?php echo $get_btw_report_msg['chat_msg'];?></p>
                                            <p class="mb-0 small"><code>- Enviado el <?php echo $get_btw_report_msg['chat_date'];?></code></p>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        <?php
                        } else {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <div class="iq-alert-icon">
                                    <i class="las la-eye-slash"></i>
                                </div>
                                <div class="iq-alert-text">Los usuarios que has agregado NO han generado contenido para mostrarte.<br> No te preocupes, <code>Billy, The Bully Watcher</code>  te avisará cuando tengamos un reporte para ti ;)</div>
                            </div>
                        <?php
                        }
                        ?>

                        <p class="mb-3">¿Qué deseas hacer?</p>
                        <div class="flex align-items-center list-user-action">
                            <a class="btn btn-lg bg-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Configurar WhatsApp" href="<?php echo get_server()."/users/whatsapp-numbers";?>">
                                <i class="lab la-whatsapp"></i>
                            </a>
                            <a class="btn btn-lg bg-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Configurar Twitch" href="<?php echo get_server()."/users/twitch";?>">
                                <i class="lab la-twitch"></i>
                            </a>
                            <a class="btn btn-lg bg-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cerrar Sesión" href="<?php echo get_server()."/users/logout";?>">
                                <i class="las la-sign-out-alt"></i>
                            </a>
                        </div>

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