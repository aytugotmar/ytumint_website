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
$admin = null;

// Logo ve favicon ayarlarını çek
require_once 'includes/admin-header.php';

// Düzenleme modunda kullanıcı bilgilerini getir
if ($isEdit) {
    $id = (int)$_GET['id'];
    $admin = $db->fetchOne("SELECT * FROM admin_users WHERE id = ?", [$id]);
    
    if (!$admin) {
        header('Location: admin-users.php');
        exit;
    }
}

// Form işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email)) {
        $message = 'Kullanıcı adı ve e-posta gereklidir.';
        $messageType = 'error';
    } elseif (!$isEdit && empty($password)) {
        $message = 'Yeni kullanıcı için şifre gereklidir.';
        $messageType = 'error';
    } elseif (!empty($password) && $password !== $confirmPassword) {
        $message = 'Şifreler eşleşmiyor.';
        $messageType = 'error';
    } else {
        if ($isEdit) {
            // Güncelleme
            $id = (int)$_POST['id'];
            
            if (!empty($password)) {
                // Şifre değiştiriliyor
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $result = $db->query("UPDATE admin_users SET username = ?, email = ?, password = ? WHERE id = ?", 
                    [$username, $email, $hashedPassword, $id]);
            } else {
                // Sadece kullanıcı adı ve e-posta güncelleniyor
                $result = $db->query("UPDATE admin_users SET username = ?, email = ? WHERE id = ?", 
                    [$username, $email, $id]);
            }
            
            if ($result) {
                header('Location: admin-users.php');
                exit;
            } else {
                $message = 'Güncelleme sırasında hata oluştu.';
                $messageType = 'error';
            }
        } else {
            // Yeni kullanıcı ekleme
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            $result = $db->query("INSERT INTO admin_users (username, email, password) VALUES (?, ?, ?)", 
                [$username, $email, $hashedPassword]);
            
            if ($result) {
                header('Location: admin-users.php');
                exit;
            } else {
                $message = 'Kullanıcı eklenirken hata oluştu. Kullanıcı adı zaten kullanılıyor olabilir.';
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
    <title><?php echo $isEdit ? 'Admin Düzenle' : 'Yeni Admin Ekle'; ?> - YTU MINT Admin</title>
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
            <li><a href="team-members.php"><i class="fas fa-user-friends"></i> <span>Ekip Üyeleri</span></a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> <span>Hakkımızda</span></a></li>
            <li><a href="messages.php"><i class="fas fa-envelope"></i> <span>Mesajlar</span></a></li>
            <li><a href="admin-users.php" class="active"><i class="fas fa-user-shield"></i> <span>Admin Kullanıcıları</span></a></li>
            <li><a href="site-identity.php"><i class="fas fa-palette"></i> <span>Site Kimliği</span></a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> <span>Ayarlar</span></a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="top-bar">
            <h1><?php echo $isEdit ? 'Admin Kullanıcısı Düzenle' : 'Yeni Admin Kullanıcısı Ekle'; ?></h1>
            <div class="top-bar-actions">
                <a href="admin-users.php" class="btn btn-secondary">
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
                <form method="POST" action="">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="username">Kullanıcı Adı *</label>
                        <input type="text" id="username" name="username" required 
                               value="<?php echo $isEdit ? htmlspecialchars($admin['username']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">E-posta *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo $isEdit ? htmlspecialchars($admin['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Şifre <?php echo $isEdit ? '(Değiştirmek istemiyorsanız boş bırakın)' : '*'; ?></label>
                        <input type="password" id="password" name="password" <?php echo !$isEdit ? 'required' : ''; ?>>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Şifre Tekrar <?php echo $isEdit ? '' : '*'; ?></label>
                        <input type="password" id="confirm_password" name="confirm_password" <?php echo !$isEdit ? 'required' : ''; ?>>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Güncelle' : 'Ekle'; ?>
                        </button>
                        <a href="admin-users.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
