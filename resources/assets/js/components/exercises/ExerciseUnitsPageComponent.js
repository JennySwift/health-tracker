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
            var data = {
                name: this.newUnit.name
            };

            HelpersRepository.post('/api/exerciseUnits', data, 'Unit added', function (response) {
                store.addExerciseUnit(response.data.data);
                $("#create-new-exercise-unit").val("");
                this.clearFields();
            }.bind(this));
        },

        /**
        *
        */
        deleteUnit: function (unit) {
            HelpersRepository.delete('/api/exerciseUnits/' + unit.id, 'Unit deleted', function (response) {
                store.deleteExerciseUnit(unit);
                this.showPopup = false;
                router.go(this.redirectTo);
            }.bind(this));
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        
    }
};