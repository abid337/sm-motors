<?php
$file = 'd:/xampp/htdocs/smart-cms/storage/app/ai/knowledge/dealership.json';
if (file_exists($file)) {
    unlink($file);
    echo "Fake data file deleted.";
} else {
    echo "File already gone.";
}
