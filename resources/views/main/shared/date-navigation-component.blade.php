<script id="date-navigation-template" type="x-template">

<div id="date-navigation-container">

    <h1 class="center">@{{ date.long }}</h1>

    <div id="date-navigation" class="margin-bottom">

        <div class="my-btn-group">
            <button
                type="button"
                id="last-week"
                class="my-btn fa fa-angle-double-left"
                v-on:click="goToDate(-7)"
            >
            </button>

            <button
                type="button"
                id="prev"
                class="my-btn fa fa-angle-left"
                v-on:click="goToDate(-1)"
            >
            </button>
        </div>

        <input
            v-on:keyup.13="changeDate()"
            type="text"
            placeholder="date"
            id="date"
            class="my-input"
        >

        <button
            v-on:click="goToToday()"
            type="button"
            id="today"
            class="my-btn"
        >
            <span class="hidden-xs">today</span>
            <span class="fa fa-star visible-xs"></span>
        </button>

        <div class="my-btn-group">
            <button
                type="button"
                id="next"
                class="my-btn fa fa-angle-right"
                v-on:click="goToDate(1)"
            >
            </button>

            <button
                type="button"
                id="next-week"
                class="my-btn fa fa-angle-double-right"
                v-on:click="goToDate(7)"
            >
            </button>
        </div>

    </div>



</div>

</script>