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

// Logo ve favicon ayarlarını çek
$logoSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_logo'");
$faviconSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_favicon'");
$siteLogo = $logoSetting['setting_value'] ?? '';
$siteFavicon = $faviconSetting['setting_value'] ?? '';

// İstatistikler
$eventsResult = $db->fetchOne("SELECT COUNT(*) as count FROM events");
$eventsCount = $eventsResult ? $eventsResult['count'] : 0;

$communitiesResult = $db->fetchOne("SELECT COUNT(*) as count FROM communities");
$communitiesCount = $communitiesResult ? $communitiesResult['count'] : 0;

$teamResult = $db->fetchOne("SELECT COUNT(*) as count FROM team_members");
$boardCount = $teamResult ? $teamResult['count'] : 0;

$messagesResult = $db->fetchOne("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = 0");
$messagesCount = $messagesResult ? $messagesResult['count'] : 0;

// Son mesajlar
$recentMessages = $db->fetchAll("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - YTU MINT Admin</title>
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
                <img src="../<?php echo htmlspecialchars($siteLogo); ?>" alt="YTU MINT" style="width: 80px; height: 80px; object-fit: contain;">
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
            <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="events.php"><i class="fas fa-calendar"></i> <span>Etkinlikler</span></a></li>
            <li><a href="communities.php"><i class="fas fa-users"></i> <span>Topluluklar</span></a></li>
            <li><a href="team-members.php"><i class="fas fa-user-friends"></i> <span>Ekip Üyeleri</span></a></li>
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
            <h1>Dashboard</h1>
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
            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="dashboard-card-icon green">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="dashboard-card-content">
                        <h3><?php echo $eventsCount; ?></h3>
                        <p>Toplam Etkinlik</p>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="dashboard-card-content">
                        <h3><?php echo $communitiesCount; ?></h3>
                        <p>Toplam Topluluk</p>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-icon orange">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="dashboard-card-content">
                        <h3><?php echo $boardCount; ?></h3>
                        <p>Toplam Kulüp Üyesi</p>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-card-icon red">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="dashboard-card-content">
                        <h3><?php echo $messagesCount; ?></h3>
                        <p>Okunmamış Mesaj</p>
                    </div>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="table-container">
                <div class="table-header">
                    <h2>Son Mesajlar</h2>
                    <a href="messages.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-envelope"></i> Tüm Mesajlar
                    </a>
                </div>

                <?php if (empty($recentMessages)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>Henüz mesaj yok</h3>
                        <p>Kullanıcılardan gelen mesajlar burada görünecek</p>
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
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentMessages as $message): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($message['name']); ?></td>
                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                <td><?php echo htmlspecialchars(substr($message['subject'], 0, 30)) . '...'; ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($message['created_at'])); ?></td>
                                <td>
                                    <?php if ($message['is_read']): ?>
                                        <span class="badge badge-success">Okundu</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Okunmadı</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="messages.php?view=<?php echo $message['id']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
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
