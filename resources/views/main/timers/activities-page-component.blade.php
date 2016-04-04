<script id="activities-page-template" type="x-template">

    <div id="activities">

        <activity-popup
            :activities.sync="activities"
        >
        </activity-popup>

        <h3>New activity</h3>

        <div id="new-activity">

            <div class="form-group">
                <label for="new-activity-name">Name</label>
                <input
                    v-model="newActivity.name"
                    v-on:keyup.13="insertActivity()"
                    type="text"
                    id="new-activity-name"
                    name="new-activity-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="new-activity-color">Color</label>
                <input
                    v-model="newActivity.color"
                    v-on:keyup.13="insertActivity()"
                    type="text"
                    id="new-activity-color"
                    name="new-activity-color"
                    placeholder="color"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <button v-on:click="insertActivity()" class="btn btn-success">Save</button>
            </div>

        </div>

        <h3>Activities</h3>

        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Colour</th>
                <th>Total duration</th>
            </tr>

            <tr v-for="activity in activities" v-on:click="showActivityPopup(activity)" class="activity">
                <td class="activity-column"><span v-bind:style="{'background': activity.color}" class="label label-default">@{{ activity.name }}</span></td>
                <td>@{{ activity.color }}</td>
                <td>@{{ activity.totalMinutes | formatDuration }}</td>
            </tr>

        </table>

    </div>


</script>