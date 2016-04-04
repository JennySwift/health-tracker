<script id="timer-popup-template" type="x-template">

<div
    v-show="showPopup"
    v-on:click="closePopup($event)"
    class="popup-outer"
>

    <div id="timer-popup" class="popup-inner">

        <div class="buttons">
            <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
            <button v-on:click="deleteTimer()" class="btn btn-danger">Delete</button>
            <button v-on:click="updateTimer()" class="btn btn-success">Save</button>
        </div>

    </div>
</div>

</script>