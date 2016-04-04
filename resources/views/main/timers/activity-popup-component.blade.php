<script id="activity-popup-template" type="x-template">

<div
    v-show="showPopup"
    v-on:click="closePopup($event)"
    class="popup-outer"
>

    <div id="activity-popup" class="popup-inner">

        <div class="content">
            <form>

                <div>
                    <label for="selected-activity-name">Name</label>
                    <input v-model="selectedActivity.name" type="text" id="selected-activity-name" name="selected-activity-name" placeholder="name" class="form-control"/>
                </div>

                <div>
                    <label for="selected-activity-color">Color</label>
                    <input v-model="selectedActivity.color" type="text" id="selected-activity-color" name="selected-activity-color" placeholder="color" class="form-control"/>
                </div>

            </form>
        </div>

        <div class="buttons">
            <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
            <button v-on:click="deleteActivity()" class="btn btn-danger">Delete</button>
            <button v-on:click="updateActivity()" class="btn btn-success">Save</button>
        </div>

    </div>
</div>

</script>