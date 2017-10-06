<?php
/**
 * Created by PhpStorm.
 * User: lucasweijers
 * Date: 17-08-17
 * Time: 13:53
 *
 * The currently selected icon is saved with this model.
 * The icon is a unicode number
 *
 * @property $icon string
 * @property $iconFont string
 * @property $iconChar string
 * @property $iconHex string
 * @property $iconSpan string
 */

namespace plugins\dolphiq\iconpicker\models;

use Craft;
use craft\base\Model;

class IconpickerModel extends Model
{
    public $icon;
    public $iconFont;

    public function rules()
    {
        return [
            [['icon', 'iconFont'], 'safe'],
        ];
    }

    public function getIconChar()
    {
        return '&#'.$this->icon.';';
    }

    public function getIconCharHex()
    {
        return '&#x'.$this->getIconHex().';';
    }

    public function getIconHex()
    {
        return dechex($this->icon);
    }

    public function getIconSpan()
    {
        return '<span class="'.$this->getIconClass().'">'.$this->getIconCharHex().'</span>';
    }

    public function getIconClass()
    {
        return 'dq-icon-'.$this->iconFont;
    }
}
