var ExerciseUnitsPage = Vue.component('exercise-units-page', {
    template: '#exercise-units-page-template',
    data: function () {
        return {
            units: [],
            newUnit: {}
        };
    },
    components: {},
    methods: {
        /**
        *
        */
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseUnits', function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        insertUnit: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newUnit.name
            };

            this.$http.post('/api/exerciseUnits', data, function (response) {
                this.units.push(response.data);
                $.event.trigger('provide-feedback', ['Unit created', 'success']);
                //this.$broadcast('provide-feedback', 'Unit created', 'success');
                $.event.trigger('hide-loading');
                $("#create-new-exercise-unit").val("");
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param unit
         */
        deleteUnit: function (unit) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exerciseUnits/' + unit.id, function (response) {
                    this.units = _.without(this.units, unit);
                    $.event.trigger('provide-feedback', ['Unit deleted', 'success']);
                    //this.$broadcast('provide-feedback', 'Unit deleted', 'success');
                    $.event.trigger('hide-loading');

                    })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getUnits();
    }
});