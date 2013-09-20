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

require_once __DIR__.'/../www/show.php';


use Joomla\Filesystem\Path;
use Joomla\Filesystem\Path\PathLocator;
use Joomla\Filesystem\Path\PathCollection;