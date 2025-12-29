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

// Mevcut ayarları çek
$settings = [];
$settingsData = $db->fetchAll("SELECT * FROM site_settings");
foreach ($settingsData as $setting) {
    $settings[$setting['setting_key']] = $setting['setting_value'];
}

// Form işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_title = trim($_POST['site_title'] ?? 'YTU MINT');
    $site_slogan = trim($_POST['site_slogan'] ?? '');
    $site_description = trim($_POST['site_description'] ?? '');
    $twitter_url = trim($_POST['twitter_url'] ?? '');
    $instagram_url = trim($_POST['instagram_url'] ?? '');
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $youtube_url = trim($_POST['youtube_url'] ?? '');
    $tiktok_url = trim($_POST['tiktok_url'] ?? '');
    
    // Ayarları güncelle veya ekle
    $settingsToUpdate = [
        'site_title' => $site_title,
        'site_slogan' => $site_slogan,
        'site_description' => $site_description,
        'twitter_url' => $twitter_url,
        'instagram_url' => $instagram_url,
        'linkedin_url' => $linkedin_url,
        'youtube_url' => $youtube_url,
        'tiktok_url' => $tiktok_url
    ];
    
    foreach ($settingsToUpdate as $key => $value) {
        $db->query(
            "INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) 
             ON DUPLICATE KEY UPDATE setting_value = ?", 
            [$key, $value, $value]
        );
    }

    $uploadErrors = [];
    // Logo upload
    $logoUpload = ytumint_save_upload('site_logo', 'logo', '', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    if ($logoUpload['attempted']) {
        if (!empty($logoUpload['error'])) {
            $uploadErrors[] = 'Logo: ' . $logoUpload['error'];
        } elseif (!empty($logoUpload['path'])) {
            $db->query(
                "INSERT INTO site_settings (setting_key, setting_value) VALUES ('site_logo', ?) 
                 ON DUPLICATE KEY UPDATE setting_value = ?",
                [$logoUpload['path'], $logoUpload['path']]
            );
        }
    }

    // Favicon upload
    $faviconUpload = ytumint_save_upload('site_favicon', 'favicon', '', ['ico', 'png']);
    if ($faviconUpload['attempted']) {
        if (!empty($faviconUpload['error'])) {
            $uploadErrors[] = 'Favicon: ' . $faviconUpload['error'];
        } elseif (!empty($faviconUpload['path'])) {
            $db->query(
                "INSERT INTO site_settings (setting_key, setting_value) VALUES ('site_favicon', ?) 
                 ON DUPLICATE KEY UPDATE setting_value = ?",
                [$faviconUpload['path'], $faviconUpload['path']]
            );
        }
    }

    if (!empty($uploadErrors)) {
        $message = 'Site kimliği güncellendi fakat bazı dosyalar yüklenemedi: ' . implode(' | ', $uploadErrors);
        $messageType = 'error';
    } else {
        $message = 'Site kimliği başarıyla güncellendi!';
        $messageType = 'success';
    }
    
    // Ayarları tekrar yükle
    $settingsData = $db->fetchAll("SELECT * FROM site_settings");
    $settings = [];
    foreach ($settingsData as $setting) {
        $settings[$setting['setting_key']] = $setting['setting_value'];
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Kimliği - YTU MINT Admin</title>
    <?php if (!empty($settings['site_favicon'])): ?>
    <link rel="icon" type="image/x-icon" href="../<?php echo htmlspecialchars($settings['site_favicon']); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?php echo (int)@filemtime(__DIR__ . '/../assets/css/admin.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <?php if (!empty($settings['site_logo'])): ?>
                <img src="../<?php echo htmlspecialchars($settings['site_logo']); ?>" alt="YTU MINT" style="width: 80px; height: 80px;">
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
            <li><a href="messages.php"><i class="fas fa-envelope"></i> <span>Mesajlar</span></a></li>
            <li><a href="admin-users.php"><i class="fas fa-user-shield"></i> <span>Admin Kullanıcıları</span></a></li>
            <li><a href="site-identity.php" class="active"><i class="fas fa-palette"></i> <span>Site Kimliği</span></a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> <span>Ayarlar</span></a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="top-bar">
            <h1>Site Kimliği Yönetimi</h1>
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

            <div class="card">
                <form method="POST" enctype="multipart/form-data">
                    <h3 style="margin-bottom: 20px; color: #2d4a36;"><i class="fas fa-info-circle"></i> Genel Bilgiler</h3>
                    
                    <div class="form-group">
                        <label for="site_title">Site Başlığı *</label>
                        <input type="text" id="site_title" name="site_title" required
                               value="<?php echo htmlspecialchars($settings['site_title'] ?? 'YTU MINT'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="site_slogan">Site Sloganı</label>
                        <input type="text" id="site_slogan" name="site_slogan"
                               value="<?php echo htmlspecialchars($settings['site_slogan'] ?? ''); ?>"
                               placeholder="Teknoloji ve İnovasyonda Öncü Topluluk">
                    </div>

                    <div class="form-group">
                        <label for="site_description">Site Açıklaması (SEO)</label>
                        <textarea id="site_description" name="site_description" rows="3"
                                  placeholder="Site açıklaması, arama motorları için önemlidir..."><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                    </div>

                    <hr style="margin: 30px 0; border: none; border-top: 1px solid #e0e0e0;">

                    <h3 style="margin-bottom: 20px; color: #2d4a36;"><i class="fas fa-image"></i> Görsel Öğeler</h3>

                    <div class="form-group">
                        <label for="site_logo">Site Logosu</label>
                        <?php if (!empty($settings['site_logo'])): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="../<?php echo htmlspecialchars($settings['site_logo']); ?>" 
                                     alt="Logo" style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="site_logo" name="site_logo" accept="image/*">
                        <small>PNG veya SVG formatında, şeffaf arkaplan önerilir</small>
                    </div>

                    <div class="form-group">
                        <label for="site_favicon">Favicon</label>
                        <?php if (!empty($settings['site_favicon'])): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="../<?php echo htmlspecialchars($settings['site_favicon']); ?>" 
                                     alt="Favicon" style="width: 32px; height: 32px; border: 1px solid #ddd; padding: 5px; border-radius: 3px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="site_favicon" name="site_favicon" accept="image/*">
                        <small>32x32 veya 64x64 piksel, .ico veya .png formatında</small>
                    </div>

                    <hr style="margin: 30px 0; border: none; border-top: 1px solid #e0e0e0;">

                    <h3 style="margin-bottom: 20px; color: #2d4a36;"><i class="fas fa-share-alt"></i> Sosyal Medya</h3>

                    <div class="form-group">
                        <label for="twitter_url"><i class="fab fa-x-twitter"></i> Twitter/X URL</label>
                        <input type="url" id="twitter_url" name="twitter_url"
                               value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>"
                               placeholder="https://twitter.com/ytumint">
                    </div>

                    <div class="form-group">
                        <label for="instagram_url"><i class="fab fa-instagram"></i> Instagram URL</label>
                        <input type="url" id="instagram_url" name="instagram_url"
                               value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>"
                               placeholder="https://instagram.com/ytumint">
                    </div>

                    <div class="form-group">
                        <label for="linkedin_url"><i class="fab fa-linkedin"></i> LinkedIn URL</label>
                        <input type="url" id="linkedin_url" name="linkedin_url"
                               value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>"
                               placeholder="https://linkedin.com/company/ytumint">
                    </div>

                    <div class="form-group">
                        <label for="youtube_url"><i class="fab fa-youtube"></i> YouTube URL</label>
                        <input type="url" id="youtube_url" name="youtube_url"
                               value="<?php echo htmlspecialchars($settings['youtube_url'] ?? ''); ?>"
                               placeholder="https://youtube.com/@ytumint">
                    </div>

                    <div class="form-group">
                        <label for="tiktok_url"><i class="fab fa-tiktok"></i> TikTok URL</label>
                        <input type="url" id="tiktok_url" name="tiktok_url"
                               value="<?php echo htmlspecialchars($settings['tiktok_url'] ?? ''); ?>"
                               placeholder="https://tiktok.com/@ytumint">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
