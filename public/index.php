<?php
require_once(__DIR__ . '/../core/db.php');
require_once(__DIR__ . '/../core/dbSession.php');
require_once(__DIR__ . '/../core/session.php');


function render($path, $data = [])
{
    extract($data);
    ob_start();
    include($path);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}

function sessionPage()
{
    $csrf = $_POST['csrf'];
    if (Session::getCSRF() != $csrf) {
        echo '<br />' . 'invalid csrf';
        return;
    }
    Session::regenerate();
    echo render('./../view/app.php', [
        'csrf' => Session::getCSRF()
    ]);
}

DBSession::start();
if (!Session::checkSession()) {
    Session::regenerate();

    header("Location: /");
    exit();
}
echo session_id();
$path = $_SERVER['REQUEST_URI'];
$path =  $path == '/' ? '/' : rtrim($path, '/');
$method = $_SERVER['REQUEST_METHOD'];
DB::getInstance();

switch (true) {
    case $path == '/':
        echo render('./../view/app.php', [
            'csrf' => Session::getCSRF()
        ]);
        break;
    case $path == '/regenerate' && $method == 'POST':
        sessionPage();
        break;
}
