<?php
/* Old URL kept working -> permanent redirect to the new location. */
$qs = $_SERVER['QUERY_STRING'] ?? '';
header('Location: pages/contact.php' . ($qs !== '' ? '?' . $qs : ''), true, 301);
exit;
