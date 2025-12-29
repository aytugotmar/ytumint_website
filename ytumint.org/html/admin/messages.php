<?php
require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$db = Database::getInstance();
$message = '';
$messageType = '';

// Logo ve favicon ayarlarını çek
require_once 'includes/admin-header.php';

// Mesajı okundu olarak işaretle
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    $db->query("UPDATE contact_messages SET is_read = 1 WHERE id = ?", [$id]);
    header('Location: messages.php');
    exit;
}

// Mesajı sil
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->query("DELETE FROM contact_messages WHERE id = ?", [$id])) {
        $message = 'Mesaj başarıyla silindi.';
        $messageType = 'success';
    }
}

// Mesaj detayı
$viewMessage = null;
if (isset($_GET['view'])) {
    $id = (int)$_GET['view'];
    $viewMessage = $db->fetchOne("SELECT * FROM contact_messages WHERE id = ?", [$id]);
    if ($viewMessage && !$viewMessage['is_read']) {
        $db->query("UPDATE contact_messages SET is_read = 1 WHERE id = ?", [$id]);
    }
}

$messages = $db->fetchAll("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesajlar - YTU MINT Admin</title>
    <?php if (!empty($siteFavicon)): ?>
    <link rel="icon" type="image/x-icon" href="../<?php echo htmlspecialchars($siteFavicon); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?php echo (int)@filemtime(__DIR__ . '/../assets/css/admin.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <?php if (!empty($siteLogo)): ?>
                <img src="../<?php echo htmlspecialchars($siteLogo); ?>" alt="YTU MINT" style="width: 80px; height: 80px;">
            <?php else: ?>
            <svg width="80" height="80" viewBox="0 0 100 100">
                <path d="M50 10 L30 30 Q20 50 30 70 L50 90 L70 70 Q80 50 70 30 Z" fill="#7ed09e"/>
                <circle cx="40" cy="40" r="3" fill="#4a7c59"/>
                <circle cx="60" cy="40" r="3" fill="#4a7c59"/>
                <circle cx="50" cy="60" r="3" fill="#4a7c59"/>
                <line x1="40" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
                <line x1="60" y1="40" x2="50" y2="50" stroke="#4a7c59" stroke-width="2"/>
                <line x1="50" y1="50" x2="50" y2="60" stroke="#4a7c59" stroke-width="2"/>
            </svg>
            <?php endif; ?>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="events.php"><i class="fas fa-calendar"></i> <span>Etkinlikler</span></a></li>
            <li><a href="communities.php"><i class="fas fa-users"></i> <span>Topluluklar</span></a></li>
            <li><a href="team-members.php"><i class="fas fa-user-friends"></i> <span>Ekip Üyeleri</span></a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> <span>Hakkımızda</span></a></li>
            <li><a href="messages.php" class="active"><i class="fas fa-envelope"></i> <span>Mesajlar</span></a></li>
            <li><a href="admin-users.php"><i class="fas fa-user-shield"></i> <span>Admin Kullanıcıları</span></a></li>
            <li><a href="site-identity.php"><i class="fas fa-palette"></i> <span>Site Kimliği</span></a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> <span>Ayarlar</span></a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <h1>İletişim Mesajları</h1>
            <div class="top-bar-actions">
                <a href="../" class="btn btn-secondary" target="_blank"><i class="fas fa-external-link-alt"></i> Siteye Git</a>
                <a href="index.php?logout=1" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
            </div>
        </div>

        <div class="content-area">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($viewMessage): ?>
                <div class="form-container" style="max-width: 100%;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>Mesaj Detayı</h2>
                        <a href="messages.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Geri</a>
                    </div>
                    
                    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                        <p><strong>Gönderen:</strong> <?php echo htmlspecialchars($viewMessage['name']); ?></p>
                        <p><strong>E-posta:</strong> <a href="mailto:<?php echo htmlspecialchars($viewMessage['email']); ?>"><?php echo htmlspecialchars($viewMessage['email']); ?></a></p>
                        <p><strong>Konu:</strong> <?php echo htmlspecialchars($viewMessage['subject']); ?></p>
                        <p><strong>Tarih:</strong> <?php echo date('d.m.Y H:i', strtotime($viewMessage['created_at'])); ?></p>
                    </div>
                    
                    <div style="background: #fff; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px;">
                        <h3 style="margin-bottom: 15px;">Mesaj:</h3>
                        <p style="line-height: 1.8; white-space: pre-wrap;"><?php echo htmlspecialchars($viewMessage['message']); ?></p>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <a href="messages.php?delete=<?php echo $viewMessage['id']; ?>" class="btn btn-danger" onclick="return confirm('Bu mesajı silmek istediğinizden emin misiniz?')">
                            <i class="fas fa-trash"></i> Mesajı Sil
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <div class="table-header">
                        <h2>Tüm Mesajlar</h2>
                    </div>

                    <?php if (empty($messages)): ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>Henüz mesaj yok</h3>
                        </div>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Ad Soyad</th>
                                    <th>E-posta</th>
                                    <th>Konu</th>
                                    <th>Tarih</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $msg): ?>
                                <tr style="<?php echo !$msg['is_read'] ? 'font-weight: bold; background: #f0f8ff;' : ''; ?>">
                                    <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($msg['subject'], 0, 40)) . '...'; ?></td>
                                    <td><?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $msg['is_read'] ? 'success' : 'danger'; ?>">
                                            <?php echo $msg['is_read'] ? 'Okundu' : 'Okunmadı'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="messages.php?view=<?php echo $msg['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                        <?php if (!$msg['is_read']): ?>
                                            <a href="messages.php?read=<?php echo $msg['id']; ?>" class="btn btn-secondary btn-sm" title="Okundu İşaretle"><i class="fas fa-check"></i></a>
                                        <?php endif; ?>
                                        <a href="messages.php?delete=<?php echo $msg['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
