<?php
if(isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    $filePath = "uploads/" . $filename;

    if(file_exists($filePath)) {
        unlink($filePath);
        echo "File $filename has been deleted.";
    } else {
        echo "File $filename does not exist.";
    }
} else {
    echo "No filename specified.";
}
?>
