<?php
namespace RumeauLibThemeManager;

use RumeauLibThemeManager\Listener\AddLayoutListener;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Mvc\MvcEvent;

class Module implements BootstrapListenerInterface
{
    /**
     * @param EventInterface|MvcEvent $event
     * @return array|void
     */
    public function onBootstrap(EventInterface $event)
    {
        $app = $event->getApplication();
        /**
         * @var \Zend\EventManager\EventManager $em
         */
        $em  = $app->getEventManager();

        $em->attachAggregate(new AddLayoutListener());
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
