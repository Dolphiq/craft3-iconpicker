/**
 * Created by lucasweijers on 16-08-17.
 */
Craft.IconpickerSelectInput = Craft.BaseElementSelectInput.extend({
  iconpickerField_modals: [],
  init: function (toggleId) {
    var self = this;
    $(toggleId).on('click', function () {
      var p = $(this).parent();
      if (p.data('modal-id') !== undefined) {
        self.iconpickerField_modals[p.data('modal-id')].show();
      } else {
        var m = p.find('.iconpickerField_modal');
        var modal = new Craft.IconpickerModal(m, p);
        self.iconpickerField_modals.push(modal);
        p.data('modal-id', self.iconpickerField_modals.length - 1);
      }
    });

    $('document').on('click', '.locationField_modal_close', function () {
      Garnish.Modal.visibleModal.hide();
    });
  }
});