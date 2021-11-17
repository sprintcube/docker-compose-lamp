<?php

class ConfigTest extends \SpecifyUnitTest
{
    /**
     * @var \Codeception\Specify\Config
     */
    protected $config;

    protected function setUp()
    {
        $this->config = \Codeception\Specify\Config::create();
    }

    public function testDefaults()
    {
        $this->assertTrue($this->config->propertyIgnored('backupGlobals'));
        $this->assertTrue($this->config->propertyIgnored('dependencies'));
        $this->assertTrue($this->config->propertyIsDeeplyCloned('user'));
        $this->assertFalse($this->config->propertyIsShallowCloned('user'));
    }

    public function testCloneModes()
    {
        $this->config->is_deep = false;
        $this->config->deep[] = 'user';
        $this->assertTrue($this->config->propertyIsShallowCloned('profile'));
        $this->assertFalse($this->config->propertyIsShallowCloned('user'));
        $this->assertTrue($this->config->propertyIsDeeplyCloned('user'));
    }

    public function testConfigOnly()
    {
        $this->config->deep = ['user', 'post', 'tag'];
        $this->config->only = ['user'];
//        $this->assertFalse($this->config->propertyIgnored('user'));
//        $this->assertTrue($this->config->propertyIgnored('post'));
    }

}

