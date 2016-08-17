var ExercisesRepository = {

    /**
     *
     * @param exercise
     * @returns {{name: *, description: *, priority: *, step_number: *, default_quantity: *, target: *, program_id: *, series_id: *, default_unit_id: *}}
     */
    setData: function (exercise) {
        var data = {
            name: exercise.name,
            description: exercise.description,
            priority: exercise.priority,
            step_number: exercise.stepNumber,
            default_quantity: exercise.defaultQuantity,
            target: exercise.target,
            program_id: exercise.program.id,
            series_id: exercise.series.id,
            stretch: HelpersRepository.convertBooleanToInteger(exercise.stretch)
        };

        if (exercise.defaultUnit.data) {
            data.default_unit_id = exercise.defaultUnit.data.id;
        }
        else {
            data.default_unit_id = exercise.defaultUnit.id;
        }

        return data;
    }
};