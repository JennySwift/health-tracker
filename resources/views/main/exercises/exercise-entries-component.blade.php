<script id="exercise-entries-template" type="x-template">

<div>
    <specific-exercise-entries-popup></specific-exercise-entries-popup>

    <div>
        <table class="table table-bordered">
            <caption>exercise entries</caption>
            <tr>
                <th>exercise</th>
                <th>description</th>
                <th>sets</th>
                <th>total</th>
                <th></th>
            </tr>

            <tr v-for="entry in exerciseEntries">
                <td
                        v-on:click="getSpecificExerciseEntries(entry)"
                        class="pointer"
                >
                    @{{ entry.exercise.data.name }}
                </td>
                <td
                        v-on:click="getSpecificExerciseEntries(entry)"
                        class="pointer"
                >
                    @{{ entry.exercise.data.description }}
                </td>
                <td
                        v-on:click="getSpecificExerciseEntries(entry)"
                        class="pointer"
                >
                    @{{ entry.sets }}
                </td>
                <td
                        v-on:click="getSpecificExerciseEntries(entry)"
                        class="pointer"
                >
                    @{{ entry.total }} @{{ entry.unit.name }}
                </td>
                <td>
                    <button
                            v-if="entry.exercise.data.defaultUnit && entry.unit.id === entry.exercise.data.defaultUnit.data.id"
                            v-on:click="insertExerciseSet(entry.exercise)"
                            class="btn-xs">
                        <i class="fa fa-plus"></i> @{{ entry.exercise.data.defaultQuantity }} @{{ entry.exercise.data.defaultUnit.data.name }}
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>

</script>