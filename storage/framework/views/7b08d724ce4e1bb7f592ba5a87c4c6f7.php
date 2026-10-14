<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'CRM'); ?> — <?php echo e(config('app.name')); ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/10x-theme.css')); ?>?v=<?php echo e(@filemtime(base_path('css/10x-theme.css'))); ?>">

    <style>
        :root {
            --sidebar-width: 240px;
            --topbar-height: 56px;
        }

        body {
            background: #f5f7fb;
        }

        /* Sidebar */
        .crm-sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: #1e293b;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: transform .25s ease;
        }

        .crm-sidebar .brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            font-weight: 700;
            color: #fff;
            font-size: 1.1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .crm-sidebar .nav-link {
            color: rgba(255, 255, 255, .7);
            padding: .55rem 1.25rem;
            border-radius: .375rem;
            margin: .1rem .75rem;
            font-size: .9rem;
            transition: background .15s, color .15s;
        }

        .crm-sidebar .nav-link:hover,
        .crm-sidebar .nav-link.active {
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        .crm-sidebar .nav-section {
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255, 255, 255, .35);
            padding: 1rem 1.25rem .3rem;
        }

        /* Main content */
        .crm-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .crm-topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            gap: .75rem;
        }

        .crm-content {
            padding: 1.75rem 1.5rem;
        }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: .75rem;
            transition: transform .15s, box-shadow .15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        /* Table */
        .table-crm th {
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #64748b;
            border-top: none;
        }

        .table-crm td {
            vertical-align: middle;
        }

        .table-crm tbody tr:hover {
            background: #f8fafc;
        }

        /* Filter bar */
        .filter-bar {
            background: #fff;
            border-radius: .75rem;
            border: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
        }

        /* Note timeline */
        .note-item {
            border-left: 3px solid #e2e8f0;
            padding-left: 1rem;
            margin-bottom: 1rem;
        }

        .note-item:hover {
            border-left-color: #0d6efd;
        }

        @media (max-width: 991px) {
            .crm-sidebar {
                transform: translateX(-100%);
            }

            .crm-sidebar.open {
                transform: translateX(0);
            }

            .crm-main {
                margin-left: 0;
            }
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>

    <!-- Sidebar -->
    <nav class="crm-sidebar">
        <div class="brand">
            <i class="bi bi-diagram-3-fill me-2 text-primary"></i>
            CRM
        </div>
        <div class="pt-2">
            <div class="nav-section">Records</div>
            <a href="<?php echo e(route('crm.leads.index')); ?>"
                class="nav-link <?php echo e(request()->routeIs('crm.leads.*') ? 'active' : ''); ?>">
                <i class="bi bi-funnel me-2"></i>Leads
            </a>
            <a href="<?php echo e(route('crm.deals.index')); ?>"
                class="nav-link <?php echo e(request()->routeIs('crm.deals.*') ? 'active' : ''); ?>">
                <i class="bi bi-briefcase me-2"></i>Deals
            </a>
            <a href="<?php echo e(route('crm.activities.index')); ?>"
                class="nav-link <?php echo e(request()->routeIs('crm.activities.*') ? 'active' : ''); ?>">
                <i class="bi bi-calendar-check me-2"></i>Activities
            </a>

            <div class="nav-section">Import / Export</div>
            <a href="<?php echo e(route('crm.leads.import.form')); ?>" class="nav-link">
                <i class="bi bi-upload me-2"></i>Import Leads
            </a>
            <a href="<?php echo e(route('crm.deals.import.form')); ?>" class="nav-link">
                <i class="bi bi-upload me-2"></i>Import Deals
            </a>
        </div>
    </nav>

    <!-- Main -->
    <div class="crm-main">
        <!-- Topbar -->
        <div class="crm-topbar">
            <button class="btn btn-sm btn-outline-secondary d-lg-none"
                onclick="document.querySelector('.crm-sidebar').classList.toggle('open')">
                <i class="bi bi-list"></i>
            </button>
            <nav aria-label="breadcrumb" class="mb-0 flex-grow-1">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.leads.index')); ?>">CRM</a></li>
                    <?php echo $__env->yieldContent('breadcrumb'); ?>
                </ol>
            </nav>
            <?php echo $__env->yieldContent('topbar-actions'); ?>
        </div>

        <div class="crm-content">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/layouts/crm.blade.php ENDPATH**/ ?>