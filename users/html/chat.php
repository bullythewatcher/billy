<?php
$get_btw_accounts = get_btw_accounts($user_data['user_email']);
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
<body class=" ">
<!-- loader Start -->
<div id="loading">
    <div id="loading-center">
    </div>
</div>
<!-- loader END -->

<div class="wrapper">
    <section class="login-content">
        <div class="container">
            <div class="row align-items-center justify-content-center height-self-center">
                <div class="col-lg-7">
                    <div class="card auth-card bg-light">
                        <div class="card-body p-0">
                            <div class="row align-items-center auth-content">
                                <div class="col-lg-12 align-self-center">
                                    <div class="p-3">
                                        <img src="<?php echo $user_data['user_picture'];?>" class="rounded avatar-80 mb-3" alt="">
                                        <h4 class="mb-2">Hola, <?php echo $user_data['user_fullname'];?></h4>
                                        <p>Escribe la cuentas que deseas monitorear y haz click en el botón de Ingresar</p>

                                        <div class="alert alert-danger" role="alert" id="error_msg" style="display:none">
                                            <div class="iq-alert-text"><b>ERROR:</b> Debes escribir el nombre de usuario de Twitch</div>
                                        </div>

                                        <!--<form>-->
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="input-group mb-4">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="basic-addon2">https://www.twitch.tv/</span>
                                                        </div>
                                                        <input type="text" class="form-control" placeholder="Usuario Twitch" aria-label="Usuario Twitch" aria-describedby="basic-addon2" id="account_username" name="account_username">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="custom-control custom-checkbox mb-3">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="true" disabled="true">
                                                        <label class="custom-control-label control-label-1" for="customCheck1">Acepto que Billy, The Bully Watcher notifique a mi número telefónico cuando reconozca actividad inadecuada en este computador al detectar sesiones de streaming</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <a class="btn btn-success" href="javascript:void(0)" onclick="addAccount();" id="button_send" name="button_send"><i class="las la-user-astronaut"></i> Ingresar Cuenta</a>
                                        <!--</form>-->

                                    </div>

                                </div>
                                <?php
                                if ($get_btw_accounts !== false) {?>
                                    <div class="col-lg-12">
                                        <div class="list animate__animated animate__fadeIn">
                                            <div class="card bg-light border-none">
                                                <div class="card-body">
                                                    <h4 class="mb-3">Cuentas Monitoreadas</h4>
                                                    <?php
                                                        foreach ($get_btw_accounts as $get_btw_account) {
                                                    ?>
                                                            <div class="card">
                                                                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                                                                    <div class="">
                                                                        <h5 class="mb-0"><?php echo $get_btw_account['account_username'];?></h5>
                                                                    </div>
                                                                    <div class="d-flex mt-2 mt-md-0">
                                                                        <a class="btn btn-outline-danger active mr-3 " href="javascript:void(0)" onclick="deleteAccount('<?php echo $get_btw_account['account_uin'];?>')" id="button_send_<?php echo $get_btw_account['account_uin'];?>" name="button_send_<?php echo $get_btw_account['account_uin'];?>"><i class="las la-user-times"></i> Eliminar</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

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
    function addAccount() {

        $("#button_send").hide();

        var formData = {
            user_email: "<?php echo $user_data['user_email']?>",
            account_username: $("#account_username").val()
        };

        if (formData.account_username !== '') {

            $.ajax({
                type: "POST",
                url: "<?php echo get_server();?>/api/accounts/add",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {

                if (data === true) {
                    location.href="<?php echo get_server();?>/users/chat";
                } else {
                    $("#button_send").show();
                    $("#error_msg").show();
                }

            });

        } else {
            $("#button_send").show();
            $("#error_msg").show();
        }


    }

    function deleteAccount(uin) {

        $("#button_send_"+uin).hide();

        var formData = {
            account_uin: uin
        };

        if (formData.account_username !== '') {

            $.ajax({
                type: "POST",
                url: "<?php echo get_server();?>/api/accounts/delete",
                data: formData,
                dataType: "json",
                encode: true,
            }).done(function (data) {

                location.href="<?php echo get_server();?>/users/chat";

            });

        }

    }
</script>

</body>
</html>