<?php
namespace AlbumTest\Controller;

use Album\Controller\AlbumController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = false;
    
    public function setUp()
    {
        /* You can override configuration here with test case specific values. */
        $configOverrides = [];
        
        $this->setApplicationConfig(ArrayUtils::merge(
            /* Grabbing the full application configration: */
            include __DIR__. '/../../../../config/application.config.php',
            $configOverrides
        ));
        parent::setUp();
    }
    
    public function testIndexActionCanBeAccessed()
    {
        /* Asserts that the response code is 200, and 
         * ended up in the desired module and controller. */
        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName(AlbumController::class);
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }
}