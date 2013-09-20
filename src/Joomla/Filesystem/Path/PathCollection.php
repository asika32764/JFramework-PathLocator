<?php
/**
 * Part of the Joomla Framework Filesystem Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Filesystem\Path;

use Joomla\Filesystem\Path;
use Joomla\Filesystem\Path\PathLocator;
use Joomla\Filesystem\Path\PathLocatorInterface;

/**
 * A PathLocator collection class
 *
 * @since  1.0
 */
class PathCollection implements \IteratorAggregate
{
    protected $paths = array();
    
    /**
     * __construct description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  __constructReturn
     *
     * @since  1.0
     */
    public function __construct($paths = array())
    {
        $this->addPaths($paths);
    }
    
    /**
     * addPaths description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  addPathsReturn
     *
     * @since  1.0
     */
    public function addPaths($paths)
    {
        $paths = is_array($paths) ? $paths : array($paths);
        
        foreach($paths as $key => $path)
        {
            $key = is_int($key) ? null : $key;
            
            // If path element is subclass of PathLocatorInterface, just put it in path bag.
            // You can create any your Path locator class implements from PathLocatorInterface.
            if($path instanceof PathLocatorInterface)
            {
                $this->addPath($path, $key);
            }
            // If this element is a path string, we create a PathLocator to wrap it.
            elseif(is_string($path) || !$path)
            {
                $this->addPath(new PathLocator($path), $key);
            }
            // If type of this element not match our interface, throw exception.
            else
            {
                throw new \InvalidArgumentException('PathCollection needed every path element instance of PathLocatorInterface.');
            }
        }
        
        return $this;
    }
    
    /**
     * addPath description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  addPathReturn
     *
     * @since  1.0
     */
    public function addPath($path, $key = null)
    {
        if($key){
            $this->paths[$key] = $path;
        }
        else
        {
            $this->paths[] = $path;
        }
        
        return $this;
    }
    
    /**
     * removePath description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  removePathReturn
     *
     * @since  1.0
     */
    public function removePath($key)
    {
        unset($this->paths[$key]);
        
        return $this;
    }
    
    /**
     * getPaths description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  getPathsReturn
     *
     * @since  1.0
     */
    public function getPaths()
    {
        return $this->paths;
    }
    
    /**
     * getPath description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  getPathReturn
     *
     * @since  1.0
     */
    public function getPath($key, $default = null)
    {
        if(!isset($this->paths[$key]))
        {
            if(!$default)
            {
                return $default;
            }
            
            if(!($default instanceof PathLocatorInterface))
            {
                $default = new PathLocator($default);
            }
            
            return $default;
        }
        
        return $this->paths[$key];
    }
    
    /**
     * getIterator description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  getIteratorReturn
     *
     * @since  1.0
     */
    public function getIterator()
    {
        return new \ArrayObject($this->paths);
    }
    
    /**
     * getDirectoryIterator description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  getDirectoryIteratorReturn
     *
     * @since  1.0
     */
    public function getDirectoryIterator($recursive = false)
    {
        return $this->appendIterator(function ($path) use ($recursive)
        {
            return $path->getIterator($recursive);
        });
    }
    
    /**
     * appendIterator description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  appendIteratorReturn
     *
     * @since  1.0
     */
    protected function appendIterator(\Closure $callback = null)
    {
        $iterator = new \AppendIterator();
        
        $paths    = $this->paths;
        
        $callback = function($path) use($callback, $iterator)
        {
            return $iterator->append($callback($path));
        };
        
        foreach($this->paths as $path)
        {
            if($this->isSubdir($path)) continue;
            $callback($path);
        }
        
        return $iterator;
    }
    
    /**
     * setPrefix description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  setPrefixReturn
     *
     * @since  1.0
     */
    public function setPrefix($prefix)
    {
        foreach($this->paths as &$path)
        {
            $path->setPrefix($prefix);
        }
    }
    
    /**
     * find description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  findReturn
     *
     * @since  1.0
     */
    public function find($condition, $rescurive = false)
    {
        $iterator = $this->appendIterator(function ($path) use ($recursive)
        {
            return $path->findAll($condition, $rescurive);
        });
        
        $iterator->rewind();
        
        return $iterator->current();
    }
    
    /**
     * findAll description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  findAllReturn
     *
     * @since  1.0
     */
    public function findAll()
    {
        return $this->appendIterator(function ($path) use ($recursive)
        {
            return $path->findAll($condition, $rescurive);
        });
    }
    
    /**
     * toArray description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  toArrayReturn
     *
     * @since  1.0
     */
    public function toArray()
    {
        return $this->paths;
    }
    
    /**
     * getFiles description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  getFilesReturn
     *
     * @since  1.0
     */
    public function getFiles($recursive = false)
    {
        return $this->appendIterator(function ($path) use ($recursive)
        {
            return $path->getFiles($recursive);
        });
    }
    
    /**
     * getFolders description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  getFoldersReturn
     *
     * @since  1.0
     */
    public function getFolders($recursive = false)
    {
        return $this->appendIterator(function ($path) use ($recursive)
        {
            return $path->getFolders($recursive);
        });
    }
    
    /**
     * appendAll description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  appendAllReturn
     *
     * @since  1.0
     */
    public function appendAll()
    {
        
    }
    
    /**
     * prependAll description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  prependAllReturn
     *
     * @since  1.0
     */
    public function prependAll()
    {
        
    }
    
    /**
     * isSubdir description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  isSubdirReturn
     *
     * @since  1.0
     */
    public function isSubdir($path)
    {
        foreach($this->paths as $val)
        {
            if($val->isSubdirOf($path))
            {
                return true;
            }
        }
        
        return false;
    }
}