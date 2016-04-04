var HelpersRepository = {

    /**
     *
     * @param response
     */
    handleResponseError: function (response) {
        $.event.trigger('response-error', [response]);
        $.event.trigger('hide-loading');
    },

    /**
     *
     */
    closePopup: function ($event, that) {
        if ($event.target.className === 'popup-outer') {
            that.showPopup = false;
        }
    },
};