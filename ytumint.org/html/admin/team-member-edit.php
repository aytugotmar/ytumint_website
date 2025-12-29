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
$isEdit = isset($_GET['id']);
$member = null;

// Logo ve favicon ayarlarını çek
require_once 'includes/admin-header.php';

// Toplulukları getir
$communities = $db->fetchAll("SELECT id, name FROM communities WHERE is_active = 1 ORDER BY name ASC");

// Düzenleme modunda üye bilgilerini getir
if ($isEdit) {
    $id = (int)$_GET['id'];
    $member = $db->fetchOne("SELECT * FROM team_members WHERE id = ?", [$id]);
    
    if (!$member) {
        header('Location: team-members.php');
        exit;
    }
}

// Form işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $member_type = $_POST['member_type'] ?? 'active';
    $community_id = !empty($_POST['community_id']) ? (int)$_POST['community_id'] : null;
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Community type ise community_id zorunlu
    if ($member_type === 'community' && !$community_id) {
        $message = 'Topluluk üyesi seçiliyse topluluk belirtilmelidir.';
        $messageType = 'error';
    } elseif (empty($name) || empty($position)) {
        $message = 'Ad soyad ve pozisyon gereklidir.';
        $messageType = 'error';
    } else {
        // Fotoğraf yükleme
        $imagePath = $isEdit ? $member['image'] : '';
        $upload = ytumint_save_upload('image', 'team', 'team');
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
        } elseif ($isEdit) {
            // Güncelleme
            $id = (int)$_POST['id'];
            $result = $db->query(
                "UPDATE team_members SET name = ?, position = ?, email = ?, linkedin = ?, 
                 member_type = ?, community_id = ?, display_order = ?, is_active = ?, image = ?
                 WHERE id = ?",
                [$name, $position, $email, $linkedin, $member_type, $community_id, $display_order, $is_active, $imagePath, $id]
            );
            
            if ($result) {
                header('Location: team-members.php');
                exit;
            } else {
                $message = 'Güncelleme sırasında hata oluştu.';
                $messageType = 'error';
            }
        } else {
            // Yeni üye ekleme
            $result = $db->query(
                "INSERT INTO team_members (name, position, email, linkedin, member_type, community_id, display_order, is_active, image) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$name, $position, $email, $linkedin, $member_type, $community_id, $display_order, $is_active, $imagePath]
            );
            
            if ($result) {
                header('Location: team-members.php');
                exit;
            } else {
                $message = 'Üye eklenirken hata oluştu.';
                $messageType = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Üye Düzenle' : 'Yeni Üye Ekle'; ?> - YTU MINT Admin</title>
    <?php if (!empty($siteFavicon)): ?>
    <link rel="icon" type="image/x-icon" href="../<?php echo htmlspecialchars($siteFavicon); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?php echo (int)@filemtime(__DIR__ . '/../assets/css/admin.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-group select, .form-group input[type="number"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
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
            <h1><?php echo $isEdit ? 'Ekip Üyesi Düzenle' : 'Yeni Ekip Üyesi Ekle'; ?></h1>
            <div class="top-bar-actions">
                <a href="team-members.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Geri Dön
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
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="name">Ad Soyad *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo $isEdit ? htmlspecialchars($member['name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="position">Pozisyon *</label>
                        <input type="text" id="position" name="position" required 
                               value="<?php echo $isEdit ? htmlspecialchars($member['position']) : ''; ?>"
                               placeholder="Başkan, Başkan Yardımcısı, Üye vb.">
                    </div>

                    <div class="form-group">
                        <label for="member_type">Kategori *</label>
                        <select id="member_type" name="member_type" required onchange="toggleCommunitySelect()">
                            <option value="board" <?php echo ($isEdit && $member['member_type'] === 'board') ? 'selected' : ''; ?>>
                                Yönetim Kurulu
                            </option>
                            <option value="community" <?php echo ($isEdit && $member['member_type'] === 'community') ? 'selected' : ''; ?>>
                                Topluluk Üyesi
                            </option>
                            <option value="active" <?php echo ($isEdit && $member['member_type'] === 'active') ? 'selected' : ''; ?>>
                                Aktif Üye
                            </option>
                        </select>
                        <small>Yönetim Kurulu en üstte, Topluluk Üyeleri ortada, Aktif Üyeler en altta gösterilir</small>
                    </div>

                    <div class="form-group" id="community_select_group" style="display: none;">
                        <label for="community_id">Topluluk</label>
                        <select id="community_id" name="community_id">
                            <option value="">Topluluk Seçin</option>
                            <?php foreach ($communities as $community): ?>
                                <option value="<?php echo $community['id']; ?>" 
                                    <?php echo ($isEdit && $member['community_id'] == $community['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($community['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="display_order">Gösterilme Sırası</label>
                        <input type="number" id="display_order" name="display_order" 
                               value="<?php echo $isEdit ? $member['display_order'] : 0; ?>" min="0">
                        <small>Küçük sayılar önce gösterilir (0, 1, 2...)</small>
                    </div>

                    <div class="form-group">
                        <label for="email">E-posta</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo $isEdit ? htmlspecialchars($member['email'] ?? '') : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="linkedin">LinkedIn Profil URL</label>
                        <input type="url" id="linkedin" name="linkedin" 
                               value="<?php echo $isEdit ? htmlspecialchars($member['linkedin'] ?? '') : ''; ?>"
                               placeholder="https://linkedin.com/in/kullanici-adi">
                    </div>

                    <div class="form-group">
                        <label for="image">Fotoğraf</label>
                        <?php if ($isEdit && !empty($member['image']) && file_exists('../' . $member['image'])): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="../<?php echo htmlspecialchars($member['image']); ?>" 
                                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="image" name="image" accept="image/*">
                        <small>Kare fotoğraf önerilir (örn: 400x400px)</small>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" <?php echo ($isEdit && $member['is_active']) || !$isEdit ? 'checked' : ''; ?>>
                            Aktif
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Güncelle' : 'Ekle'; ?>
                        </button>
                        <a href="team-members.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function toggleCommunitySelect() {
            const memberType = document.getElementById('member_type').value;
            const communityGroup = document.getElementById('community_select_group');
            const communitySelect = document.getElementById('community_id');
            
            if (memberType === 'community') {
                communityGroup.style.display = 'block';
                communitySelect.required = true;
            } else {
                communityGroup.style.display = 'none';
                communitySelect.required = false;
                communitySelect.value = '';
            }
        }
        
        // Sayfa yüklendiğinde kontrol et
        document.addEventListener('DOMContentLoaded', toggleCommunitySelect);
    </script>
</body>
</html>
