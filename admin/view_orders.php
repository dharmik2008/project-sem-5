<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../users/admin_login.php');
    exit();
}
include '../users/db.php';

// Add email column to orders table if it doesn't exist
$check_column = mysqli_query($con, "SHOW COLUMNS FROM orders LIKE 'email'");
if (mysqli_num_rows($check_column) == 0) {
    mysqli_query($con, "ALTER TABLE orders ADD COLUMN email VARCHAR(255) DEFAULT NULL");
}

// Fetch all orders with user info if available
$orders = mysqli_query($con, "SELECT o.*, u.username, COALESCE(o.email, u.email) as email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.date DESC, o.id DESC");

// Check if query failed
if (!$orders) {
    die("Query failed: " . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/images/logo2.png">
    <style>
        :root {
            --primary-color: #8B4513;
            --secondary-color: #D2691E;
            --accent-color: #DEB887;
            --text-dark: #2C1810;
            --text-light: #8B7355;
            --bg-light: #FDF5E6;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-light);
            margin: 0;
            padding: 0;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
        }

        .nav-menu {
            padding: 20px 0;
        }

        .nav-item {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--accent-color);
            transform: translateX(5px);
        }

        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: var(--bg-light);
            color: var(--text-dark);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--accent-color);
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: var(--text-light);
        }

        .table tr:hover {
            background: var(--bg-light);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }

        /* Toggle Button for Mobile */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-coffee me-2"></i>Admin Panel</h2>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="admin_dashboard.php" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_coffee.php" class="nav-link">
                        <i class="fas fa-coffee"></i>
                        Coffee
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_tables.php" class="nav-link">
                        <i class="fas fa-table"></i>
                        Table
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view_orders.php" class="nav-link active">
                        <i class="fas fa-shopping-basket"></i>
                        Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view_users.php" class="nav-link">
                        <i class="fas fa-users"></i>
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage_cart.php" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        Cart
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view_bookings.php" class="nav-link">
                        <i class="fas fa-calendar-check"></i>
                        Booked Tables
                    </a>
                </li>
                <li class="nav-item">
                    <a href="view_messages.php" class="nav-link">
                        <i class="fas fa-comments"></i>
                        Messages
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1><i class="fas fa-shopping-basket me-3"></i>Customer Orders</h1>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Date</th>
                            <th>Invoice #</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($orders)): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['username'] ? htmlspecialchars($row['username']) : 'Guest' ?></td>
                            <td>
                                <?php if ($row['email']): ?>
                                    <i class="fas fa-envelope text-success me-1"></i>
                                    <span class="text-primary"><?= htmlspecialchars($row['email']) ?></span>
                                <?php else: ?>
                                    <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                    <span class="text-muted">No email</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td>₹<?= htmlspecialchars($row['price']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td>₹<?= htmlspecialchars($row['subtotal_amount']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['invoice_number']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Highlight current page in navigation
        const currentPage = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>