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

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->query("DELETE FROM communities WHERE id = ?", [$id])) {
        $message = 'Topluluk başarıyla silindi.';
        $messageType = 'success';
    }
}

if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $db->query("UPDATE communities SET is_active = NOT is_active WHERE id = ?", [$id]);
    header('Location: communities.php');
    exit;
}

$communities = $db->fetchAll("SELECT * FROM communities ORDER BY display_order ASC, created_at DESC");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topluluklar - YTU MINT Admin</title>
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
            <li><a href="communities.php" class="active"><i class="fas fa-users"></i> <span>Topluluklar</span></a></li>
            <li><a href="team-members.php"><i class="fas fa-user-friends"></i> <span>Ekip Üyeleri</span></a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> <span>Hakkımızda</span></a></li>
            <li><a href="messages.php"><i class="fas fa-envelope"></i> <span>Mesajlar</span></a></li>
            <li><a href="admin-users.php"><i class="fas fa-user-shield"></i> <span>Admin Kullanıcıları</span></a></li>
            <li><a href="site-identity.php"><i class="fas fa-palette"></i> <span>Site Kimliği</span></a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> <span>Ayarlar</span></a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <h1>Topluluklar Yönetimi</h1>
            <div class="top-bar-actions">
                <a href="../" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Siteye Git
                </a>
                <a href="index.php?logout=1" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Çıkış
                </a>
            </div>
        </div>

        <div class="content-area">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <div class="table-header">
                    <h2>Tüm Topluluklar</h2>
                    <a href="community-edit.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Yeni Topluluk
                    </a>
                </div>

                <?php if (empty($communities)): ?>
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <h3>Henüz topluluk yok</h3>
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Görsel</th>
                                <th>İsim</th>
                                <th>Açıklama</th>
                                <th>Web Sitesi</th>
                                <th>Sıra</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($communities as $community): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($community['image']) && file_exists('../' . $community['image'])): ?>
                                        <img src="../<?php echo htmlspecialchars($community['image']); ?>" alt="" class="table-image" style="border-radius:50%;">
                                    <?php else: ?>
                                        <div style="width:60px;height:60px;background:#eee;border-radius:50%;"></div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($community['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars(substr($community['description'], 0, 50)) . '...'; ?></td>
                                <td><a href="<?php echo htmlspecialchars($community['website_url']); ?>" target="_blank">Link</a></td>
                                <td><?php echo $community['display_order']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $community['is_active'] ? 'success' : 'danger'; ?>">
                                        <?php echo $community['is_active'] ? 'Aktif' : 'Pasif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="community-edit.php?id=<?php echo $community['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="communities.php?toggle=<?php echo $community['id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-toggle-on"></i></a>
                                    <a href="communities.php?delete=<?php echo $community['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
