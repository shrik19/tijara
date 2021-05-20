"use strict";

//$(document).ready(function(){

  if($(".view-vendor-modal").length)
  {
    $(".view-vendor-modal").on('click',function(){
      $("#currentView").val($(this).attr('id'));
      
      $(".view-vendor-modal").fireModal({
        title: 'Basket Details',
        body: $("#modal-basket-part-"+$("#currentView").val()),
        footerClass: 'bg-whitesmoke',
        closeButton: false,
        autoFocus: false,
      });
    });
  }

  

  if($(".view-basket-modal").length)
  {
    $(".view-basket-modal").on('click',function(){
      $("#currentView").val($(this).attr('id'));
      
      $(".view-basket-modal").fireModal({
        title: 'Basket Details',
        body: $("#modal-basket-part-"+$("#currentView").val()),
        footerClass: 'bg-whitesmoke',
        closeButton: false,
        autoFocus: false,
        buttons: [
          {
            text: 'Close',
            submit: false,
            class: 'btn btn-danger btn-shadow',
            handler: function(modal) {
              modal.modal('hide');
              modal.on('hidden.bs.modal', function() {
              });
            }
          }
        ]
      });
    });
  }
//});