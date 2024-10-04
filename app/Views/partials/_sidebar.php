<?php if (session()->get('role') !== 'Sales'): ?>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('dashboard') ?>">
                    <i class="icon-grid menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <!-- Pipeline Menu -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#pipeline" aria-expanded="false" aria-controls="pipeline">
                    <i class="icon-layers menu-icon"></i>
                    <span class="menu-title">Pipeline</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="pipeline">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('prospect') ?>">Prospects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('milestone') ?>">Milestones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('financial') ?>">Financial</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('project') ?>">
                    <i class="icon-briefcase menu-icon"></i>
                    <span class="menu-title">Project List</span>
                </a>
            </li>

            <!-- Hanya Tampilkan Sales Manage dan Divisions Manage untuk Admin -->
            <?php if (session()->get('role') == 'Admin'): ?>
                <!-- Sales Management Menu -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#salesManagement" aria-expanded="false"
                        aria-controls="salesManagement">
                        <i class="ti-user menu-icon"></i>
                        <span class="menu-title">Sales Manage</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="salesManagement">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('sales') ?>">Sales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('pre-sales') ?>">Pre-Sales</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('division') ?>">
                        <i class="ti-layers menu-icon"></i>
                        <span class="menu-title">Divisions Manage</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Reports -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#reports" aria-expanded="false" aria-controls="reports">
                    <i class="ti-bar-chart menu-icon"></i>
                    <span class="menu-title">Reports</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="reports">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('reports/pipeline_summary') ?>">Pipeline Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('rkap') ?>">RKAP Targets</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
<?php endif; ?>

<!-- Sales Sidebar -->
<?php if (session()->get('role') == 'Sales'): ?>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('dashboard') ?>">
                    <i class="icon-grid menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#pipeline" aria-expanded="false" aria-controls="pipeline">
                    <i class="icon-layers menu-icon"></i>
                    <span class="menu-title">Pipeline</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="pipeline">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('prospect') ?>">Prospects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('milestone') ?>">Milestones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('financial') ?>">Financial</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('project') ?>">
                    <i class="icon-briefcase menu-icon"></i>
                    <span class="menu-title">Project List</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('performance') ?>">
                    <i class="ti-bar-chart menu-icon"></i>
                    <span class="menu-title">Monthly Performance</span>
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>