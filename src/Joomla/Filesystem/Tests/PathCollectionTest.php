<?php
/**
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Filesystem\Path;
use Joomla\Filesystem\Path\PathLocator;
use Joomla\Filesystem\Path\PathCollection;

/**
 * Tests for the PathCollection class.
 *
 * @since  1.0
 */
class PathCollectionTest extends PHPUnit_Framework_TestCase
{
    public $collection;
    
    /**
     * setUp description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  setUpReturn
     *
     * @since  1.0
     */
    public function setUp()
    {
        $this->collection = new PathCollection();
    }
    
    /**
	 * Data provider for testClean() method.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getPathData()
	{
		return array(
			// Input Path, Directory Separator, Expected Output
			'one path' => array(
				'/var/www/foo/bar',
				array(new PathLocator('/var/www/foo/bar'))
			),
			
            'paths with on key' => array(
				array(
					'/',
					'/var/www/foo/bar',
					'/var/www/joomla/bar/foo'
				),
				array(
					new PathLocator('/'),
					new PathLocator('/var/www/foo/bar'),
					new PathLocator('/var/www/joomla/bar/foo')
				)
			),
			
            'paths with key' => array(
				array(
					'root' => '/',
					'foo' => '/var/www/foo'
				),
				array(
					'root' => new PathLocator('/'),
					'foo' => new PathLocator('/var/www/foo')
				)
			)
		);
	}
    
    /**
     * test__construct description
     *
     * @param  string
     * @param  string
     * @param  string
     *
     * @return  string  test__constructReturn
     *
     * @since  1.0
     */
    public function test__construct()
    {
        $collections = new PathCollection('/var/www/foo/bar');
        
        $paths = $collections->getPaths();
		
		$this->assertEquals(array(new PathLocator('/var/www/foo/bar')), $paths);
    }
	
	/**
	 * testAddPaths description
	 *
	 * @param  string
	 * @param  string
	 * @param  string
	 *
	 * @return  string  testAddPathsReturn
	 *
	 * @dataProvider  getPathData
	 * 
	 * @since  1.0
	 */
	public function testAddPaths($paths, $expects)
	{
		$this->collection->addPaths($paths);
		
		$paths = $this->collection->getPaths();
		
		$this->assertEquals($paths, $expects);
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
    public function testAddPath()
    {
		$path = new PathLocator('/var/foo/bar');
		
        $this->collection->addPath($path, 'bar');
		
		$this->assertEquals($path, $this->collection->getPath('bar'));
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
    public function testRemovePath()
    {
		$path = new PathLocator('/var/foo/bar');
		
        $this->collection->addPath($path, 'bar');
		
        $this->collection->removePath('bar');
		
		$path = $this->collection->getPath('bar');
		
		$this->assertNull($path);
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
     * @dataProvider  getPathData
     * 
     * @since  1.0
     */
    public function testGetPaths($paths, $expects)
    {
		$this->setUp();
		
        $this->collection->addPaths($paths);
		
		$paths = $this->collection->getPaths();
		
		$this->assertEquals($paths, $expects);
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
    public function testGetPath()
    {
        $path = new PathLocator('/var/foo/bar2');
		
        $this->collection->addPath($path, 'bar2');
		
		$this->assertEquals($path, $this->collection->getPath('bar2'));
		
		$this->assertEquals(new PathLocator('/'), $this->collection->getPath('bar3', '/'));
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