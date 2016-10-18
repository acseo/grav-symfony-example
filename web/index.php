<?php
/**
 * @package    Grav.Core
 *
 * @copyright  Copyright (C) 2014 - 2016 RocketTheme, LLC. All rights reserved.
 * @license    MIT License; see LICENSE file for details.
 */

//namespace Grav;

// Ensure vendor libraries exist
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!is_file($autoload)) {
    die("Please run: <i>bin/grav install</i>");
}

if (!defined('GRAV_ROOT')) {
    define('GRAV_ROOT', str_replace(DIRECTORY_SEPARATOR, "/", getcwd(). DIRECTORY_SEPARATOR . "..". DIRECTORY_SEPARATOR. "grav"));
}

// USE Grav namespaces
use Grav\Common\Grav;
use RocketTheme\Toolbox\Event\Event;
//use symfony namespaces
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

// Register the auto-loader.
$loader = require_once $autoload;
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

// Initialize Symfony AppKernel
$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);

if (version_compare($ver = PHP_VERSION, $req = GRAV_PHP_MIN, '<')) {
    die(sprintf('You are running PHP %s, but Grav needs at least <strong>PHP %s</strong> to run.', $ver, $req));
}

// Set timezone to default, falls back to system if php.ini not set
date_default_timezone_set(@date_default_timezone_get());

// Set internal encoding if mbstring loaded
if (!extension_loaded('mbstring')) {
    die("'mbstring' extension is not loaded.  This is required for Grav to run correctly");
}
mb_internal_encoding('UTF-8');

// Get the Grav instance
$grav = Grav::instance(
    array(
        'loader' => $loader
    )
);

// Process the page, with Grav + Symfony
try {
    $container = $kernel->getContainer();
    $grav->kernel = $kernel;
    $grav->request = $request;
    $grav->process();
} catch (\Exception $e) {
    if (get_class($e) == "RuntimeException" && $e->getCode() == "404") {
        Debug::enable();
        // $request = Request::createFromGlobals();
        // $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
        die();
    }
    throw $e;
}
