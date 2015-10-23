
<ul id="navbar" style="z-index:1000">
    <li id="menu-dropdown" class="dropdown">
        <a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown"><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="/auth/logout">logout</a></li>
        </ul>
    </li>
    
    <!-- <li class="desktop"><a ng-click="changeTab('entries')" href="">home</a></li>
    <li class="iphone"><a ng-click="changeTab('food_entries')" href="">food log</a></li>
    <li class="iphone"><a ng-click="changeTab('exercise_entries')" href="">exercise log</a></li> -->

    <li><a href="/">entries</a></li>

    <li id="menu-dropdown" class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span>foods</span>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="/foods">Foods</a></li>
            <li><a href="/recipes">Recipes</a></li>
            <li><a href="/food-units">Units</a></li>
        </ul>
    </li>

    <li id="menu-dropdown" class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span>exercises</span>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="/exercises">Exercises</a></li>
            <li><a href="/series">Series</a></li>
            <li><a href="/workouts">Workouts</a></li>
            <li><a href="/exercise-tags">Tags</a></li>
            <li><a href="/exercise-units">Units</a></li>
        </ul>
    </li>

    <li><a href="/journal">journal</a></li>

    <li><a href="#" id="search_button" class="location_button fa fa-search"></a></li>
</ul>

<feedback-directive></feedback-directive>
@include('templates.loading')

