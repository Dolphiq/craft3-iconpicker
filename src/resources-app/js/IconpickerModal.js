/**
 * Created by lucasweijers on 18-08-17.
 */
/** global: Craft */
/** global: Garnish */
/**
 * Map location selector modal class
 */
Craft.IconpickerModal = Garnish.Modal.extend(
  {

    icon: null,
    iconField: null,
    iconSelectedClass: 'iconpicker--selected',

    $selectBtn: null,
    $cancelBtn: null,

    init: function(container, field, settings) {
      this.iconField = field;
      this.setSettings(settings, Craft.IconpickerModal.defaults);

      // Build the modal
      this.base(container, this.settings);

      this.$cancelBtn = $(container).find('.iconpickerField_modal_close');
      this.$selectBtn = $(container).find('.iconpickerField_modal_select');

      this.addListener(this.$cancelBtn, 'activate', 'cancel');
      this.addListener(this.$selectBtn, 'activate', 'selectIcon');
    },

    onFadeIn: function() {
      var self = this;
      // If there is already an icon selected then use that one as the selected icon
      if($(this.$container).find('.dolphiq-iconpicker span.'+this.iconSelectedClass).length > 0){
        this.enableSelectBtn();
      }

      // Listen if an icon is clicked. If so then enable the selecticon button
      $(this.$container).find('.dolphiq-iconpicker span').click(function(){

        // Set selected class
        $(this).parent().find('span').removeClass(self.iconSelectedClass);
        $(this).addClass(self.iconSelectedClass);

        // Set icon value and enable select button
        self.icon = $(this).data('val');
        self.enableSelectBtn();
      });
    },

    enableSelectBtn: function() {
      this.$selectBtn.removeClass('disabled');
    },

    disableSelectBtn: function() {
      this.$selectBtn.addClass('disabled');
    },

    cancel: function() {
        this.hide();
    },

    selectIcon: function(){
      $(this.iconField).find('.iconpicker-icon').val(this.icon);
      $(this.iconField).find('.iconpicker-msg').removeClass('dolphiq-iconpicker--empty');
      $(this.iconField).find('.iconpicker-msg .iconpicker-preview').html('&#x'+(parseInt(this.icon).toString(16))+';');
      $(this.iconField).find('.iconpicker-msg .iconpicker-code').html((parseInt(this.icon).toString(16)));
      this.hide();
    },
  },
  {
    defaults: {
      resizable: true,
      hideOnSelect: true,
      onCancel: $.noop,
      onSelect: $.noop,
    }
  });
