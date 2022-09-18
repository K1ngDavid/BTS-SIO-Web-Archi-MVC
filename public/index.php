<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Quizz\Core\Controller\FastRouteCore;

// Gestion des fichiers environnement
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Couche Controller
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', 'Quizz\Controller\HomeController');
    $route->addRoute('GET', '/lister', 'Quizz\Controller\Questionnaire\ListController');
    $route->addRoute('GET', '/detail/{id:\d+}', 'Quizz\Controller\Questionnaire\ViewController');
    $route->addRoute(['GET','POST'],'/etudiants/add', 'Quizz\Controller\User\AddUserController');
    $route->addRoute(['GET','POST'],'/etudiants/{id:\d+}', 'Quizz\Controller\User\UpdateUserController');
    $route->addRoute('POST','/etudiants/{id:\d+}/del', 'Quizz\Controller\User\DeleteUserController');
    $route->addRoute(['GET','POST'],'/etudiants', 'Quizz\Controller\User\ViewUsersController');


});
// Dispatcher -> Couche view
echo FastRouteCore::getDispatcher($dispatcher);

