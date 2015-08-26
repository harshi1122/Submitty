<?php

namespace tests;

use lib\AutoLoader;

class AutoLoaderTester extends \PHPUnit_Framework_TestCase {
    private $autoloader_classes;
    
    public function setUp() {
        $this->autoloader_classes = AutoLoader::getClasses();
    }
    
    public function tearDown() {
        AutoLoader::setClasses($this->autoloader_classes);    
    }
    
    public function testGetPathToRoot() {
        $this->assertEquals("../", AutoLoader::getPathToRoot(__DIR__));
    }
    
    public function testRegisterDirectory() {
        AutoLoader::registerDirectory(__DIR__);
        $classes = AutoLoader::getClasses();
        $this->assertTrue(in_array('AutoLoaderTester',array_keys($classes)));
        $this->assertEquals(__FILE__,$classes['AutoLoaderTester']);
        
        $this->assertTrue(in_array('DummyClass', array_keys($classes)));
        $this->assertEquals(__DIR__."/data/DummyClass.php", $classes['DummyClass']);
    }
    
    public function testEmptyLoader() {
        AutoLoader::registerDirectory(__DIR__);
        AutoLoader::emptyLoader();
        $this->assertEmpty(AutoLoader::getClasses());
    }
    
    public function testUnregister() {
        AutoLoader::registerDirectory(__DIR__);
        $this->assertTrue(in_array('DummyClass', array_keys(AutoLoader::getClasses())));
        AutoLoader::unregisterClass('DummyClass');
        $this->assertFalse(in_array('DummyClass', array_keys(AutoLoader::getClasses())));
    }
    
    public function testSetClasses() {
        AutoLoader::emptyLoader();
        AutoLoader::setClasses(array("DummyClass"=>"Dummy"));
        $this->assertEquals(1, count(AutoLoader::getClasses()));
        $this->assertTrue(in_array('DummyClass', array_keys(AutoLoader::getClasses())));
    }
    
    public function testRegisterNamespace() {
        AutoLoader::registerDirectory(__DIR__, true, "tests");
        $this->assertTrue(in_array('tests\data\DummyClass', array_keys(AutoLoader::getClasses())));
        $this->assertTrue(in_array('tests\AutoLoaderTester', array_keys(AutoLoader::getClasses())));
    }
}