<?php

/**
 * Test initialization and helpers.
 *
 * @author     David Grudl
 * @package    Nette\Test
 */


require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/nette/tester/Tester/bootstrap.php';


// configure environment
date_default_timezone_set('Europe/Prague');


// temporary directory garbage collection
if (mt_rand() / mt_getrandmax() < 0.01) {
	foreach (glob(__DIR__ . '/../tmp/*', GLOB_ONLYDIR) as $dir) {
		if (time() - @filemtime($dir) > 300 && @rename($dir, $dir . '-delete')) {
			TestHelpers::purge($dir . '-delete');
			rmdir($dir . '-delete');
		}
	}
}

// create temporary directory
define('TEMP_DIR', __DIR__ . '/../tmp/' . getmypid());
TestHelpers::purge(TEMP_DIR);


$loader = new Nette\Loaders\RobotLoader;
$loader->setCacheStorage(new Nette\Caching\Storages\FileStorage(TEMP_DIR));
$loader->addDirectory(__DIR__ . '/../../Nextras');
$loader->register();


$_SERVER = array_intersect_key($_SERVER, array_flip(array('PHP_SELF', 'SCRIPT_NAME', 'SERVER_ADDR', 'SERVER_SOFTWARE', 'HTTP_HOST', 'DOCUMENT_ROOT', 'OS', 'argc', 'argv')));
$_SERVER['REQUEST_TIME'] = 1234567890;
$_ENV = $_GET = $_POST = array();


if (extension_loaded('xdebug')) {
	xdebug_disable();
	TestHelpers::startCodeCoverage(__DIR__ . '/coverage.dat');
}


function id($val) {
	return $val;
}
