module.exports = {
    data: function () {
        return {
            // showLoading: false
            shared: store.state
        };
    },
    template: "#loading-template",
    computed: {
        loading: function () {
          return this.shared.loading;
        }
    },
    props: [
        //'showLoading'
    ],
    methods: {
        // listen: function () {
        //     var that = this;
        //     $(document).on('show-loading', function (event, message, type) {
        //         that.showLoading = true;
        //     });
        //     $(document).on('hide-loading', function (event, message, type) {
        //         that.showLoading = false;
        //     });
        // }
    },
    ready: function () {
        // this.listen();
    }
};