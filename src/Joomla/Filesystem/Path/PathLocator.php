<?php
/**
 * Part of the Joomla Framework Filesystem Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Filesystem\Path;

use Joomla\Filesystem\Path;

/**
 * A Path locator class
 *
 * @since  1.0
 */
class PathLocator implements \IteratorAggregate
{
    /**
     * Path prefix
     *
     * @var string   
     */
    protected $prefix = '';
    
    /**
     * A variable to store paths
     *
     * @var array 
     */
    protected $paths = array();
    
    /**
     * Iterator cache
     *
     * @var \FilesystemIterator 
     */
    protected $iterator = null;
    
    /**
     * Constructor to handle path.
     * 
     * @param   string  $path  Path to parse.
     *
     * @since   1.0
     */
    public function __construct($path)
    {
        // clean path
        $this->paths = $this->clean($path);
    }
    
    /**
     * Get file iterator of current dir.
     *
     * @return  \FilesystemIterator  File & dir iterator.
     */
    public function getIterator()
    {
        return new \FilesystemIterator((string)$this);
    }
    
    /**
     * Get folder iterator of current dir
     *
     * @return  \CallbackFilterIterator  Iterator only include dirs.
     */
    public function getFolders()
    {
        return new \CallbackFilterIterator($this->getIterator(), function($current, $key, $iterator)
        {
            return $iterator->isDir() && ! $iterator->isDot();
        });
    }
    
    /**
     * Get file iterator of current dir
     *
     * @return  \CallbackFilterIterator  Iterator only include files.
     */
    public function getFiles()
    {
        return new \CallbackFilterIterator($this->getIterator(), function($current, $key, $iterator)
        {
            return $iterator->isFile() && ! $iterator->isDot();
        });
    }
    
    /**
     * Clean path and remove dots.
     * 
     * @param   string   $path     A given path to parse.
     * @param   boolean  $compact  If true, return imploded string, or return array.
     *
     * @return  string|array  Cleaned path.
     */
    protected function clean($path, $compact = false)
    {
        $path = rtrim($path, ' /\\');
        
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR ,$path);
        
        $path = $this->extract($path);
        
        foreach($path as $key => $row)
        {
            // Remove dot files
            if($row == '.')
            {
                unset($path[$key]);
            }
            
            // Remove .. and parent dir
            if($row == '..')
            {
                unset($path[$key]);
                unset($path[$key - 1]);
            }
        }
        
        // Re index array
        $path = array_values($path);
        
        return $compact ? $this->compact($path) : $path;
    }
    
    /**
     * Detect is current path a dir?
     * 
     * @return  boolean  True if is a dir.
     */
    public function isDir()
    {
        return is_dir((string) $this);
    }
    
    /**
     * Detect is current path a file?
     * 
     * @return  boolean  True if is a file.
     */
    public function isFile()
    {
        return is_file((string) $this);
    }
    
    /**
     * Detect is current path exists?
     * 
     * @return  boolean  True if exists.
     */
    public function exists()
    {
        return file_exists((string) $this);
    }
    
    /**
     * Set a prefix, when this object convert to string,
     * prefix will auto add to the front of path.
     * 
     * @param   string  $prefix  Prefix string to set.
     *
     * @return  PathLocator  Return this object to support chaining.
     */
    public function setPrefix($prefix = '')
    {
        $this->prefix = $this->clean($prefix, true);
        
        return $this;
    }
    
    /**
     * Get a child path of given name.
     * 
     * @param   string  $name  Child name.
     *
     * @return  PathLocator  Return this object to support chaining.
     */
    public function child($name)
    {
        $path = $this->clean($name);
        
        $this->append($path);
        
        return $this;
    }
    
    /**
     * Get a parent path of given condition.
     * 
     * @param   string  $name  Parent condition.
     *
     * @return  PathLocator  Return this object to support chaining.
     */
    public function parent($condition = null)
    {
        // Up one level
        if(is_null($condition))
        {
            array_pop($this->paths);
        }
        // Up mutiple level
        elseif(is_int($condition))
        {
            $this->paths = array_slice($this->paths, 0, -$condition);
        }
        // Find a dir name and go to this level
        elseif(is_string($condition))
        {
            $paths = $this->paths;
            
            $paths = array_reverse($paths);
            
            // Find parent
            $n = 0;
            foreach($paths as $key => $name)
            {
                if($key == 0)
                {
                    continue; // Ignore latest dir
                }
                
                // Is this dir match condition?
                if($name == $condition)
                {
                    $n = $key;
                    break;
                }
            }
            
            $this->paths = array_slice($this->paths, 0, -$n);
        }
        
        return $this;
    }
    
    /**
     * Append a new path before current path.
     * 
     * @param   string  $path  Path to append.
     *
     * @return  PathLocator  Return this object to support chaining.
     */
    public function append($path)
    {
        if(!is_array($path))
        {
            $path = $this->clean();
        }
        
        $this->paths = array_merge($this->paths, $path);
        
        return $this;
    }
    
    /**
     * Convert this object to string.
     *
     * @return  string  Path name.
     */
    public function __toString()
    {
        $path = $this->compact($this->paths);
        
        if($this->prefix)
        {
            $path = rtrim($this->prefix, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);
        }
        
        return $path;
    }
    
    /**
     * Explode path by DIRECTORY_SEPARATOR.
     * 
     * @param   string  $path  Path to extract.
     *
     * @return  array  Extracted path array.
     */
    protected function extract($path)
    {
        return explode(DIRECTORY_SEPARATOR, $path);
    }
    
    /**
     * Implode path by DIRECTORY_SEPARATOR.
     * 
     * @param   string  $path  Path to compact.
     *
     * @return  array  Compacted path array.
     */
    protected function compact($path)
    {
        return implode(DIRECTORY_SEPARATOR, $path);
    }
}