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
	 * @var array
	 */
	protected $iterator = array();
	
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
		$this->paths = $this->regularize($path);
	}
	
	/**
	 * Get file iterator of current dir.
	 *
	 * @return  \FilesystemIterator  File & dir iterator.
	 */
	public function getIterator($recursive = false)
	{
		if(!empty($this->iterator[(string) $this]))
		{
			$iterator = $this->iterator[(string) $this];
		}
		else
		{
			// Remove previous iterator cache
			$this->iterator = array();
			
			$iterator = $this->iterator[(string) $this] = new \RecursiveDirectoryIterator((string)$this);
		}
		
		// If rescurive set to true, use RecursiveIteratorIterator
		return $recursive ? new \RecursiveIteratorIterator($iterator) : $iterator;
	}
	
	/**
	 * Get folder iterator of current dir
	 *
	 * @return  \CallbackFilterIterator  Iterator only include dirs.
	 */
	public function getFolders($recursive = false)
	{
		return $this->findByCallback(function($current, $key, $iterator) use ($recursive)
		{
			if($recursive)
			{
				$baseame = $current->getBasename();
			
				if($baseame == '..') return false;
				
				return $iterator->isDir();
			}
			else
			{
				return $iterator->isDir() && ! $iterator->isDot();
			}
		}, $recursive);
	}
	
	/**
	 * Get file iterator of current dir
	 *
	 * @return  \CallbackFilterIterator  Iterator only include files.
	 */
	public function getFiles($recursive = false)
	{
		return $this->findByCallback(function($current, $key, $iterator)
		{
			return $iterator->isFile() && ! $iterator->isDot();
		}, $recursive);
	}
	
	/**
	 * find description
	 *
	 * @param  string
	 * @param  string
	 *
	 * @return  string  findReturn
	 *
	 * @since  1.0
	 */
	public function find($condition, $recursive = false)
	{
		return new \LimitIterator($this->findAll($condition, $recursive), 0, 1);
	}
	
	/**
	 * finAll description
	 *
	 * @param  string
	 *
	 * @return  string  finAllReturn
	 *
	 * @since  1.0
	 */
	public function findAll($condition, $recursive = false)
	{
		if(!($condition instanceof \Closure))
		{
			if(is_array($condition))
			{
				$condition = '/(' . implode('|', $condition) . ')/';
			}
			else{
				$condition = (string) $condition;
			}
			
			$condition = function($current, $key, $iterator) use ($condition)
			{
				return @preg_match($condition, $iterator->getFilename())  && ! $iterator->isDot();
			};
		}
		
		return $this->findByCallback($condition, $recursive);
	}
	
	/**
	 * findByCallback description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  findByCallbackReturn
	 *
	 * @since  1.0
	 */
	protected function findByCallback(\Closure $callback, $recursive = false)
	{
		return new \CallbackFilterIterator($this->getIterator($recursive), $callback);
	}
	
	/**
	 * Regularize path, remove not necessary elements. 
	 *
	 * @param  string  $path          A given path to regularize.
	 * @param  string  $returnString  Return string or array.
	 *
	 * @return  string|array  Regularized path.
	 *
	 * @since  1.0
	 */
	public function regularize($path, $returnString = false)
	{
		// Clean the Directory separator 
		$path = $this->clean($path);
		
		// Extract to array
		$path = $this->extract($path);
		
		// Remove dots from path
		$path = $this->removeDots($path);
		
		// If set to return string, compact it.
		if($returnString == true)
		{
			$path = $thsi->compact($path);
		}
		
		return $path;
	}
	
	/**
	 * Clean path and remove dots.
	 * 
	 * @param   string   $path     A given path to parse.
	 *
	 * @return  string  Cleaned path.
	 */
	protected function clean($path)
	{
		$path = rtrim($path, ' /\\');
		
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR ,$path);
		
		return $path;
	}
	
	/**
	 * Remove dots from path.
	 *
	 * @param  string|array  $path  A given path to remove dots.
	 *
	 * @return  string|array  Cleaned path.
	 *
	 * @since  1.0
	 */
	public function removeDots($path)
	{
		$isBeginning = true ;
		
		// If not array, extract it.
		$isArray = is_array($path);
		
		$path = $isArray ? $path : $this->extract($path);
		
		// Search for dot files
		foreach($path as $key => $row)
		{
			// Remove dot files
			if($row == '.')
			{
				unset($path[$key]);
			}
			
			// Remove dots and go parent dir
			if($row == '..' && !$isBeginning)
			{
				unset($path[$key]);
				unset($path[$key - 1]);
			}
			
			// Do not get parent if dots in the beginning
			if($row != '..' && $isBeginning)
			{
				$isBeginning = false;
			}
		}
		
		// Re index array
		$path = array_values($path);
		
		return $isArray ? $path : $this->compact($path);
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
		$prefix = $this->regularize($prefix);
		
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
		$path = $this->regularize($name);
		
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
			$path = $this->regularize();
		}
		
		$path = array_merge($this->paths, $path);
		
		$this->paths = $this->removeDots($path);
		
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
		
		$path = $this->removeDots($path);
		
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