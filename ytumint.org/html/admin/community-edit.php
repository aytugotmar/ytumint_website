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
$community = null;

// Logo ve favicon ayarlarını çek
require_once 'includes/admin-header.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $community = $db->fetchOne("SELECT * FROM communities WHERE id = ?", [$id]);
    if (!$community) {
        header('Location: communities.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $website_url = trim($_POST['website_url'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $id = (int)($_POST['id'] ?? 0);
    
    $imagePath = $community['image'] ?? '';
    $upload = ytumint_save_upload('image', 'community');
    if ($upload['attempted']) {
        if (!empty($upload['error'])) {
            $message = $upload['error'];
            $messageType = 'error';
        } elseif (!empty($upload['path'])) {
            ytumint_delete_upload_by_relative($imagePath);
            $imagePath = $upload['path'];
        }
    }
    
    if (!empty($message)) {
        // upload hatası varsa burada dur
    } elseif (empty($name)) {
        $message = 'İsim alanı zorunludur.';
        $messageType = 'error';
    } else {
        if ($id > 0) {
            $sql = "UPDATE communities SET name = ?, description = ?, image = ?, website_url = ?, display_order = ?, is_active = ? WHERE id = ?";
            $params = [$name, $description, $imagePath, $website_url, $display_order, $is_active, $id];
        } else {
            $sql = "INSERT INTO communities (name, description, image, website_url, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)";
            $params = [$name, $description, $imagePath, $website_url, $display_order, $is_active];
        }
        
        if ($db->query($sql, $params)) {
            header('Location: communities.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $community ? 'Topluluk Düzenle' : 'Yeni Topluluk'; ?> - YTU MINT Admin</title>
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
            <h1><?php echo $community ? 'Topluluk Düzenle' : 'Yeni Topluluk'; ?></h1>
            <div class="top-bar-actions">
                <a href="communities.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Geri Dön</a>
                <a href="index.php?logout=1" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
            </div>
        </div>

        <div class="content-area">
            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $community['id'] ?? ''; ?>">
                    
                    <div class="form-group">
                        <label for="name">Topluluk Adı *</label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($community['name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Açıklama</label>
                        <textarea id="description" name="description"><?php echo htmlspecialchars($community['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="website_url">Web Sitesi URL</label>
                        <input type="url" id="website_url" name="website_url" value="<?php echo htmlspecialchars($community['website_url'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="image">Logo / Görsel</label>
                        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                        <?php if (!empty($community['image']) && file_exists('../' . $community['image'])): ?>
                            <div class="image-preview">
                                <img id="preview" src="../<?php echo htmlspecialchars($community['image']); ?>" alt="" style="border-radius:50%;">
                            </div>
                        <?php else: ?>
                            <div class="image-preview" id="previewContainer" style="display: none;">
                                <img id="preview" src="" alt="" style="border-radius:50%;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="display_order">Sıra</label>
                        <input type="number" id="display_order" name="display_order" min="0" value="<?php echo $community['display_order'] ?? 0; ?>">
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" <?php echo (!isset($community) || $community['is_active']) ? 'checked' : ''; ?>> Aktif
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kaydet</button>
                        <a href="communities.php" class="btn btn-secondary"><i class="fas fa-times"></i> İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('previewContainer');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    if (previewContainer) previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
