<?php
// Logo ve favicon ayarlarını yükle
if (!isset($siteLogo) || !isset($siteFavicon)) {
    $logoSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_logo'");
    $faviconSetting = $db->fetchOne("SELECT setting_value FROM site_settings WHERE setting_key = 'site_favicon'");
    $siteLogo = $logoSetting['setting_value'] ?? '';
    $siteFavicon = $faviconSetting['setting_value'] ?? '';
}
?>
