<?php
/**
 * Part of the Joomla Framework Filesystem Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Filesystem\Path;

use Joomla\Filesystem\Path;

class PathLocator implements \IteratorAggregate
{
    protected $paths = array();
    
    protected $iterator = null;
    
    /**
     * function __construct
     */
    public function __construct($path)
    {
        // clean path
        $path = $this->clean($path);
        
        $this->paths = $path;
    }
    
    /**
     * function getIterator
     */
    public  function getIterator()
    {
        return new \FilesystemIterator((string)$this);
    }
    
    /**
     * function clean
     */
    protected function clean($path, $compact = false)
    {
        $path = trim($path, ' /\\');
        
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
     * function child
     */
    public function child($name)
    {
        $path = $this->clean($name);
        
        $this->append($path);
        
        return $this;
    }
    
    /**
     * function parent
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
     * function append
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
     * function extract
     */
    protected function extract($path)
    {
        return explode(DIRECTORY_SEPARATOR, $path);
    }
    
    /**
     * function compact
     */
    protected function compact($path)
    {
        return implode(DIRECTORY_SEPARATOR, $path);
    }
    
    /**
     * function __toString
     */
    public function __toString()
    {
        return $this->compact($this->paths);
    }
}