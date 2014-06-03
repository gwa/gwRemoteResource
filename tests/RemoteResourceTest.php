<?php
require_once __DIR__.'/../src/Gwa/Util/RemoteResource.php';

use \Gwa\Util\RemoteResource;

class RemoteResourceTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $r = new RemoteResource('http://www.google.com');
        $this->assertInstanceOf('\Gwa\Util\RemoteResource', $r);
    }

    public function testFetch()
    {
        $r = new RemoteResource('http://www.google.com');
        $success = $r->fetch();
        $this->assertTrue($success);
        $this->assertEquals('200', $r->getHTTPCode());
        $this->assertEquals('0', $r->getErrorNumber());
        $this->assertEquals('Google', $r->getTitle());
    }
}
