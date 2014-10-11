<?php
/**
 * Created by PhpStorm.
 * User: Jean
 * Date: 10/10/2014
 * Time: 19:47
 */

namespace RumeauLibThemeManager\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;

class AddLayoutListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'addThemes'], 50000);
    }

    public function addThemes(MvcEvent $event)
    {
        $app              = $event->getApplication();
        $serviceLocator   = $app->getServiceManager();
        $viewMapResolver  = $serviceLocator->get('ViewTemplateMapResolver');
        $viewPathResolver = $serviceLocator->get('ViewTemplatePathStack');

        $themeManager     = $serviceLocator->get('RumeauLibThemeManager\ThemeManager');
        $themes           = $themeManager->getThemes();

        $assetPathResolver    = false;
        if ($serviceLocator->has('AssetManager\Resolver\PathStackResolver')) {
            $assetPathResolver  = $serviceLocator->get('AssetManager\Resolver\PathStackResolver');
        }

        /**
         * @var \RumeauLibThemeManager\Theme\Theme $theme
         */
        foreach ($themes as $groupName => $group) {
            foreach ($group as $theme) {
                if ($theme->getAssets() !== false && $assetPathResolver) {
                    $assetPathResolver->addPath($theme->getAssets());
                }
                $viewMapResolver->add(
                    $theme->getTemplateMap(),
                    $theme->getPath() . DIRECTORY_SEPARATOR . 'layout.phtml'
                );
            }
            $viewPathResolver->addPath($themeManager->getThemeDir() . DIRECTORY_SEPARATOR . $groupName);
        }
    }

}
