<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Sayfa Bulunamadı | YTU MINT</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo (int)@filemtime(__DIR__ . '/assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            padding: 20px;
            text-align: center;
        }
        .error-content {
            max-width: 600px;
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: var(--primary-green);
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 32px;
            color: var(--dark-green);
            margin: 20px 0;
        }
        .error-description {
            font-size: 18px;
            color: #666;
            margin: 20px 0;
            line-height: 1.6;
        }
        .error-icon {
            font-size: 80px;
            color: var(--primary-green);
            margin-bottom: 20px;
        }
        .error-buttons {
            margin-top: 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .error-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: var(--primary-green);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .error-button:hover {
            background: var(--dark-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .error-button.secondary {
            background: white;
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
        }
        .error-button.secondary:hover {
            background: var(--primary-green);
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-content">
            <i class="fas fa-exclamation-triangle error-icon"></i>
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Sayfa Bulunamadı</h2>
            <p class="error-description">
                Üzgünüz, aradığınız sayfa bulunamadı. Sayfa kaldırılmış, adı değiştirilmiş veya geçici olarak kullanılamıyor olabilir.
            </p>
            <div class="error-buttons">
                <a href="/" class="error-button">
                    <i class="fas fa-home"></i>
                    Ana Sayfaya Dön
                </a>
                <a href="javascript:history.back()" class="error-button secondary">
                    <i class="fas fa-arrow-left"></i>
                    Geri Dön
                </a>
            </div>
        </div>
    </div>
</body>
</html>
