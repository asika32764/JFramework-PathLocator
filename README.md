JFramework PathLocator & PathCollection
======================

## Usage

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

foreach($path->findAll(array('^config_*.json', '!^..'), true /* Rescrusive */) as $file) { ... } // Find all files as array, param 2 to rescrusive
```

Find by callback

``` php
$finded = $path->findAll(function($current, $key, $iterator){
    return return @preg_match('^Foo', $current->getFilename())  && ! $iterator->isDot();
}, true /* Rescrusive */);

foreach($finded as $file) { ... }
```


#### Strict Mode

This function not prepared yet.

``` php
$dl2 = new \DirectoryLocator(JPATH_ROOT . '/src', true);
$dls->child('Campenont'); // throw PathNotExistsException();
$dls->child('../www/index.php'); // throw PathNotDirException();
```

*






``` php

```