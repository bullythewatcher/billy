<?php
$get_qr_comprobate = get_qr_comprobate($user_data['user_uuid']);
$get_btw_accounts = get_btw_accounts($user_data['auto_id']);
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
                            Escribe la cuentas que deseas monitorear y haz click en el botón <code>Ingresar Cuenta</code>
                        </p>

                        <div class="alert alert-danger" role="alert" id="error_msg" style="display:none">
                            <div class="iq-alert-text"><b>ERROR:</b> Debes escribir el nombre de usuario de Twitch</div>
                        </div>

                        <div class="alert alert-danger" role="alert" id="error_msg2" style="display:none">
                            <div class="iq-alert-text"><b>ERROR:</b> El usuario de Twitch que escribiste NO existe :(</div>
                        </div>

                        <!-- form -->
                        <div class="input-group mb-4">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">https://www.twitch.tv/</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Usuario Twitch" aria-label="Usuario Twitch" aria-describedby="basic-addon2" id="account_username" name="account_username">
                        </div>

                        <div class="custom-control custom-checkbox mb-3 text-left">
                            <input type="checkbox" class="custom-control-input" id="customCheck1" checked="true" disabled="true">
                            <label class="custom-control-label control-label-1" for="customCheck1">Acepto que Billy, The Bully Watcher notifique a mi número telefónico cuando <br>reconozca actividad inadecuada en este computador al detectar sesiones de streaming</label>
                        </div>

                        <a class="btn btn-success" href="javascript:void(0)" onclick="addAccount();" id="button_send" name="button_send"><i class="las la-user-astronaut"></i> Ingresar Cuenta</a>
                        <a class="btn btn-warning" href="javascript:void(0)" id="button_process" name="button_process" style="display:none"><i class="las la-user-astronaut"></i> Ingresando Cuenta</a>
                        <!-- form -->

                        <?php
                        if ($get_btw_accounts !== false) {
                        ?>
                            <table id="user-list-table" class="table table-striped dataTable mt-4" role="grid" aria-describedby="user-list-page-info">
                                <thead>
                                <tr class="ligth">
                                    <th></th>
                                    <th>Usuario</th>
                                    <th>Fecha Creación</th>
                                    <th style="min-width: 40px"></th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php
                            foreach ($get_btw_accounts as $get_btw_account) {
                            ?>
                                <tr>
                                    <td class="text-center"><img class="rounded img-fluid avatar-50" src="assets/images/profiles/<?php echo $get_btw_account['profile_profile_image_url'];?>" alt="profile"></td>
                                    <td>@<?php echo $get_btw_account['profile_login'];?></td>
                                    <td><?php echo $get_btw_account['profile_created_at'];?></td>
                                    <td>
                                        <div class="flex align-items-center list-user-action">
                                            <a class="btn btn-sm bg-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar Usuario" href="javascript:void(0)" onclick="deleteAccount('<?php echo $get_btw_account['account_uin'];?>');" id="button_delete_<?php echo $get_btw_account['account_uin'];?>" name="button_delete_<?php echo $get_btw_account['account_uin'];?>"><i class="ri-delete-bin-line mr-0"></i></a>
                                            <a class="btn btn-sm bg-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminando Usuario" href="javascript:void(0);" id="button_process_<?php echo $get_btw_account['account_uin'];?>" name="button_process_<?php echo $get_btw_account['account_uin'];?>" style="display:none"><i class="ri-delete-bin-line mr-0"></i></a>
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