<?php
$zip = new ZipArchive;

if ($zip->open(__DIR__ . '/vendor.zip') === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();
    echo "✅ Vendor extracted successfully";
} else {
    echo "❌ Failed to extract vendor";
}
