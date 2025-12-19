<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../users/admin_login.php');
    exit();
}
include '../users/db.php';

// Handle add coffee
if (isset($_POST['add_coffee'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = floatval($_POST['price']);
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../users/images/' . $image);
    }
    $sql = "INSERT INTO coffees (name, price, image) VALUES ('$name', '$price', '$image')";
    mysqli_query($con, $sql);
    header('Location: manage_coffee.php');
    exit();
}

// Handle delete coffee
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM coffees WHERE id=$id";
    mysqli_query($con, $sql);
    header('Location: manage_coffee.php');
    exit();
}

// Handle edit coffee
if (isset($_POST['edit_coffee'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $price = floatval($_POST['price']);
    $image = $_POST['old_image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], '../users/images/' . $image);
    }
    $sql = "UPDATE coffees SET name='$name', price='$price', image='$image' WHERE id=$id";
    mysqli_query($con, $sql);
    header('Location: manage_coffee.php');
    exit();
}

// Fetch all coffees
$coffees = mysqli_query($con, "SELECT * FROM coffees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coffee - Admin Panel</title>
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

        .content-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
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
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
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

        .form-section {
            margin-top: 40px;
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
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
                    <a href="manage_coffee.php" class="nav-link active">
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
                <h1><i class="fas fa-coffee me-3"></i>Manage Coffee Products</h1>
            </div>

            <!-- Coffee Products Section -->
            <div class="content-section">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Actions</th>
                                <th>Save/Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($coffees)): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td>₹<?= htmlspecialchars($row['price']) ?></td>
                                <td>
                                    <?php if ($row['image']): ?>
                                        <img src="../users/images/<?= htmlspecialchars($row['image']) ?>" class="product-image">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="post" enctype="multipart/form-data" style="display:inline-block;">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="old_image" value="<?= htmlspecialchars($row['image']) ?>">
                                        <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required class="form-control mb-2">
                                        <input type="number" name="price" value="<?= htmlspecialchars($row['price']) ?>" step="0.01" required class="form-control mb-2">
                                        <input type="file" name="image" accept="image/*" class="form-control">
                                </td>
                                <td>
                                    <button type="submit" name="edit_coffee" class="btn btn-sm btn-primary mb-2">Save</button>
                                    </form>
                                    <a href="manage_coffee.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this coffee?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add New Coffee Section -->
            <div class="form-section">
                <h3><i class="fas fa-plus-circle me-2"></i>Add New Coffee</h3>
                <form method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control" placeholder="Coffee Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="price" class="form-control" placeholder="Price" step="0.01" required>
                        </div>
                        <div class="col-md-3">
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="add_coffee" class="btn btn-success w-100">Add Coffee</button>
                        </div>
                    </div>
                </form>
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