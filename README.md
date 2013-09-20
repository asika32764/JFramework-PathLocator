JFramework PathLocator & PathCollection
======================

## Usage

### PathLocator object

#### Create a new PathLocator

``` php
$path = new PathLocator('/var/www/joomla');
```

#### Convert to string

``` php
echo $path or (string) $path;
```

#### Path operation

##### Child

``` php
$path->child('plugins')           // => /var/www/joomla/plugins
    ->child('system/joomla/lib')  // => /var/www/joomla/plugins/system/joomla/lib
    ;

```

##### Parent

``` php
$path->parent()       // => /var/www/joomla/plugins/system/joomla (Up one level)
    ->parent(2)       // => /var/www/joomla/plugins               (Up 2 levels)
    ->parent('www')   // => /var/www                              (Fiind a parent and go this level)
    ;

```

##### Prefix

``` php
$path2 = new PathLocator('src/Component/Issues');
echo $path->addPrefix(JPATH_ROOT); // => /var/www/src/Component/Issues
```

#### Filesystem Operation

``` php
echo $path->isDir(); // true or false
echo $path->isFile(); // true or false
echo $path->exists();
```

##### Get file info

This function not prepared yet.

``` php
$path->getInfo();            // return SplFileInfo of current directory
 
$path->getInfo('index.php'); // return SplFileInfo of this file
```

##### Scan dir

Get Folders

``` php
$dirs = $path->getFolders([true to recrusive]);

foreach($dirs as $dir)
{
    echo $dir . '<br />'; // print all dir's name
}
```

Get Files

``` php
$files = $path->getFiles([true to recrusive]);

foreach($files as $file)
{
    echo $file->getPathname() . '<br />'; // print all file's name
}
```

##### Find files

Find by string or regex

``` php
echo $path->find('config.json');     // Find one file and return fileinfo object

echo $path->find(array('^config'));  // Find one file by regex

foreach($path->findAll(array('^config_*.json', '!^..'), true /* Rescrusive */) as $file)
{
    // Find all files as array, param 2 to rescrusive
}
```

Find by callback

``` php
$callback = function($current, $key, $iterator){
    return return @preg_match('^Foo', $current->getFilename())  && ! $iterator->isDot();
};

foreach($path->findAll($callback, true) as $file)
{
    // ...
}
```


#### Strict Mode

This function not prepared yet.

``` php
$dl2 = new \DirectoryLocator(JPATH_ROOT . '/src', true);
$dls->child('Campenont');        // throw PathNotExistsException();
$dls->child('../www/index.php'); // throw PathNotDirException();
```

*

### PathCollection object

#### Create a new PathCollection

##### Add with no key

``` php
new $paths = new PathColleciotn(array(
    new PathLocator('templates/' . $template . '/html/' . $com),
    new PathLocator('components/' . $com . '/view/tmpl/'),
    new PathLocator('layouts/' . $com)
));
```

##### Add with key name

``` php
new $paths = new PathColleciotn(array(
    'Template'  => new PathLocator('templates/' . $template . '/html/' . $com),
    'Component' => new PathLocator('components/' . $com . '/view/tmpl/'),
    'Layout'    => new PathLocator('layouts/' . $com)
));
```

#### Paths operations

##### Add paths

``` php
$paths->addPath(new PathLocator('Foo'));        // No key name, will using number as key

$paths->addPath(new PathLocator('Foo'), 'Foo'); // With key name

$paths->addPaths(array(new PathLocator('Bar'))); // Add by array
```

##### Remove path

``` php
$paths->removePath('Foo');  // Remove by key name
$paths->removePath(0);      // Remove by number
```

##### Set prefix

``` php
// Prepend all path with a prefix path.

$paths->setPrefix('/var/www/joomla');

// We can change this prefix, only when converting to string,
// the prefix will have been added to path.
```



#### Iterator

List all PathLocator

``` php
foreach($paths as $path)
{
    echo $path // print path string
}
```

List all files and folders of all paths

``` php
foreach($paths->getAllChildren([true to recrusive]) as $file)
{
    echo $file // SplFileInfo
}
```

List all files

``` php
foreach($paths->getFiles([true to recrusive]) as $file)
{
    echo $file->getFilename() // SplFileInfo
}
```

List all folders

``` php
foreach($paths->getFolders([true to recrusive]) as $dir)
{
    echo $file->getPathname() // SplFileInfo
}
```

#### Find Files and Folders

Same as PathLocator, but return all paths file & folders.

``` php
$paths->find('config.json');
 
$paths->findAll('config_*.json');
```

-------

### Using it as array or string

``` php
$cache  = new PathLocator(JPATH_ROOT . '/cache');
$loader = new \Twig_Loader_Filesystem($paths);
 
$twig = new \Twig_Environment($loader, array(
    'cache' => (string) $cache,
));
```




``` php

```