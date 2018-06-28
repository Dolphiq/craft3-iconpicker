<?php
/**
 * Created by PhpStorm.
 * User: lucasweijers
 * Date: 16-08-17
 * Time: 13:17
 *
 * @var string $name
 * @var \plugins\dolphiq\iconpicker\models\IconpickerModel $value
 * @var \plugins\dolphiq\iconpicker\fields\Iconpicker $field
 * @var array $icons
 */

$field->getFontCss();

if (is_array($icons) && count($icons) > 0) {
    ?>


  <input type="hidden" name="<?= $name; ?>[icon]" class="iconpicker-icon" value="<?= $value->icon; ?>">
  <input type="hidden" name="<?= $name; ?>[iconFont]" value="<?= $field->getIconFontName(); ?>">

  <p class="iconpicker-msg <?= empty($value->icon) ? 'dolphiq-iconpicker--empty' : ''?>">
    <span class="dolphiq-iconpicker <?= $field::ICON_CLASS; ?><?= $field->getIconFontName(); ?>">
      <span class="iconpicker-preview">
        <?= !empty($value->icon) ? '&#x'.$value->iconHex : ''; ?>
      </span>
    </span>
    <span class="iconpicker-code">
        <?= !empty($value->icon) ? '('.$value->iconHex.')' : ''; ?>
    </span>
  </p>
  <button class="iconpickerField_modaltoggle btn" type="button">Pick an icon</button>

  <div class="modal iconpickerField_modal elementselectormodal" style="display: none" id="<?= $name; ?>_modal">
    <div class="body">
      <div class="content">
        <div class="main">
          <div class="dolphiq-iconpicker <?= $field::ICON_CLASS; ?><?= $field->getIconFontName(); ?>--">
              <?php
              if (count($icons) > 0) {
                  foreach ($icons as $icon => $iconId) {
                      echo '<span data-val="' . $icon . '" class="' . (($value->icon == $icon) ? "iconpicker--selected" : "") . '" title="' . dechex($icon) . '">&#x' . dechex($icon) . ';</span>';
                  }
              } else {
                  echo 'No icons found in the selected font';
              } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="footer">
      <div class="iconpickerField_modal_preview"></div>
      <div class="buttons right">
        <div class="btn iconpickerField_modal_close" tabindex="0"><?= Craft::t('app', 'Cancel'); ?></div>
        <div class="btn disabled submit iconpickerField_modal_select"><?= Craft::t('app', 'Select'); ?></div>
      </div>
    </div>
    <div class="resizehandle"></div>
  </div>

<?php
} else {
                  ?>
    <p>There is no font uploaded to the font folder of this plugin, no font selected in the field settings, or the font contains no icons</p>
<?php
              } ?>