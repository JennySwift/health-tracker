<script id="graphs-page-template" type="x-template">

    <div id="sleep-chart">

        <div id="sleep-chart-times">
            <div v-bind:style="{'bottom': 1 * 60 / 2 + 'px'}">1:00am</div>
            <div v-bind:style="{'bottom': 2 * 60 / 2 + 'px'}">2:00am</div>
            <div v-bind:style="{'bottom': 3 * 60 / 2 + 'px'}">3:00am</div>
            <div v-bind:style="{'bottom': 4 * 60 / 2 + 'px'}">4:00am</div>
            <div v-bind:style="{'bottom': 5 * 60 / 2 + 'px'}">5:00am</div>
            <div v-bind:style="{'bottom': 6 * 60 / 2 + 'px'}">6:00am</div>
            <div v-bind:style="{'bottom': 7 * 60 / 2 + 'px'}">7:00am</div>
            <div v-bind:style="{'bottom': 8 * 60 / 2 + 'px'}">8:00am</div>
            <div v-bind:style="{'bottom': 9 * 60 / 2 + 'px'}">9:00am</div>
            <div v-bind:style="{'bottom': 10 * 60 / 2 + 'px'}">10:00am</div>
            <div v-bind:style="{'bottom': 11 * 60 / 2 + 'px'}">11:00am</div>
            <div v-bind:style="{'bottom': 12 * 60 / 2 + 'px'}">12:00pm</div>
            <div v-bind:style="{'bottom': 13 * 60 / 2 + 'px'}">1:00pm</div>
            <div v-bind:style="{'bottom': 14 * 60 / 2 + 'px'}">2:00pm</div>
            <div v-bind:style="{'bottom': 15 * 60 / 2 + 'px'}">3:00pm</div>
            <div v-bind:style="{'bottom': 16 * 60 / 2 + 'px'}">4:00pm</div>
            <div v-bind:style="{'bottom': 17 * 60 / 2 + 'px'}">5:00pm</div>
            <div v-bind:style="{'bottom': 18 * 60 / 2 + 'px'}">6:00pm</div>
            <div v-bind:style="{'bottom': 19 * 60 / 2 + 'px'}">7:00pm</div>
            <div v-bind:style="{'bottom': 20 * 60 / 2 + 'px'}">8:00pm</div>
            <div v-bind:style="{'bottom': 21 * 60 / 2 + 'px'}">9:00pm</div>
            <div v-bind:style="{'bottom': 22 * 60 / 2 + 'px'}">10:00pm</div>
            <div v-bind:style="{'bottom': 23 * 60 / 2 + 'px'}">11:00pm</div>
            <div v-bind:style="{'bottom': 24 * 60 / 2 + 'px'}">12:00am</div>
        </div>

        <div id="sleep-chart-entries">
            <div v-for="date in timers" class="date-entries">
                <div v-for="timer in date">

                    <div
                            v-if="!timer.fakeStartPosition"
                            v-bind:style="{'bottom': timer.startPosition/ 2 + 'px', 'height': timer.startHeight / 2 + 'px', 'background': timer.color}"
                            class="time start">
                        <label v-bind:style="{'background': timer.color}" class="label">@{{ timer.start }}</label>
                    </div>

                    <div
                            v-if="!timer.fakeStartPosition"
                            v-bind:style="{'bottom': timer.finishPosition/ 2 + 'px'}"
                            class="time finish">
                        <label v-bind:style="{'background': timer.color}" class="label">@{{ timer.finish }}</label>
                    </div>

                    <div v-if="timer.fakeStartPosition || timer.fakeStartPosition === 0"
                         v-bind:style="{'bottom': timer.fakeStartPosition + 'px', 'height': timer.startHeight / 2 + 'px'}"
                         class="time start">
                        <label class="label label-danger">@{{ timer.fakeStart }}</label>
                    </div>

                </div>
                <div class="date">
                    <label class="label label-primary">@{{ date.day }}</label>
                    <label class="label label-primary">@{{ date.shortDate }}</label>
                </div>
            </div>
        </div>


    </div>

</script>