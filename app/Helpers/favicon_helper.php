<?php
function generateFavicon($sourcePath, $size = 32) {
    // Cek tipe file
    $mime = mime_content_type($sourcePath);
    
    switch($mime) {
        case 'image/jpeg':
            $srcImg = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $srcImg = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $srcImg = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }
    
    // Resize
    $newImg = imagecreatetruecolor($size, $size);
    
    // Transparansi untuk PNG
    if($mime == 'image/png') {
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
    }
    
    // Resize gambar
    imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $size, $size, imagesx($srcImg), imagesy($srcImg));
    
    // Simpan
    $faviconPath = FCPATH . 'favicon_' . $size . '.png';
    imagepng($newImg, $faviconPath);
    
    // Bersihkan
    imagedestroy($srcImg);
    imagedestroy($newImg);
    
    return true;
}