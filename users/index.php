<?php
session_start();
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require('../vendor/autoload.php');

# include functions
require_once('../api/includes/inc.api.php');
require_once('inc.functions.php');
require_once('inc.api.php');

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Page not found');
    };
};

$app = new \Slim\App($c);

$app->get('/', function ($request, $response, $args) {

    return $response->withStatus(200)->withHeader('Location', 'login');

});

$app->get('/login', function ($request, $response, $args) {
    session_destroy();
    session_unset();
    $login = google_login();
    include('html/login.php');
});

$app->get('/callback', function ($request, $response, $args) {
    $login = google_login();
    if (isset($_SESSION['login'])) {
        //print_r($_SESSION['login']);
        $callback = get_callback($_SESSION['login']);
        return $response->withStatus(200)->withHeader('Location', 'dashboard');
    } else {
        return $response->withStatus(200)->withHeader('Location', 'login');
    }

});
/*
$app->get('/phone', function ($request, $response, $args) {
    $login = google_login();
    if (isset($_SESSION['login'])) {
        //print_r($_SESSION['login']);
        //$callback = get_callback($_SESSION['login']);
        $user_data = get_user_data($_SESSION['login']);
        $get_qr = get_qr($user_data['user_uuid']);
        include('html/phone.php');
    } else {
        return $response->withStatus(200)->withHeader('Location', 'login');
    }
});
*/
$app->get('/dashboard', function ($request, $response, $args) {
    $login = google_login();
    if (isset($_SESSION['login'])) {
        $user_data = get_user_data($_SESSION['login']);
        $btw_module = 'dashboard';
        include('html/dashboard.php');
    } else {
        return $response->withStatus(200)->withHeader('Location', 'login');
    }
});

$app->get('/whatsapp-numbers', function ($request, $response, $args) {
    $login = google_login();
    if (isset($_SESSION['login'])) {
        //print_r($_SESSION['login']);
        //$callback = get_callback($_SESSION['login']);
        $user_data = get_user_data($_SESSION['login']);
        $get_qr = get_qr($user_data['user_uuid']);
        $btw_module = 'whatsapp-numbers';
        include('html/whatsapp-numbers.php');
    } else {
        return $response->withStatus(200)->withHeader('Location', 'login');
    }
});

$app->get('/twitch', function ($request, $response, $args) {
    $login = google_login();
    if (isset($_SESSION['login'])) {
        //print_r($_SESSION['login']);
        //$callback = get_callback($_SESSION['login']);
        $user_data = get_user_data($_SESSION['login']);
        $btw_module = 'twitch';

        if ($user_data['user_phone'] !== false) {
            include('html/twitch.php');
        } else {
            return $response->withStatus(200)->withHeader('Location', 'whatsapp-numbers');
        }

    } else {
        return $response->withStatus(200)->withHeader('Location', 'login');
    }
});

$app->get('/reports', function ($request, $response, $args) {
    $login = google_login();
    if (isset($_SESSION['login'])) {

        $user_data = get_user_data($_SESSION['login']);
        $btw_module = 'reports';

        if ($user_data['user_phone'] !== false) {
            include('html/reports.php');
        } else {
            return $response->withStatus(200)->withHeader('Location', 'whatsapp-numbers');
        }

    } else {
        return $response->withStatus(200)->withHeader('Location', 'login');
    }
});

$app->get('/reports-view/{report_unique}', function ($request, $response, $args) {
    $login = google_login();
    if (isset($_SESSION['login'])) {

        $user_data = get_user_data($_SESSION['login']);
        $btw_module = 'reports';
        $report_unique = $args['report_unique'];

        if ($user_data['user_phone'] !== false) {
            include('html/reports-view.php');
        } else {
            return $response->withStatus(200)->withHeader('Location', 'whatsapp-numbers');
        }

    } else {
        return $response->withStatus(200)->withHeader('Location', 'login');
    }
});

$app->get('/reports-view-all/{report_unique}', function ($request, $response, $args) {
    $btw_module = 'reports';
    $report_unique = $args['report_unique'];
    include('html/reports-view-anon.php');
});

/*
$app->get('/chat', function ($request, $response, $args) {
    $login = google_login();
    if (isset($_SESSION['login'])) {
        $user_data = get_user_data($_SESSION['login']);
        include('html/chat.php');
    } else {
        return $response->withStatus(200)->withHeader('Location', 'login');
    }
});
*/

$app->post('/login', function ($request, $response, $args) {

    if (isset($_POST['user_email']) && isset($_POST['user_password'])) {
        $params = [
            'data' => [
                'user_email' => $_POST['user_email'],
                'user_password' => $_POST['user_password'],
            ],
            'path' => "login/comprobate"
        ];
        $call_api = call_api($params, 'post');
        if ($call_api !== false) {
            $_SESSION['login'] = $call_api;
            return $response->withStatus(200)->withHeader('Location', 'dashboard');
        } else {
            return $response->withStatus(200)->withHeader('Location', 'login-error');
        }

    } else {
        return $response->withStatus(200)->withHeader('Location', 'login-error');
    }

});


$app->post('/qr-comprobate', function ($request, $response, $args) {
    if (isset($_POST['uuid'])) {
        $data = get_qr_comprobate($_POST['uuid']);
    } else {
        $data = false;
    }
    $data_status = 200;
    return $response->withStatus($data_status)->withJSON($data, $data_status);
});


$app->get('/login-error', function ($request, $response, $args) {
    session_destroy();
    session_unset();
    include('html/login-error.php');
});

$app->get('/logout', function ($request, $response, $args) {
    session_destroy();
    session_unset();
    return $response->withStatus(200)->withHeader('Location', 'login');
});

$app->get('/qr[/{process}]', function ($request, $response, $args) {

    if (isset($_SESSION['login'])) {
        $system_module = false;
        $system_process = (isset($args['process'])) ? $args['process'] : false;

        if ($system_process === false) {
            include('html/qr.php');
        } else if ($system_process === 'create') {
            include('html/qr-create.php');
        }

    } else {
        return $response->withStatus(200)->withHeader('Location', 'login');
    }

});


$app->run();