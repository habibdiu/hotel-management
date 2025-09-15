<!-- partial:partials/_sidebar.html -->
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ url('/') }}" class="sidebar-brand">
            H<span>OTEL</span>
        </a>
        <div class="sidebar-toggler ">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
                <li class="nav-item nav-category">Admin</li>
                <!--  Dashboard  -->
                <li class="nav-item {{ $data['active_menu'] == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link ">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="link-title">Dashboard</span>
                    </a>
                </li>
                <!--   Students   -->
                <li
                    class="nav-item {{ $data['active_menu'] == 'advocate_add' || $data['active_menu'] == 'advocate_edit' || $data['active_menu'] == 'advocate_list' ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#advocate" role="button" aria-expanded="false"
                        aria-controls="advocate">
                        <i class="fa-regular fa-user"></i>
                        <span class="link-title">Advocates Manage</span>
                        <i class="fa-solid fa-chevron-down link-arrow"></i>
                    </a>
                    <div class="collapse" id="advocate">
                        <ul class="nav sub-menu">
                            <li class="nav-item ">
                                <a href="{{ route('admin.advocate.add') }}"
                                    class="nav-link {{ $data['active_menu'] == 'advocate_add' ? 'active' : '' }}">Advocate
                                    Add</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.advocate.list') }}"
                                    class="nav-link {{ $data['active_menu'] == 'advocate_list' ? 'active' : '' }}">Advocate List</a>
                            </li>
                        </ul>
                    </div>
                </li>






                <!--   Messages   -->
                <li
                    class="nav-item {{ $data['active_menu'] == 'message_add' || $data['active_menu'] == 'message_edit' || $data['active_menu'] == 'message_list' ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#message" role="button" aria-expanded="false"
                        aria-controls="message">
                        <i class="fa-regular fa-user"></i>
                        <span class="link-title">Manage Messages</span>
                        <i class="fa-solid fa-chevron-down link-arrow"></i>
                    </a>
                    <div class="collapse" id="message">
                        <ul class="nav sub-menu">
                            <li class="nav-item ">
                                <a href="{{ route('admin.message.add') }}"
                                    class="nav-link {{ $data['active_menu'] == 'message_add' ? 'active' : '' }}">Message
                                    Add</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.message.list') }}"
                                    class="nav-link {{ $data['active_menu'] == 'message_list' ? 'active' : '' }}">Message List</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!--   Room Features   -->
                <li
                    class="nav-item {{ $data['active_menu'] == 'category' || $data['active_menu'] == 'subcategory' || $data['active_menu'] == 'room_type' ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#room_features" role="button" aria-expanded="false"
                        aria-controls="room_features">
                        <i class="fa-regular fa-user"></i>
                        <span class="link-title">Room Features</span>
                        <i class="fa-solid fa-chevron-down link-arrow"></i>
                    </a>
                    <div class="collapse" id="room_features">
                        <ul class="nav sub-menu">
                            <li class="nav-item ">
                                <a href="{{route('admin.room.type')}}"
                                    class="nav-link {{ $data['active_menu'] == 'room_type' ? 'active' : '' }}">Room Type
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="{{route('admin.room.category')}}"
                                    class="nav-link {{ $data['active_menu'] == 'category' ? 'active' : '' }}">
                                    Room Category
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a href="{{route('admin.room.subcategory')}}"
                                    class="nav-link {{ $data['active_menu'] == 'subcategory' ? 'active' : '' }}">
                                    Room Subcategory
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!--   Rooms   -->
                <li
                    class="nav-item {{ $data['active_menu'] == 'room_add' || $data['active_menu'] == 'room_edit' || $data['active_menu'] == 'room_list' ? 'active' : '' }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#room" role="button" aria-expanded="false"
                        aria-controls="room">
                        <i class="fa-regular fa-user"></i>
                        <span class="link-title">Rooms Manage</span>
                        <i class="fa-solid fa-chevron-down link-arrow"></i>
                    </a>
                    <div class="collapse" id="room">
                        <ul class="nav sub-menu">
                            <li class="nav-item ">
                                <a href="{{route('admin.room.add')}}"
                                    class="nav-link {{ $data['active_menu'] == 'room_add' ? 'active' : '' }}">Room
                                    Add</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.room.list')}}"
                                    class="nav-link {{ $data['active_menu'] == 'room_list' ? 'active' : '' }}">Room List</a>
                            </li>
                        </ul>
                    </div>
                </li>
                 
        </ul>
</nav>