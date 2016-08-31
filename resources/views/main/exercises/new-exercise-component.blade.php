<script id="new-exercise-template" type="x-template">

    <div v-show="showNewExerciseFields" class="new-exercise margin-bottom">

        <div class="flex">
            <div>
                <div class="form-group">
                    <label for="new-exercise-name">Name</label>
                    <input
                            v-model="newExercise.name"
                            v-on:keyup.13="insertExercise($event.keyCode)"
                            type="text"
                            id="new-exercise-name"
                            name="new-exercise-name"
                            placeholder="name"
                            class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="new-exercise-description">Description</label>
                    <input
                            v-model="newExercise.description"
                            v-on:keyup.13="insertExercise($event.keyCode)"
                            type="text"
                            id="new-exercise-description"
                            name="new-exercise-description"
                            placeholder="description"
                            class="form-control"
                    >
                </div>

                <div class="step-number form-group">
                    <label for="new-exercise-step-number">Step Number</label>
                    <input
                            v-model="newExercise.stepNumber"
                            v-on:keyup.13="insertExercise($event.keyCode)"
                            type="text"
                            id="new-exercise-step-number"
                            name="new-exercise-step-number"
                            placeholder="step number"
                            class="form-control"
                    >
                </div>

                <div class="priority form-group">
                    <label for="new-exercise-priority">Priority</label>
                    <input
                            v-model="newExercise.priority"
                            v-on:keyup.13="insertExercise($event.keyCode)"
                            type="text"
                            id="new-exercise-priority"
                            name="new-exercise-priority"
                            placeholder="priority"
                            class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="new-exercise-target">Target</label>
                    <input
                            v-model="newExercise.target"
                            v-on:keyup.13="insertExercise($event.keyCode)"
                            type="text"
                            id="new-exercise-target"
                            name="new-exercise-target"
                            placeholder="target"
                            class="form-control"
                    >
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="new-exercise-program">Program</label>

                    <select
                            v-model="newExercise.program"
                            id="new-exercise-program"
                            class="form-control"
                    >
                        <option
                                v-for="program in programs"
                                v-bind:value="program"
                        >
                            @{{ program.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="new-exercise-series">Series</label>

                    <select
                            v-model="newExercise.series"
                            id="new-exercise-series"
                            class="form-control"
                    >
                        <option
                                v-for="series in exerciseSeries"
                                v-bind:value="series"
                        >
                            @{{ series.name }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="new-exercise-default-unit">DefaultUnit</label>

                    <select
                            v-model="newExercise.defaultUnit"
                            id="new-exercise-default-unit"
                            class="form-control"
                    >
                        <option
                                v-for="unit in units"
                                v-bind:value="unit"
                        >
                            @{{ unit.name }}
                        </option>
                    </select>
                </div>

                <div class="default-quantity form-group">
                    <label for="new-exercise-step-number">Default Quantity</label>
                    <input
                            v-model="newExercise.defaultQuantity"
                            v-on:keyup.13="insertExercise($event.keyCode)"
                            type="text"
                            id="new-exercise-default-quantity"
                            name="new-exercise-default-quantity"
                            placeholder="default quantity"
                            class="form-control"
                    >
                </div>

                <div class="form-group">
                    <div class="form-group">
                        <label for="new-exercise-frequency">Frequency</label>
                        <input
                            v-model="newExercise.frequency"
                            v-on:keyup.13="insertExercise($event.keyCode)"
                            type="text"
                            id="new-exercise-frequency"
                            name="new-exercise-frequency"
                            placeholder="frequency"
                            class="form-control"
                        >
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group flex">
            <button
                    v-on:click="showNewExerciseFields = false"
                    class="btn btn-default"
            >
                Close
            </button>
            <button
                    v-on:click="insertExercise(13)"
                    class="btn btn-success"
            >
                Add exercise
            </button>
        </div>

    </div>

</script>
