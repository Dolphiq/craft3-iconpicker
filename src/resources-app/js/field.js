/**
 * Created by lucasweijers on 16-08-17.
 */
$(function() {

  var iconpickerField_modals = [];

  $(document).on('click', '.iconpickerField_modaltoggle', function(){
    var p = $(this).parent();
    if(p.data('modal-id') !== undefined){
      iconpickerField_modals[p.data('modal-id')].show();
    }else{
      var m = p.find('.iconpickerField_modal');
      var modal = new Craft.IconpickerModal(m, p);
      iconpickerField_modals.push(modal);
      p.data('modal-id', iconpickerField_modals.length -1);
    }
  });

  // Close visible modal when clicking on close button
  $(document).on('click', '.locationField_modal_close', function(){
    Garnish.Modal.visibleModal.hide();
  });
});
