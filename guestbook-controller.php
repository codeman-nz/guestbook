<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/jasongrimes/paginator/src/JasonGrimes/Paginator.php';
require_once __DIR__ . '/vendor/sinergi/browser-detector/src/Browser.php';
require_once __DIR__ . '/vendor/sinergi/browser-detector/src/DetectorInterface.php';
require_once __DIR__ . '/vendor/sinergi/browser-detector/src/BrowserDetector.php';
require_once __DIR__ . '/vendor/sinergi/browser-detector/src/UserAgent.php';
require_once __DIR__ . '/vendor/sinergi/browser-detector/src/OsDetector.php';
require_once __DIR__ . '/vendor/sinergi/browser-detector/src/Os.php';

$app                 = new Silex\Application();
$app['debug']        = true;
$app['itemsPerPage'] = 10;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'guestbook',
        'user' => 'guestbook',
        'password' => 'guestbook',
        'charset' => 'utf8mb4'
    )
));

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;
use JasonGrimes\Paginator;

$app->post('/create-entry', function(Request $request) use ($app) {
    $secret          = "6LdDnzAUAAAAAKJaI7mj3r04lBN6aeD3dQ5IRCtS";
    $captcha         = $_POST["captcha"];
    $verify          = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$captcha}");
    $captcha_success = json_decode($verify);
    
    if ($captcha_success->success == true) {
        $browser         = new Browser();
        $browser_name    = $browser->getName();
        $browser_version = $browser->getVersion();
        
        $os      = new Os();
        $os_name = $os->getName();
        
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        $key_value_data = array(
            'Name' => $request->get('name'),
            'Address' => $request->get('address'),
            'EmailAddress' => $request->get('emailAddress'),
            'Message' => $request->get('message'),
            'Browser' => $browser_name,
            'BrowserVersion' => $browser_version,
            'OS' => $os_name,
            'IpAddress' => $ip_address
        );
        
        $app['db']->insert('entries', $key_value_data);
    }
    
    return new Response('', Response::HTTP_CREATED);
});

$app->get('/get-entries', function(Request $request) use ($app) {
    $response = new Response();
    
    $currentPage = $request->get('currentPage');
    $offset      = ($currentPage - 1) * 10;
    
    $allEntries = $app['db']->fetchAll("SELECT Name, Address, EmailAddress, Message FROM entries LIMIT {$offset}, 10");
    
    $response->setContent(json_encode($allEntries));
    
    return $response;
});

$app->get('/get-paginator', function(Request $request) use ($app) {
    $row = $app['db']->fetchAssoc("SELECT COUNT(*) AS TotalItems FROM entries");
    
    $totalItems   = $row['TotalItems'];
    $itemsPerPage = $app['itemsPerPage'];
    $currentPage  = $request->get('currentPage');
    $urlPattern   = '?currentPage=(:num)';
    $paginator    = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    
    $response = new Response($paginator);
    
    return $response;
});

$app->run();
?>