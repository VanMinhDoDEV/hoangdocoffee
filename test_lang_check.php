<?php

$messages = require __DIR__ . '/lang/en/messages.php';

echo "Total keys: " . count($messages) . "\n";
if (isset($messages['display_options'])) {
    echo "display_options: " . $messages['display_options'] . "\n";
} else {
    echo "display_options key NOT FOUND in en/messages.php\n";
}

$vi_messages = require __DIR__ . '/lang/vi/messages.php';
if (isset($vi_messages['display_options'])) {
    echo "VI display_options: " . $vi_messages['display_options'] . "\n";
} else {
    echo "display_options key NOT FOUND in vi/messages.php\n";
}
