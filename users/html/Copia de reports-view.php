<?php
$get_btw_reports_view = get_btw_reports_view($report_uuid, $report_uin);
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
                                <li class="breadcrumb-item active" aria-current="page">Library</li>
                            </ol>
                        </nav>
                        <div class="col-lg-12 text-center align-self-center"><br>
                            <img src="assets/images/logo_btw.png" class="img-fluid rounded" alt="logo" style="width:60%">
                        </div><br>
                        <h3 class="mb-0">Hola, <?php echo $user_data['user_fullname'];?></h3>
                        <p class="mb-0 mb-4">
                            A continuación encontrarás los reportes generados por <code>Billy, The Bully Watcher</code>
                        </p>

                        <?php
                        if ($get_btw_reports_view !== false) {
                        ?>
                            <table id="user-list-table" class="table table-striped dataTable mt-4" role="grid" aria-describedby="user-list-page-info">
                                <thead>
                                <tr class="ligth">
                                    <th>Usuario</th>
                                    <th class="text-left">Mensaje</th>
                                    <th>Fecha Mensaje</th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php
                            foreach ($get_btw_reports_view as $get_btw_report_msg) {
                            ?>
                                <tr>
                                    <td>@<?php echo $get_btw_report_msg['profile_id'];?></td>
                                    <td class="text-left"><?php echo $get_btw_report_msg['chat_msg'];?></td>
                                    <td><?php echo $get_btw_report_msg['report_created_at'];?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                            </table>
                            <br>
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
    </div>

    <!-- Backend Bundle JavaScript -->
    <script src="assets/js/backend-bundle.min.js"></script>


    <!-- app JavaScript -->
    <script src="assets/js/app.js"></script>
    <script>
        function addAccount() {

            $("#error_msg").hide();
            $("#error_msg2").hide();
            $("#button_send").hide();
            $("#button_process").show();

            var formData = {
                user_id: "<?php echo $user_data['auto_id']?>",
                username: $("#account_username").val()
            };

            if (formData.account_username !== '') {

                $.ajax({
                    type: "POST",
                    url: "<?php echo get_server();?>/api/twitch/twitch-username",
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {

                    if (data === true) {
                        location.href="<?php echo get_server();?>/users/twitch";
                    } else {
                        $("#button_process").hide();
                        $("#button_send").show();
                        $("#error_msg2").show();
                    }

                });

            } else {
                $("#button_process").hide();
                $("#button_send").show();
                $("#error_msg").show();
            }


        }

        function deleteAccount(account_uin) {

            $("#button_delete_"+account_uin).hide();
            $("#button_process_"+account_uin).show();

            var formData = {
                account_uin: account_uin
            };

            if (formData.account_uin !== '') {

                $.ajax({
                    type: "POST",
                    url: "<?php echo get_server();?>/api/twitch/twitch-username-delete",
                    data: formData,
                    dataType: "json",
                    encode: true,
                }).done(function (data) {
                    location.href="<?php echo get_server();?>/users/twitch";
                });

            } else {
                $("#button_process_"+account_uin).hide();
                $("#button_delete_"+account_uin).show();
            }

        }
    </script>

</body>
</html>