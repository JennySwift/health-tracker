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
                @{{ entry.exercise.name }}
            </td>
            <td
                v-on:click="getSpecificExerciseEntries(entry)"
                class="pointer"
            >
                @{{ entry.exercise.description }}
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
                    v-if="entry.unit.id === entry.exercise.defaultUnit.id"
                    v-on:click="insertExerciseSet(entry.exercise)"
                    class="btn-xs">
                    <i class="fa fa-plus"></i> @{{ entry.exercise.defaultQuantity }} @{{ entry.exercise.defaultUnit.name }}
                </button>
            </td>
        </tr>
    </table>
</div>