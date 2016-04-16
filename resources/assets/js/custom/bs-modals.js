var bsModal = {
    preventClose: function (modal) {
        modal.data('bs.modal').options.backdrop = 'static';
        modal.data('bs.modal').options.keyboard = false;
        modal.find('.modal-close-btn').hide();
    },

    allowClose: function (modal) {
        modal.data('bs.modal').options.backdrop = true;
        modal.data('bs.modal').options.keyboard = true;
        modal.find('.modal-close-btn').show();
    }
};
