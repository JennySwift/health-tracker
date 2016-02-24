<script id="activities-page-template" type="x-template">

    <div id="activities">

        <h3>New activity</h3>

        <div id="new-activity">

            <div>
                <label for="new-activity-name">Name</label>
                <input v-model="newActivity.name" type="text" id="new-activity-name" name="new-activity-name" placeholder="name" class="form-control"/>
            </div>

            <div>
                <label for="new-activity-color">Color</label>
                <input v-model="newActivity.color" type="text" id="new-activity-color" name="new-activity-color" placeholder="color" class="form-control"/>
            </div>

            <div>
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

            <tr v-for="activity in activities" v-on:click="showEditActivity(activity)" class="activity">
                <td><span v-bind:style="{'background': activity.color}" class="label label-default">@{{ activity.name }}</span></td>
                <td>@{{ activity.color }}</td>
                <td>@{{ activity.totalMinutes | formatDuration }}</td>
            </tr>

        </table>

        <form v-show="editingActivity" id="selected-activity">

            <div>
                <label for="selected-activity-name">Name</label>
                <input v-model="selectedActivity.name" type="text" id="selected-activity-name" name="selected-activity-name" placeholder="name" class="form-control"/>
            </div>

            <div>
                <label for="selected-activity-color">Color</label>
                <input v-model="selectedActivity.color" type="text" id="selected-activity-color" name="selected-activity-color" placeholder="color" class="form-control"/>
            </div>

            <div>
                <button v-on:click="updateActivity(selectedActivity)" class="btn btn-success">Save</button>
                <button v-on:click="editingActivity = false" class="btn btn-default">Cancel</button>
                <button v-on:click="deleteActivity(selectedActivity)" class="btn btn-danger">Delete</button>
            </div>

        </form>

    </div>


</script>