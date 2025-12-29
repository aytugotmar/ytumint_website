<?php
// Uploads klasörüne doğrudan erişimi engelle
header('HTTP/1.0 403 Forbidden');
die('Erişim reddedildi.');
?>
