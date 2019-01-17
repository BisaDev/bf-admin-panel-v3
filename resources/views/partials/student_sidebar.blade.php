<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{ route('student_dashboard') }}" class="waves-effect">
                        <i class="ti-home"></i><span> Dashboard </span>
                    </a>
                </li>

                <li class="has_sub">
                    <a href="#" data-toggle="modal" data-target="#take-practice-exam" class="waves-effect {{ Request::is('/*') ? '' : 'input-file' }}">
                        <i class="ti-pencil-alt"></i><span> Take Practice Exam </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('answer_sheet.analytics') }}" class="waves-effect">
                        <i class="ti-bar-chart"></i><span> Analytics </span>
                    </a>
                </li>

            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>