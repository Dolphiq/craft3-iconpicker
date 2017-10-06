<?php
/**
 * Created by PhpStorm.
 * User: lucasweijers
 * Date: 19-05-17
 * Time: 15:23
 */


namespace plugins\dolphiq\iconpicker;

use Craft;
use plugins\dolphiq\iconpicker\fields\Iconpicker;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use yii\base\Event;
use yii\web\View;

class Plugin extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();

        // Register field type
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = Iconpicker::class;
        });
    }
}
