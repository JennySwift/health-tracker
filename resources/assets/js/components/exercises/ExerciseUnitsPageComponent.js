module.exports = {
    template: '#exercise-units-page-template',
    data: function () {
        return {
            newUnit: {},
            shared: store.state
        };
    },
    computed: {
        units: function () {
          return this.shared.exerciseUnits;
        }
    },
    components: {},
    methods: {
        /**
         *
         */
        insertUnit: function () {
            store.showLoading();
            var data = {
                name: this.newUnit.name
            };

            this.$http.post('/api/exerciseUnits', data).then(function (response) {
                store.addExerciseUnit(response.data.data);
                $.event.trigger('provide-feedback', ['Unit created', 'success']);
                //this.$broadcast('provide-feedback', 'Unit created', 'success');
                store.hideLoading();
                $("#create-new-exercise-unit").val("");
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param unit
         */
        deleteUnit: function (unit) {
            if (confirm("Are you sure?")) {
                store.showLoading();
                this.$http.delete('/api/exerciseUnits/' + unit.id).then(function (response) {
                    store.deleteExerciseUnit(unit);
                    $.event.trigger('provide-feedback', ['Unit deleted', 'success']);
                    //this.$broadcast('provide-feedback', 'Unit deleted', 'success');
                    store.hideLoading();

                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        
    }
};