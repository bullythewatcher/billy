<?php
$get_btw_reports = get_btw_reports($user_data['auto_id']);
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

                        <div class="col-lg-12 text-center align-self-center"><br>
                            <img src="assets/images/logo_btw.png" class="img-fluid rounded" alt="logo" style="width:60%">
                        </div><br>
                        <h3 class="mb-0">Hola, <?php echo $user_data['user_fullname'];?></h3>
                        <p class="mb-0 mb-4">
                            A continuación encontrarás los reportes generados por <code>Billy, The Bully Watcher</code>
                        </p>

                        <?php
                        if ($get_btw_reports !== false) {
                        ?>
                            <table id="user-list-table" class="table table-striped dataTable mt-4" role="grid" aria-describedby="user-list-page-info">
                                <thead>
                                <tr class="ligth">
                                    <th></th>
                                    <th>Canal</th>
                                    <th>Fecha Reporte</th>
                                    <th style="min-width: 60px"></th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php
                            foreach ($get_btw_reports as $get_btw_report) {
                            ?>
                                <tr>
                                    <td class="text-center"><img class="rounded img-fluid avatar-50" src="assets/images/profiles/<?php echo $get_btw_report['profile_profile_image_url'];?>" alt="profile"></td>
                                    <td>@<?php echo $get_btw_report['profile_login'];?></td>
                                    <td><?php echo $get_btw_report['report_created_at'];?></td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            <a class="btn btn-sm bg-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Reporte" href="<?php echo get_server()."/users/reports-view/{$get_btw_report['report_unique']}";?>"><i class="las la-file-contract mr-0 font-size-20"></i></a>
                                            <a class="btn btn-sm bg-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enviar por WhatsApp" href="javascript:void(0)"><i class="lab la-whatsapp mr-0 font-size-20"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                            </table>
                            <br>
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