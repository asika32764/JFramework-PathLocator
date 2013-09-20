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

// Start test collection

$pc = new PathCollection(array(
    new PathLocator(JPATH_BASE . '/src/Joomla/Filesystem/Tests/files'),
    new PathLocator(JPATH_BASE . '/vendor')
));

foreach($pc as $pl)
{
    echo $pl . '<br>';
}

echo '<br><br>';

foreach($pc->getFolders(true) as $pl)
{
    echo $pl . '<br>';
}

echo '<br><br>';

foreach($pc->getDirectoryIterator() as $pl)
{
    echo $pl . '<br>';
}

echo '<br><h3>Subdir test</h3><br>';

$pc = new PathCollection(array(
    new PathLocator(JPATH_BASE . '/src/Joomla/Filesystem/Tests/files'),
    new PathLocator(JPATH_BASE . '/src/Joomla/Filesystem/')
));


foreach($pc->getFolders(true) as $pl)
{
    echo $pl . '<br>';
}

show($pc->toArray());
