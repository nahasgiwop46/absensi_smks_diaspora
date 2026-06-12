<?php
// app/Controllers/FaviconController.php
namespace App\Controllers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FaviconController extends BaseController
{
    public function generate($size = 32)
    {
        $manager = new ImageManager(new Driver());
        
        // Baca gambar dari folder uploads
        $image = $manager->read(FCPATH . 'uploads/logo/logo.jpeg');
        
        // Resize ke ukuran favicon
        $image->resize($size, $size);
        
        // Output langsung ke browser
        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setBody($image->toPng());
    }
}