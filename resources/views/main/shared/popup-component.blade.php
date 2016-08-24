<script id="popup-template" type="x-template">

    <div
            v-show="showPopup"
            transition="popup-outer"
            v-on:click="closePopup($event)"
            class="popup-outer animate"
    >

        <div
                v-show="showPopup"
                transition="popup-inner"
                :id="id"
                class="popup-inner scrollbar-container animate"
        >

            <div class="content">
                <slot name="content"></slot>
            </div>

            <div class="buttons">
                <slot name="buttons">
                    <button
                            v-on:click="showPopup = false"
                            v-link="{path: redirectTo}"
                            class="btn btn-default"
                    >
                        Cancel
                    </button>

                    <button
                            v-on:click="destroy()"
                            v-link="{path: redirectTo}"
                            class="btn btn-danger"
                    >
                        Delete
                    </button>

                    <button
                            v-on:click="update()"
                            v-link="{path: redirectTo}"
                            class="btn btn-success"
                    >
                        Save
                    </button>
                </slot>
            </div>

        </div>
    </div>

</script>