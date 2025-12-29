<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Sunucu Hatası | YTU MINT</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo (int)@filemtime(__DIR__ . '/assets/css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f57c00, #e65100);
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
            color: #f57c00;
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 32px;
            color: #e65100;
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
            color: #f57c00;
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
            background: #f57c00;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .error-button:hover {
            background: #e65100;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .error-button.secondary {
            background: white;
            color: #f57c00;
            border: 2px solid #f57c00;
        }
        .error-button.secondary:hover {
            background: #f57c00;
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-content">
            <i class="fas fa-server error-icon"></i>
            <h1 class="error-code">500</h1>
            <h2 class="error-title">Sunucu Hatası</h2>
            <p class="error-description">
                Üzgünüz, sunucuda bir hata oluştu. Lütfen daha sonra tekrar deneyin. Sorun devam ederse sistem yöneticisi ile iletişime geçin.
            </p>
            <div class="error-buttons">
                <a href="/" class="error-button">
                    <i class="fas fa-home"></i>
                    Ana Sayfaya Dön
                </a>
                <a href="javascript:location.reload()" class="error-button secondary">
                    <i class="fas fa-sync-alt"></i>
                    Tekrar Dene
                </a>
            </div>
        </div>
    </div>
</body>
</html>
