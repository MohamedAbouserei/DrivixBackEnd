<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="background-image: linear-gradient(180deg,#4d4d4d 10%,#000000 100%) !important;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/Admin">
        <div class="">
            <img src="/imgs/Logo-yello.png" class="img-fluid" width="60px" height="60px">
        </div>
        <div class="sidebar-brand-text mx-2" style="font-size: 14px">Dashboard</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/Admin">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Operations
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-gas-pump"></i>
            <span>Gas Station</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/gasStation">  <i class="fas fa-fw fa-cogs"></i> &nbsp; Manage</a>
                <a class="collapse-item" href="/AddGasStation"><i class="fas fa-fw fa-plus-square"></i> Add New</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTree" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fab fa-product-hunt"></i>
            <span>Product</span>
        </a>
        <div id="collapseTree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/manage-products">   <i class="fas fa-fw fa-cogs"></i> &nbsp; Manage</a>
            </div>
        </div>
    </li>



    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-car-crash"></i>
            <span>Roles</span>
        </a>
        <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/manage-roles">   <i class="fas fa-fw fa-cogs"></i> &nbsp; Manage</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    @if(Auth::user()->type === 1)
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsefive" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-sliders-h"></i>
            <span>General Settings</span>
        </a>
        <div id="collapsefive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/site-setting">   <i class="fas fa-fw fa-cogs"></i> &nbsp; Manage</a>
            </div>
        </div>
    </li>
    @endif

<!-- Nav Item - Pages Collapse Menu -->
    @if(Auth::user()->type === 1)
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse8" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-chart-bar"></i>
                <span>Statistics</span>
            </a>
            <div id="collapse8" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/stat">  <i class="fas fa-file-signature"></i> &nbsp; Generate Report</a>
                </div>
            </div>
        </li>
    @endif


    <!-- Nav Item - Pages Collapse Menu -->
    @if(Auth::user()->type === 1)
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesixr" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-users-cog"></i>
            <span>Supervisors</span>
        </a>
        <div id="collapsesixr" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/manage-supervisors">   <i class="fas fa-fw fa-cogs"></i> &nbsp; Manage</a>
                <a class="collapse-item" href="/AddSupervisor">  <i class="fas fa-fw fa-plus-square"></i> &nbsp; Add New</a>
            </div>
        </div>
    </li>
    @endif


    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse7" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-mail-bulk"></i>
            <span>Mails</span>
        </a>
        <div id="collapse7" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/manage-mails">   <i class="fas fa-fw fa-cogs"></i> &nbsp; Manage</a>
                <a class="collapse-item" href="/AddMail">  <i class="fas fa-fw fa-plus-square"></i> &nbsp; Add New</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
