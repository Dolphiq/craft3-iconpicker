<?php
/**
 * Created by PhpStorm.
 * User: lucasweijers
 * Date: 04-09-17
 * Time: 14:58
 *
 * This field will offer the user a choice between various icons in the selected font, and generates the needed css
 */

namespace plugins\dolphiq\iconpicker\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\helpers\FileHelper;
use FontLib\Font;
use plugins\dolphiq\iconpicker\assets\appAsset;
use plugins\dolphiq\iconpicker\assets\sharedAsset;
use plugins\dolphiq\iconpicker\models\IconpickerModel;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class Iconpicker extends Field implements PreviewableFieldInterface
{
    // Static
    // =========================================================================

    /**
     * @var string The directory where the fonts are
     */
    const FONT_DIR = '@vendor/dolphiq/iconpicker/src/resources-shared/fonts/';

    /**
     * @var array All extensions that are allowed to be imported as a font
     */
    const FONT_EXT = ['*.woff', '*.ttf'];

    /**
     * @var string Icon class
     */
    const ICON_CLASS = 'dq-icon-';

    /**
     * @var string Pattern to format a safe file name
     */
    const SAFE_NAME_PATTERN = '/[^A-Za-z0-9_\-]/';

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('app', 'Iconpicker');
    }


    // Properties
    // =========================================================================

    /**
     * @var string|null The input’s placeholder text
     */
    public ?string $placeholder = null;

    /**
     * @var string The type of database column the field should have in the content table
     */
    public string $columnType = Schema::TYPE_STRING;

    /**
     * @var string The current selected iconfont to use
     */
    public string $iconFont = "";

    /**
     * @var array A list with the available fonts
     */
    private array $fonts = [];


    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules[] = [['iconFont'], 'safe'];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('dolphiq-iconpicker/settings', [
            'field' => $this,
            'fonts' => $this->getFontOptions(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Load the sharedbundle
        $this->getFontCss();

        // Load the assetbundle
        Craft::$app->view->registerAssetBundle(appAsset::class);

        // Display the field
        return Craft::$app->getView()->renderTemplate('dolphiq-iconpicker/input', [
            'name' => $this->handle,
            'value' => $value,
            'field' => $this,
            'icons' => $this->getIcons(),
            'iconFontName' => $this->getIconFontName(),
            'iconClass' => self::ICON_CLASS.$this->getIconFontName(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ElementInterface $element = null): IconpickerModel
    {
        if ($value instanceof IconpickerModel) {
            return $value;
        }

        $model = new IconpickerModel();

        /**
         * Serialised value from the DB
         */
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        /**
         * Array value from post or unserialised array
         */
        if (is_array($value) && !empty(array_filter($value))) {
            $model->load($value, '');
        }

        return $model;
    }

    /**
     * Generate a css file that creates font families for each font file in the font directory
     *
     * @return void
     * @throws \FontLib\Exception\FontNotFoundException
     * @throws \yii\base\InvalidConfigException
     */
    public function getFontCss(): void
    {
        $sharedAsset = new sharedAsset();
        $scss = "";

        foreach ($this->getFonts() as $safeName => $pathInfo) {
            $fontFile = $pathInfo['path'];
            $font = Font::load($fontFile);
            $font->parse();

            if (!empty($font)) {
                $iconFontName = $safeName;

                if (!empty($iconFontName)) {
                    $scss .= "
@font-face {
    font-family: 'dq-iconpicker-" . $iconFontName . "';
    src: url('../fonts/" . $pathInfo['basename'] . "');
    font-weight: 100;
    font-style: normal;
}\n\n";

                    $scss .= '
[class*="dq-icon-' . $iconFontName . '"] {
  /* use !important to prevent issues with browser extensions that change fonts */
  font-family: dq-iconpicker-' . $iconFontName . ' !important;
  speak: none;
  font-style: normal;
  font-weight: normal;
  font-variant: normal;
  text-transform: none;
  line-height: 1;
  display: inline-block;
  vertical-align: baseline;


  /* Better Font Rendering =========== */
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;

}' . "\n\n";
                }
            }
        }

        file_put_contents(Craft::getAlias($sharedAsset->sourcePath . '/css/fonts.css'), $scss);

        // Register the assetbundle that loads the generated css
        Craft::$app->view->registerAssetBundle(sharedAsset::class);
    }

    /**
     * Get the fontname of the currently selected font.
     *
     * @return string
     */
    public function getIconFontName(): string
    {
        return $this->iconFont;
    }


    // Privtae Methods
    // =========================================================================

    /**
     * Get all the fonts that are residing in the fonts directory and have the right extension.
     * Index them by path as key and pathinfo array as value
     *
     * @return array
     */
    private function getFonts(): array
    {
        if (empty($this->fonts)) {
            $files = FileHelper::findFiles(Craft::getAlias(self::FONT_DIR), ['only' => self::FONT_EXT]);
            $filenames = [];
            $fonts = [];

            foreach ($files as $file) {
                $pathInfo = pathinfo($file);
                $safename = in_array($pathInfo['filename'], $filenames) ? $pathInfo['basename'] : $pathInfo['filename'];
                $safename = $this->safeName($safename);
                $fonts[$safename] = ArrayHelper::merge(
                    [
                        'path' => $file,
                        'safename' => $safename,
                    ],
                    $pathInfo
                );

                $filenames[] = $pathInfo['filename'];
            }

            $this->fonts = $fonts;
        }

        return $this->fonts;
    }

    /**
     * Returns an options list for the settings dropdown when defining a field
     *
     * @return array
     */
    private function getFontOptions(): array
    {
        $f = $this->getFonts();
        if (!empty($f)) {
            return ArrayHelper::map($f, 'safename', 'basename');
        }

        return [];
    }

    /**
     * Load a font and get all unicode characters available in that font.
     *
     * @return array
     * @throws \FontLib\Exception\FontNotFoundException
     */
    private function getIcons(): array
    {
        $returnValue = [];

        if (!empty($this->iconFont)) {
            $fonts = $this->getFonts();
            if (!empty($fonts) && isset($fonts[$this->iconFont])) {
                $font = Font::load($fonts[$this->iconFont]['path']);
                $font->parse();
                $icons = $font->getUnicodeCharMap();
                if ($icons !== null) {
                    foreach ($icons as $dec => $id) {
                        $returnValue[$dec] = dechex($dec);
                    }
                }
            }
        }

        return $returnValue;
    }

    private function safeName($filename): array|string|null
    {
        return preg_replace(self::SAFE_NAME_PATTERN, '-', $filename);
    }
}
