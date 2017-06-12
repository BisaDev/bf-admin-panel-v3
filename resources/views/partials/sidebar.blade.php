<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="ti-home"></i><span> Dashboard </span>
                    </a>
                </li>

                <li><a href="#" class="waves-effect"><i class="ti-calendar"></i><span> Meetups </span> </a></li>

                <li><a href="#" class="waves-effect"><i class="ti-pencil-alt"></i><span> Quizzes </span></a></li>

                <li>
                    <a href="{{ route('questions.index') }}" class="waves-effect {{ Request::is('questions/*')? 'active' : '' }}">
                        <i class="ti-check-box"></i><span> Questions </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('minigames.index') }}" class="waves-effect {{ Request::is('minigames/*')? 'active' : '' }}">
                        <i class="ti-game"></i><span> Minigames </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('students.index') }}" class="waves-effect {{ (Request::is('students/*') || Request::is('family_members/*'))? 'active' : '' }}">
                        <i class="ti-user"></i><span> Students </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('employees.index') }}" class="waves-effect {{ Request::is('employees/*')? 'active' : '' }}">
                        <i class="ti-user"></i><span> Employees </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('locations.index') }}" class="waves-effect {{ (Request::is('locations/*') || Request::is('rooms/*'))? 'active' : '' }}">
                        <i class="ti-location-pin"></i><span> Facilities </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('grade_levels.index') }}" class="waves-effect {{ (Request::is('grade_levels/*') || Request::is('subjects/*') || Request::is('topics/*'))? 'active' : '' }}">
                        <i class="ti-bookmark-alt"></i><span> Academic Content </span>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>