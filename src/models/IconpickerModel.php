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

use craft\base\Model;

class IconpickerModel extends Model
{
    public ?string $icon = null;
    public ?string $iconFont = null;

    public function rules(): array
    {
        return [
            [['icon', 'iconFont'], 'safe'],
        ];
    }

    public function getIconChar(): string
    {
        return '&#' . $this->icon . ';';
    }

    public function getIconCharHex(): string
    {
        return '&#x' . $this->getIconHex() . ';';
    }

    public function getIconHex(): string
    {
        return dechex($this->icon);
    }

    public function getIconSpan(): string
    {
        return '<span class="' . $this->getIconClass() . '">' . $this->getIconCharHex() . '</span>';
    }

    public function getIconClass(): string
    {
        return 'dq-icon-' . $this->iconFont;
    }
}
