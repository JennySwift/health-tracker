module.exports = {
    template: '#popup-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {
        /**
         *
         */
        closePopup: function ($event) {
            if ($($event.target).hasClass('popup-outer')) {
                this.showPopup = false;
                router.go(this.redirectTo);
            }
        },
    },
    props: [
        'showPopup',
        'id',
        'redirectTo',
        'update',
        'destroy'
    ],
    ready: function () {
        // HelpersRepository.scrollbars();
    }
};