<?php
$upload_dir = 'upload/';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'name';

function getUploadedFiles() {
    global $upload_dir, $sort_by;
    $files = array();

    if (is_dir($upload_dir)) {
        if ($dh = opendir($upload_dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    $file_path = $upload_dir . $file;
                    $files[] = array(
                        'name' => $file,
                        'type' => mime_content_type($file_path),
                        'date' => date("Y-m-d H:i:s", filemtime($file_path)),
                        'size' => filesize($file_path)
                    );
                }
            }
            closedir($dh);
        }
    }

    // Sắp xếp danh sách tệp
    usort($files, function($a, $b) use ($sort_by) {
        return $a[$sort_by] <=> $b[$sort_by];
    });

    return $files;
}

$files = getUploadedFiles();

foreach ($files as $file) {
    echo "<tr>";
    echo "<td>{$file['name']}</td>";
    echo "<td>{$file['type']}</td>";
    echo "<td>{$file['date']}</td>";
    echo "<td>{$file['size']} bytes</td>";
    echo "</tr>";
}
?>
