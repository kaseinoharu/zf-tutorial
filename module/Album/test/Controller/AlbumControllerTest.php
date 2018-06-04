<?php
namespace AlbumTest\Controller;

use Album\Controller\AlbumController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Album\Model\AlbumTable;
use Zend\ServiceManager\ServiceManager;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    /* true is the default. You'll get a list of the exceptions raised. */
    protected $traceError = true;
    protected $albumTable;

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
        
        $this->configureServiceManager($this->getApplicationServiceLocator());
 
        /* add some configuration to the test case to remove the database configuration. */
        /* A failing test case -START- */
//         $services = $this->getApplicationServiceLocator();
//         $config = $services->get('config');
//         unset($config['db']);
//         $services->setAllowOverride(true);
//         $services->setService('config', $config);
//         $services->setAllowOverride(false);
        /* A failing test case -END- */
    }
    
    public function testIndexActionCanBeAccessed()
    {
        $this->albumTable->fetchAll()->willReturn([]);
        
        /* Asserts that the response code is 200, and 
         * ended up in the desired module and controller. */
        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName(AlbumController::class);
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }
    
    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);
        
        $services->setService('config', $this->updateConfig($services->get('config')));
        $servieces->setService(AlbumTable::class, $this->mockalbumTable->reveal());
        
        $services->setallowOverride(false);
    }
    
    protected function updateconfig($config)
    {
        $config['db'] = [];
        return $config;
    }

    protected function mockAlbumTable()
    {
        /* create a mock instance of AlbumTable using Prophecy which is an object mocking framework
         * that's buncled and integrated in PHPUnit.
         */
        $this->albumTable = $this->prophersize(AlbumTable::class);
        return $this->albumTable;
    }
}