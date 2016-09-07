<script id="exercise-entries-template" type="x-template">

<div>
    <entries-for-specific-exercise-and-date-and-unit-popup
            :date="date"
    >
    </entries-for-specific-exercise-and-date-and-unit-popup>

    <div>
        <table class="table table-bordered">
            <caption>exercise entries</caption>
            <tr>
                <th>Exercise</th>
                <th>Sets</th>
                <th>Total</th>
                <th>Add</th>
            </tr>

            <tr v-for="entry in exerciseEntries">
                <td
                        v-on:click="showEntriesForSpecificExerciseAndDateAndUnitPopup(entry)"
                        class="pointer"
                >
                    @{{ entry.exercise.data.name }}
                </td>
                <td
                        v-on:click="showEntriesForSpecificExerciseAndDateAndUnitPopup(entry)"
                        class="pointer"
                >
                    @{{ entry.sets }}
                </td>
                <td
                        v-on:click="showEntriesForSpecificExerciseAndDateAndUnitPopup(entry)"
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