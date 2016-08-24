<script id="activity-popup-template" type="x-template">

    <div id="activity-popup">

        <popup
                :show-popup.sync="showPopup"
                id="activity-popup"
                :redirect-to="redirectTo"
                :update="updateActivity"
                :destroy="deleteActivity"
        >
            <div slot="content">
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
        </popup>

    </div>


</script>