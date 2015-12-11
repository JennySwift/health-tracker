<script type="text/ng-template" id="date-navigation-template">

    <h1 class="center">[[date.long]]</h1>

    <div id="date-navigation" class="margin-bottom">

        <div class="my-btn-group">
            <button type="button" id="last-week" class="my-btn fa fa-angle-double-left" ng-click="goToDate(-7)"></button>
            <button type="button" id="prev" class="my-btn fa fa-angle-left" ng-click="goToDate(-1)"></button>
        </div>

        <input ng-keyup="changeDate($event.keyCode)" type="text" placeholder="date" id="date" class="my-input">

        <button ng-click="today()" type="button" id="today" class="my-btn">
            <span class="hidden-xs">today</span>
            <span class="fa fa-star visible-xs"></span>
        </button>

        <div class="my-btn-group">
            <button type="button" id="next" class="my-btn fa fa-angle-right" ng-click="goToDate(1)"></button>
            <button type="button" id="next-week" class="my-btn fa fa-angle-double-right" ng-click="goToDate(7)"></button>
        </div>

    </div>

</script>


