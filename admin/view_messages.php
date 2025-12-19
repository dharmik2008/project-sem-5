<?php
session_start();
include("../users/db.php");

if (!isset($_SESSION['admin'])) {
    header('Location: ../users/admin_login.php');
    exit();
}

// Handle message deletion (from contact_messages)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $message_id = intval($_POST['message_id']);
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_messages.php");
    exit();
}

// Handle mark read/unread using is_read flag
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $message_id = intval($_POST['message_id']);
    $new_status = $_POST['new_status'] === 'read' ? 1 : 0;
    $stmt = $conn->prepare("UPDATE contact_messages SET is_read = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $message_id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_messages.php");
    exit();
}

// Fetch contact messages
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = [];
$dbError = $result === false ? $conn->error : null;
$total_messages = 0;
$unread_messages = 0;
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
        $total_messages++;
        if (!isset($row['is_read']) || intval($row['is_read']) !== 1) {
            $unread_messages++;
        }
    }
}

// Build page content using the shared admin template
$page_title = 'Messages';
$page_icon = 'fas fa-comments';
ob_start();
?>
    <div class="content-section">
        <?php if ($dbError): ?>
            <div class="alert alert-warning">
                <strong>Database error:</strong> <?php echo htmlspecialchars($dbError); ?>
            </div>
        <?php endif; ?>

        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Messages</h3>
                <div class="stat-number"><?php echo $total_messages; ?></div>
            </div>
            <div class="stat-card">
                <h3>Unread Messages</h3>
                <div class="stat-number"><?php echo $unread_messages; ?></div>
            </div>
        </div>

        <?php if (!empty($messages)): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr>
                                <td><?php echo $message['id']; ?></td>
                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                <td>
                                    <div class="message-preview" title="<?php echo htmlspecialchars($message['message']); ?>">
                                        <?php echo htmlspecialchars(substr($message['message'], 0, 80)); ?><?php echo (strlen($message['message']) > 80) ? '...' : ''; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php $isRead = isset($message['is_read']) && intval($message['is_read']) === 1; ?>
                                    <span class="badge <?php echo $isRead ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo $isRead ? 'Read' : 'Unread'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($message['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $message['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                            <input type="hidden" name="new_status" value="<?php echo $isRead ? 'unread' : 'read'; ?>">
                                            <button type="submit" name="update_status" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this message?');">
                                            <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                            <button type="submit" name="delete_message" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal<?php echo $message['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Message #<?php echo $message['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                                            <p><strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($message['created_at'])); ?></p>
                                            <div class="message-content p-3 bg-light rounded">
                                                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No messages found</h5>
                <p class="text-muted">There are currently no customer messages.</p>
            </div>
        <?php endif; ?>
    </div>
<?php
$page_content = ob_get_clean();
include 'template.php';
?>