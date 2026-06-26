<?php
/**
 * OpenShelf Admin Transaction History
 * Show all completed support transactions and total donation amount.
 */

session_start();

require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: /admin/login/');
    exit;
}

$search = trim($_GET['search'] ?? '');

$db = getDB();
$sql = "SELECT t.*, s.user_name AS support_user_name, s.provider AS support_provider FROM transactions t LEFT JOIN support_us s ON s.id = t.support_us_id";
$params = [];
$where = [];
if (!empty($search)) {
    $where[] = '(t.invoice_number LIKE :search OR t.transaction_id LIKE :search OR t.user_name LIKE :search OR t.user_email LIKE :search)';
    $params[':search'] = '%' . $search . '%';
}
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY t.created_at DESC';
$stmt = $db->prepare($sql);
$stmt->execute($params);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

function totalAmount($db) {
    $stmt = $db->query("SELECT COALESCE(SUM(amount), 0) FROM transactions");
    return $stmt->fetchColumn();
}

$totalTransactions = $db->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
$grandTotal = totalAmount($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Transactions - OpenShelf</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f8fafc; color: #0f172a; }
        .admin-panel { max-width: 1200px; margin: 0 auto; padding: 32px; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 28px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(15,23,42,0.04); }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: middle; }
        th { background: #f1f5f9; font-weight: 700; }
        .stats-grid { display: grid; gap: 18px; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); }
        .stat-card { padding: 22px; border-radius: 24px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .stat-card h2 { margin: 0 0 10px; font-size: 1.9rem; }
        .stat-card p { margin: 0; color: #475569; }
        .btn-secondary { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:14px; background:#0f172a; color:#fff; text-decoration:none; }
        .filter-row { display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:18px; }
        .filter-row input { padding:12px 14px; border:1px solid #cbd5e1; border-radius:14px; width:280px; }
        .text-muted { color:#64748b; }
    </style>
</head>
<body>
    <div class="admin-panel">
        <div class="card" style="margin-bottom: 24px;">
            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:12px; align-items:center;">
                <div>
                    <h1 style="font-size:2rem; margin:0;">Transaction History</h1>
                    <p class="text-muted" style="margin:0.5rem 0 0;">All approved support transactions and total received amount.</p>
                </div>
                <a href="/admin/support_us.php" class="btn-secondary"><i class="fas fa-hand-holding-dollar"></i> Support Requests</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h2><?php echo htmlspecialchars(number_format((float)$grandTotal, 2)); ?></h2>
                <p>Total Amount Collected</p>
            </div>
            <div class="stat-card">
                <h2><?php echo htmlspecialchars((int)$totalTransactions); ?></h2>
                <p>Total Transactions</p>
            </div>
        </div>

        <div class="card">
            <div class="filter-row">
                <form method="get" style="display:flex; gap:12px; flex-wrap:wrap; width:100%;">
                    <input type="search" name="search" placeholder="Search invoice, transaction id, user" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-secondary">Search</button>
                </form>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>User</th>
                            <th>Provider</th>
                            <th>Amount</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr><td colspan="7" style="text-align:center; padding: 24px;">No transactions found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $txn): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($txn['invoice_number']); ?></td>
                                    <td><?php echo htmlspecialchars($txn['user_name'] ?: $txn['support_user_name'] ?: 'Unknown'); ?><br><span class="text-muted"><?php echo htmlspecialchars($txn['user_email']); ?></span></td>
                                    <td><?php echo htmlspecialchars(ucfirst($txn['provider'])); ?></td>
                                    <td><?php echo htmlspecialchars(number_format((float)$txn['amount'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars($txn['transaction_id']); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($txn['status'])); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($txn['created_at']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
