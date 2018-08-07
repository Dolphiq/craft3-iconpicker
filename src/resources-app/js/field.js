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

  // Remove the selected icon
  $(document).on('click', '.iconpickerField_removeicon', function(){
    var p = $(this).parent();
    $(p).find('.iconpicker-icon').val('');
    $(p).find('.iconpicker-msg .iconpicker-preview').html('');
    $(p).find('.iconpicker-msg .iconpicker-code').html('');
    $(p).find('.iconpicker-msg').addClass('dolphiq-iconpicker--empty');
    $(this).addClass('dolphiq-iconpicker--empty');
  });
});
