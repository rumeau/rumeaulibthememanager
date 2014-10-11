<?php
/**
 * Created by PhpStorm.
 * User: Jean
 * Date: 10/10/2014
 * Time: 17:07
 */

namespace RumeauLibThemeManager\Theme;


class Theme implements ThemeInterface
{
    protected $name;

    protected $templateMap;

    protected $version;

    protected $path;

    protected $preview;

    protected $assets = null;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTemplateMap($templateMap)
    {
        $this->templateMap = $templateMap;
    }

    public function getTemplateMap()
    {
        return $this->templateMap;
    }

    public function getVersion()
    {
        if ($this->version) {
            return $this->version;
        }

        if (!file_exists($this->getPath() . DIRECTORY_SEPARATOR . 'version')) {
            return null;
        }

        $this->version = file_get_contents($this->getPath() . DIRECTORY_SEPARATOR . 'version');

        return $this->version;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPreview()
    {
        if ($this->preview) {
            return $this->preview;
        }

        if (!file_exists($this->getPath() . DIRECTORY_SEPARATOR . 'preview.png')) {
            return '';
        }

        $this->preview = $this->getPath() . DIRECTORY_SEPARATOR . 'preview.png';

        return $this->preview;
    }

    public function getAssets()
    {
        if ($this->assets === false) {
            return false;
        }

        if ($this->assets !== null) {
            return $this->assets;
        }

        if (!is_dir($this->getPath() . DIRECTORY_SEPARATOR . 'assets/')) {
            $this->assets = false;
        }

        $this->assets = $this->getPath() . DIRECTORY_SEPARATOR . 'assets/';

        return $this->assets;
    }
}
