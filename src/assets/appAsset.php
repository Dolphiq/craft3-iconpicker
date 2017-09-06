<?php
/**
 * Created by PhpStorm.
 * User: lucasweijers
 * Date: 16-08-17
 * Time: 14:58
 *
 * This asset will publish all css and js needed voor the backend application
 */
namespace plugins\dolphiq\iconpicker\assets;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class appAsset extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@vendor/dolphiq/iconpicker/src/resources-app';

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/field.css',
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/IconpickerModal.js',
            'js/field.js',
        ];

        parent::init();
    }
}