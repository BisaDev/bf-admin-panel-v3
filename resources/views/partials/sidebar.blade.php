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

                <!--li class="has_sub">
                    <a href="#" class="waves-effect {{ (Request::is('meetups/*') || Request::is('activity_buckets/*'))? 'active' : '' }}">
                        <i class="ti-calendar"></i><span> Meetups </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ Request::is('meetups/*')? 'active' : '' }}"><a href="{{ route('meetups.index') }}">Meetups</a></li>
                        <li class="{{ Request::is('activity_buckets/*')? 'active' : '' }}"><a href="{{ route('activity_buckets.index') }}">Activity Buckets</a></li>
                    </ul>
                </li-->

                <!--li>
                    <a href="{{ route('quizzes.index') }}" class="waves-effect {{ Request::is('quizzes/*')? 'active' : '' }}">
                        <i class="ti-pencil-alt"></i><span> Quizzes </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('questions.index') }}" class="waves-effect {{ Request::is('questions/*')? 'active' : '' }}">
                        <i class="ti-check-box"></i><span> Questions </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('minigames.index') }}" class="waves-effect {{ Request::is('minigames/*')? 'active' : '' }}">
                        <i class="ti-game"></i><span> Minigames </span>
                    </a>
                </li-->

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

                <!--li>
                    <a href="{{ route('grade_levels.index') }}" class="waves-effect {{ (Request::is('grade_levels/*') || Request::is('subjects/*') || Request::is('topics/*'))? 'active' : '' }}">
                        <i class="ti-bookmark-alt"></i><span> Academic Content </span>
                    </a>
                </li-->

                <li class="has_sub">
                    <a href="#" class="waves-effect {{ (Request::is('sat_exams/*') || Request::is('sat_results/*'))? 'active' : '' }}">
                        <i class="ti-write"></i><span> Exam Prep </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ Request::is('sat_exams/*')? 'active' : '' }}"><a href="{{ route('exams.index') }}">Exams</a></li>
                        <li class="{{ Request::is('exams.logs')? 'active' : '' }}"><a href="{{ route('exams.logs') }}">Logs</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect">
                        <i class="ti-tag"></i><span> Tagging Tool </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li class="{{ Request::is('tagging_tool/*')? 'active' : '' }}"><a href="{{ route('taggingtool') }}">Dashboard</a></li>
                        <li class="{{ Request::is('tagging_subject.show')? 'active' : '' }}"><a href="{{ route('taggingsubjects.index') }}">Manage subjects</a></li>
                    </ul>
                </li>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
