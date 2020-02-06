<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none btn-light rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">



        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter {{ (((count(getMyUnseenMails()) == 0))? 'd-none' : '') }}"> {{ count(getMyUnseenMails()) }} </span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header bg-dark border-0">
                    Message Center
                </h6>
                @foreach( getmyMails() as $mail)
                <a class="dropdown-item d-flex align-items-center {{($mail->seen === 0)? 'not-shown' : ''}}" href="/getMail/{{ $mail->id }}">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="{{ getUserImage($mail->from_id) }}" alt="">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate">{{ $mail->title }}</div>
                        <div class="small text-gray-500">{{ $mail->message }}</div>
                    </div>
                </a>
                @endforeach
                <a class="dropdown-item text-center small text-gray-500" href="/manage-mails">Read More Messages</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Hossam Hassan</span>
                <img class="img-profile rounded-circle" src="/imgs/users/default.png">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fab fa-internet-explorer mr-2 text-gray-400"></i>
                     Website
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-fw fa-tachometer-alt mr-2 text-gray-400"></i>
                    Dashboard
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal" href="#">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->
