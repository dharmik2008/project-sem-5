<?php
session_start();
include("../users/db.php");

if (!isset($_SESSION['admin'])) {
    header('Location: ../users/admin_login.php');
    exit();
}

// Handle cart item deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = $_POST['item_id'];
    
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: manage_cart.php");
    exit();
}

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['new_quantity'];
    
    if ($new_quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_quantity, $item_id);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: manage_cart.php");
    exit();
}

// Fetch all cart items with user information (use username for portability)
$query = "SELECT ci.*, u.username as user_name, u.email as user_email 
          FROM `cart_items` ci 
          LEFT JOIN `users` u ON ci.user_id = u.id 
          ORDER BY ci.created_at DESC";
$result = $conn->query($query);

// Calculate totals
$total_items = 0;
$total_value = 0;
$cart_items = [];
$dbError = $result === false ? $conn->error : null;
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_items += $row['quantity'];
        $total_value += ($row['product_price'] * $row['quantity']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cart - Admin Panel</title>
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

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: var(--text-light);
            font-size: 1rem;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .stat-card .stat-number {
            color: var(--primary-color);
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }

        .content-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .section-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bg-light);
        }

        .section-header h3 {
            color: var(--text-dark);
            margin: 0;
            font-size: 1.4rem;
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

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .alert {
            border-radius: 10px;
            border: none;
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

            .stats-container {
                grid-template-columns: 1fr;
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
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
                    <a href="view_orders.php" class="nav-link">
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
                    <a href="manage_cart.php" class="nav-link active">
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
                <h1><i class="fas fa-shopping-cart me-3"></i>Manage Cart Items</h1>
            </div>

            <?php if ($dbError): ?>
                <div class="alert alert-warning">
                    <strong>Database error:</strong> <?php echo htmlspecialchars($dbError); ?><br>
                    Ensure the `cart_items` table exists. You can run the SQL in `DATABASE/update_database.sql`.
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Cart Items</h3>
                    <p class="stat-number"><?php echo count($cart_items); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Quantity</h3>
                    <p class="stat-number"><?php echo $total_items; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Value</h3>
                    <p class="stat-number">₹<?php echo number_format($total_value, 2); ?></p>
                </div>
            </div>

            <!-- Cart Items Section -->
            <div class="content-section">
                <div class="section-header">
                    <h3><i class="fas fa-list me-2"></i>Cart Items</h3>
                </div>

                <?php if (!empty($cart_items)): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td><?php echo $item['id']; ?></td>
                                        <td>
                                            <?php if ($item['user_name']): ?>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($item['user_name']); ?></strong><br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($item['user_email']); ?></small>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">Guest User</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td>
                                            <?php
                                                $rawImage = $item['product_image'] ?? '';
                                                $imagePath = '';
                                                if (!empty($rawImage)) {
                                                    $trimmed = ltrim($rawImage, '/');
                                                    if (preg_match('#^https?://#i', $rawImage) || strpos($rawImage, '/') === 0) {
                                                        $imagePath = $rawImage; // absolute URL or root-relative
                                                    } elseif (strpos($trimmed, 'users/') === 0) {
                                                        $imagePath = '../' . $trimmed;
                                                    } elseif (strpos($trimmed, 'images/') === 0) {
                                                        $imagePath = '../users/' . $trimmed;
                                                    } else {
                                                        $imagePath = '../users/images/' . basename($trimmed);
                                                    }
                                                }
                                            ?>
                                            <?php if (!empty($imagePath)): ?>
                                                <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['product_name'] ?: 'Product'); ?>" class="product-image">
                                            <?php else: ?>
                                                <span class="text-muted">No image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>₹<?php echo number_format($item['product_price'], 2); ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                <div class="input-group input-group-sm" style="width: 100px;">
                                                    <input type="number" name="new_quantity" value="<?php echo $item['quantity']; ?>" 
                                                           min="1" class="form-control">
                                                    <button type="submit" name="update_quantity" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        <td>₹<?php echo number_format($item['product_price'] * $item['quantity'], 2); ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($item['created_at'])); ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" name="delete_item" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h5>No cart items found</h5>
                        <p>There are currently no items in any user's cart.</p>
                    </div>
                <?php endif; ?>
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
