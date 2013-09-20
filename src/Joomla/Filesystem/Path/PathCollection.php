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
            // If path element is subclass of PathLocatorInterface, just put it in path bag.
            // You can create any your Path locator class implements from PathLocatorInterface.
            if($path instanceof PathLocatorInterface)
            {
                $this->paths[$key] = $path;
            }
            // If this element is a path string, we create a PathLocator to wrap it.
            elseif(is_string($path) || !$path)
            {
                $this->paths[$key] = new PathLocator($path);
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
    public function addPath($path, $key)
    {
        $this->addPaths(array($key => $path));
        
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
    public function setPrefix()
    {
        
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
    public function find()
    {
        
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
    public function getFiles($rescursive = false)
    {
        
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
    public function getFolders($rescursive)
    {
        
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
    
    
}