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

// Silme işlemi
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->query("DELETE FROM team_members WHERE id = ?", [$id])) {
        $message = 'Üye başarıyla silindi.';
        $messageType = 'success';
    } else {
        $message = 'Üye silinirken hata oluştu.';
        $messageType = 'error';
    }
}

// Durum değiştirme
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $db->query("UPDATE team_members SET is_active = NOT is_active WHERE id = ?", [$id]);
    header('Location: team-members.php');
    exit;
}

// Tüm üyeleri getir
$members = $db->fetchAll("
    SELECT tm.*, c.name as community_name 
    FROM team_members tm
    LEFT JOIN communities c ON tm.community_id = c.id
    ORDER BY 
        FIELD(tm.member_type, 'board', 'community', 'active'),
        tm.display_order ASC,
        tm.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekip Üyeleri - YTU MINT Admin</title>
    <?php if (!empty($siteFavicon)): ?>
    <link rel="icon" type="image/x-icon" href="../<?php echo htmlspecialchars($siteFavicon); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?php echo (int)@filemtime(__DIR__ . '/../assets/css/admin.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
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
            <li><a href="team-members.php" class="active"><i class="fas fa-user-friends"></i> <span>Ekip Üyeleri</span></a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> <span>Hakkımızda</span></a></li>
            <li><a href="messages.php"><i class="fas fa-envelope"></i> <span>Mesajlar</span></a></li>
            <li><a href="admin-users.php"><i class="fas fa-user-shield"></i> <span>Admin Kullanıcıları</span></a></li>
            <li><a href="site-identity.php"><i class="fas fa-palette"></i> <span>Site Kimliği</span></a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> <span>Ayarlar</span></a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="top-bar">
            <h1>Ekip Üyeleri Yönetimi</h1>
            <div class="top-bar-actions">
                <a href="team-member-edit.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Yeni Üye Ekle
                </a>
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
                <table>
                    <thead>
                        <tr>
                            <th>Fotoğraf</th>
                            <th>Ad Soyad</th>
                            <th>Pozisyon</th>
                            <th>Kategori</th>
                            <th>Topluluk</th>
                            <th>Sıra</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                        <tr>
                            <td>
                                <?php if (!empty($member['image']) && file_exists('../' . $member['image'])): ?>
                                    <img src="../<?php echo htmlspecialchars($member['image']); ?>" 
                                         style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: #e0e0e0;"></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($member['name']); ?></td>
                            <td><?php echo htmlspecialchars($member['position']); ?></td>
                            <td>
                                <?php 
                                $types = [
                                    'board' => '<span class="badge badge-primary">Yönetim Kurulu</span>',
                                    'community' => '<span class="badge badge-info">Topluluk Üyesi</span>',
                                    'active' => '<span class="badge badge-success">Aktif Üye</span>'
                                ];
                                echo $types[$member['member_type']] ?? '';
                                ?>
                            </td>
                            <td><?php echo $member['community_name'] ? htmlspecialchars($member['community_name']) : '-'; ?></td>
                            <td><?php echo $member['display_order']; ?></td>
                            <td>
                                <a href="team-members.php?toggle=<?php echo $member['id']; ?>" 
                                   class="badge <?php echo $member['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo $member['is_active'] ? 'Aktif' : 'Pasif'; ?>
                                </a>
                            </td>
                            <td>
                                <a href="team-member-edit.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Düzenle
                                </a>
                                <a href="team-members.php?delete=<?php echo $member['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Bu üyeyi silmek istediğinizden emin misiniz?')">
                                    <i class="fas fa-trash"></i> Sil
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
