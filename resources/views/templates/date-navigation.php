<div>
	<h1 class="row center">{{date.long}}</h1>
	
	<div class="row margin-bottom">

		 <div class="flex-parent col-xs-12 col-sm-8 col-sm-offset-2">

		 	<div class="my-btn-group flex-child flex-grow-1 min-width-md">
		 		<button type="button" id="last-week" class="my-btn fa fa-angle-double-left" ng-click="goToDate(-7)"></button>
		 		<button type="button" id="prev" class="my-btn fa fa-angle-left" ng-click="goToDate(-1)"></button>	
		 	</div>

		 	<input ng-keyup="changeDate($event.keyCode)" type="text" placeholder="date" id="date" class="flex-grow-2 my-input">
		 	
		 	<button ng-click="today()" type="button" id="today" class="flex-grow-1 max-width-lg my-btn">
		 		<span class="hidden-xs">today</span>
		 		<span class="fa fa-star visible-xs"></span>
		 	</button>

		 	<div class="my-btn-group flex-grow-1 min-width-md">
		 		<button type="button" id="next" class="my-btn fa fa-angle-right" ng-click="goToDate(1)"></button>
		 		<button type="button" id="next-week" class="my-btn fa fa-angle-double-right" ng-click="goToDate(7)"></button>
		 	</div>

		 </div>

	</div>	<!-- .row -->
</div>


