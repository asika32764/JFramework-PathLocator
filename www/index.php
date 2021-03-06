<?php

// Set error reporting for development
error_reporting(32767);

// Define required paths
define('JPATH_BASE',    dirname(__DIR__));
define('JPATH_ROOT',    JPATH_BASE);
define('JPATH_SOURCE',  JPATH_BASE . '/src');
define('JPATH_WEB',     JPATH_BASE . '/www');

// Load the Composer autoloader
require JPATH_BASE . '/vendor/autoload.php';

require_once __DIR__.'/show.php';


use Joomla\Filesystem\Path;
use Joomla\Filesystem\Path\PathLocator;
use Joomla\Filesystem\Path\PathCollection;


// Start class test
echo '<h3>Set some paths in PathLocator</h3>';

echo $p1 = new PathLocator('D:/www/repo/../repo\path\\');

echo '<br><br>';

echo $p2 = new PathLocator('/var/www/repo/../repo\path\\');

echo '<br><br>';

echo $p3 = new PathLocator('repo/../repo\path\\');

echo '<br><br>';

echo $p3->child('path')->child('joomla');

echo '<br><br>';

echo $p3->parent(2)->parent();

echo '<br><br>';

echo $p1->isDir() ? 'true' : 'false';

echo '<br><br>';

echo $p1->child('README.md')->isFile() ? 'true' : 'false';

echo '<br><br>';

$dirs = $p1->parent()->getFolders();

foreach( $dirs as $dir ):
    echo $dir . '<br>';
endforeach;

echo '<br><br>';

$dirs = $p1->getFiles();

foreach( $dirs as $dir ):
    echo $dir . '<br>';
endforeach;

echo '<br><br>';

echo $p3->child('vendor/joomla')->setPrefix('D:/www');

echo '<br><br>';

echo $p4 = new PathLocator('../../joomla');

echo '<br><br>';

echo $p4->child('administrator/com_content')->child('../com_finder');

echo '<br><br>';

$dirs = $p1->child('src/Joomla')->getFolders();

foreach( $dirs as $dir ):
    echo $dir . '<br>';
endforeach;

echo '<br><br>';

// Show all files
$plimit = new PathLocator('D:/www/repo/path');

foreach( with(new PathLocator('D:/www/repo/path'))->getFolders(true) as $dir ):
    echo $dir . '<br>';
endforeach;


