
<ul id="navbar" style="z-index:1000">
    <li id="menu-dropdown" class="dropdown">
        <a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown"><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a ng-click="changeTab('units')" href="">units</a></li>
            <li><a href="/auth/logout">Logout <?php echo Auth::user()->name; ?></a></li>
            <li><a href="#" style="cursor:default">branch:master</a></li>
            <li><a href="credits.php">credits</a></li>
        </ul>
    </li>
    
    <li class="desktop"><a ng-click="changeTab('entries')" href="">home</a></li>
    <li class="iphone"><a ng-click="changeTab('food_entries')" href="">food log</a></li>
    <li class="iphone"><a ng-click="changeTab('exercise_entries')" href="">exercise log</a></li>

    <li>
        <a ng-click="changeTab('foods')" href="">
            <img src="img/apple.svg" alt="" width="14">
        </a>
    </li>
    <li><a ng-click="changeTab('exercises')" href="" class="fa fa-heart"></a></li>
    <li><a ng-click="changeTab('journal')" href="" class="fa fa-pencil"></a></li>
    
    <li><a href="#" id="search_button" class="location_button fa fa-search"></a></li>
</ul>

