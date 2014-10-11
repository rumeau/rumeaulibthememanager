<?php
/**
 * Created by PhpStorm.
 * User: Jean
 * Date: 10/10/2014
 * Time: 16:37
 */

namespace RumeauLibThemeManager\Service;

use RumeauLibThemeManager\Theme\Theme;
use \Traversable;
use \DirectoryIterator;
use RumeauLibThemeManager\Exception;
use Zend\Cache\Storage\StorageInterface;
use Zend\Cache\StorageFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class ThemeManager
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var StorageInterface
     */
    protected $cache;

    /**
     * @var string
     */
    protected $cacheKey = 'rumeaulib_thememanager';

    /**
     * @var array
     */
    protected $themes = array();

    protected $themesDir = 'data/themes';

    public function __construct($options = [], ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $this->setOptions($options);

        $this->getThemes();
    }

    public function setOptions($options = [])
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (!is_array($options)) {
            throw new Exception\InvalidArgumentException(
                'ThemeManager requires options to be passed as an array ' .
                'or as a configuration object'
            );
        }

        if (isset($options['cache'])) {
            $this->setCache($options['cache']);
        }

        if (isset($options['themes_dir'])) {
            $this->setThemesDir($options['themes_dir']);
        }
    }

    public function setCache($cacheOptions)
    {
        if ($cacheOptions instanceof StorageInterface) {
            $this->cache = $cacheOptions;
        } else {
            $this->cache = StorageFactory::factory($cacheOptions);
        }
    }

    public function setThemesDir($themesDir)
    {
        $this->validateDir($themesDir);

        $this->themesDir = $themesDir;
    }

    public function getThemeDir()
    {
        return $this->themesDir;
    }

    protected function validateDir($directory)
    {
        if (!is_dir($directory)) {
            throw new Exception\InvalidArgumentException("Directory '$directory' is invalid or does not exists");
        }
    }

    public function getThemes($namespace = null)
    {
        $cache         = $this->cache;
        $success       = false;
        $this->themes  = $cache->getItem($this->cacheKey, $success);

        if (!is_array($this->themes) || !$success) {
            $this->loadThemes();
            $cache->setItem($this->cacheKey, $this->themes);
        }

        if ($namespace !== null) {
            if (isset($this->themes[$namespace])) {
                return $this->themes[$namespace];
            }
        }

        return $this->themes;
    }

    protected function loadThemes()
    {
        $themesDir = $this->themesDir;
        $this->validateDir($themesDir);

        $directory  = new DirectoryIterator($themesDir);
        $themeNames = [];
        foreach ($directory as $directoryNamespace) {
            if (!$directoryNamespace->isDot() && $directoryNamespace->isDir()) {
                $namespace = strtolower($directoryNamespace->getFilename());
                $themes    = new DirectoryIterator($directoryNamespace->getPathname());
                foreach ($themes as $theme) {
                    if ($theme->isDot() || !$theme->isDir()) {
                        continue;
                    }

                    $themeName = $namespace . $theme->getFilename();
                    if (in_array($themeName, $themeNames)) {
                        continue;
                    }

                    $themeNames[] = $themeName;

                    $themeObject  = new Theme();
                    $themeObject->setName($theme->getFilename());
                    $themeObject->setTemplateMap('layout/admin_' . $theme->getFilename());
                    $themeObject->setPath($theme->getPathname());

                    $this->themes[$namespace][] = $themeObject;
                }
            }
        }
    }
} 