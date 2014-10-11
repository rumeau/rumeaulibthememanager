<?php
/**
 * Created by PhpStorm.
 * User: Jean
 * Date: 10/10/2014
 * Time: 16:36
 */
namespace RumeauLibThemeManager\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ThemeManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')['rumeaulib_thememanager'];

        return new ThemeManager($config, $serviceLocator);
    }

}
