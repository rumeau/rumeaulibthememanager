<?php
/**
 * Created by PhpStorm.
 * User: Jean
 * Date: 10/10/2014
 * Time: 17:07
 */

namespace RumeauLibThemeManager\Theme;


interface ThemeInterface
{
    public function getName();

    public function getTemplateMap();

    public function getVersion();

    public function getPath();

    public function getPreview();
}
