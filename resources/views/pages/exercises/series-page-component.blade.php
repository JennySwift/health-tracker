<script id="series-page-template" type="x-template">

    <div id="exercise-series">

        <series-history-popup
                :exercise-series-history="exerciseSeriesHistory"
                :selected-series="selectedSeries"
        >
        </series-history-popup>

        <series-popup
                :selected-series="selectedSeries"
                :exercise-series.sync="exerciseSeries"
        >
        </series-popup>

        <div>
            @include('pages.exercises.series-top-row')
            <div class="series-exercises-container">

                <div>
                    <table id="series-table" class="table table-bordered">
                        <tr>
                            <th>Series</th>
                            <th>Days ago</th>
                            <th>Priority</th>
                        </tr>
                        <tr
                            v-for="series in exerciseSeries | filterSeries"
                            v-bind:class="{'selected': series.id === selectedSeries.id}"
                        >
                            <td class="actions">
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        @{{ series.name }}
                                        <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu">
                                        <li>
                                            <a
                                                v-on:click="getExercisesInSeries(series)"
                                            >
                                                Exercises
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                v-on:click="getExerciseSeriesHistory(series)"
                                            >
                                                History
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                v-on:click="showExerciseSeriesPopup(series)"
                                            >
                                                Edit
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>

                            <td v-on:click="getExercisesInSeries(series)" class="name">@{{ series.lastDone }}</td>
                            <td>@{{ series.priority }}</td>
                        </tr>
                    </table>
                </div>

                <series-exercises
                        :selected-series="selectedSeries"
                        :priority-filter="priorityFilter"
                        :units="units"
                        :programs="programs"
                        :exercise-series="exerciseSeries"
                >
                </series-exercises>
            </div>


        </div>

    </div>


</script>