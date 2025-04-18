<?php

$uploadDir = '/home/onixuvjm/public_html/uploads/blog/';
// $uploadDir = 'uploads/blog/';
$uploadUrl = 'https://onixpackaging.com/uploads/blog/';  // Base URL to access the images
// $uploadUrl = 'http://127.0.0.1:8000/uploads/blog/';  // Base URL to access the images


if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $tempPath = $_FILES['file']['tmp_name'];
    // $fileName = basename($_FILES['file']['name']);
    // $fileName = time() . '1.' . $_FILES['file']->getClientOriginalExtension();
    $fileName = time() . '1.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);


    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($tempPath, $targetPath)) {
        // Respond with JSON containing the file URL
        echo json_encode(['location' => $uploadUrl . $fileName]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to move uploaded file.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Failed to upload file.']);
}
