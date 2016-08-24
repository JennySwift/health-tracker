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
                store.add(response.data.data, 'exerciseUnits');
                this.clearFields();
            }.bind(this));
        },

        /**
         *
         */
        clearFields: function () {
            this.newUnit.name = '';
        },

        /**
        *
        */
        deleteUnit: function (unit) {
            HelpersRepository.delete('/api/exerciseUnits/' + unit.id, 'Unit deleted', function (response) {
                store.delete(unit, 'exerciseUnits');
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