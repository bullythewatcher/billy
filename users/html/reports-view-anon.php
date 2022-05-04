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

    <meta property="og:title" content="Billy, The Bully Watcher" />
    <meta property="og:description" content="Reporte del usuario @<?php echo $get_btw_reports_view_data['profile_login'];?>, generado el <?php echo $get_btw_reports_view_data['report_created_at'];?>"/>
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo get_server();?>/users/reports-view-all/<?php echo $report_unique;?>" />
    <meta property="og:image" content="<?php echo get_server();?>/assets/images/logo_btw.png" />

</head>
<body class="color-light">
<!-- loader Start -->
<div id="loading">
    <div id="loading-center">
    </div>
</div>
<!-- loader END -->
<div class="wrapper">
    <div class="container">
        <div class="card-body">

            <?php
            if ($get_btw_reports_view !== false) {
            ?>
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
            <?php
            } else {
            ?>
                <div class="alert alert-danger" role="alert">
                    <div class="iq-alert-icon">
                        <i class="ri-information-line"></i>
                    </div>
                    <div class="iq-alert-text"><b>ERROR:</b> El reporte al que estás intentando ingresar no existe</div>
                </div>
            <?php
            }
            ?>

        </div>
    </div>

    <!-- Backend Bundle JavaScript -->
    <script src="assets/js/backend-bundle.min.js"></script>


    <!-- app JavaScript -->
    <script src="assets/js/app.js"></script>

</body>
</html>