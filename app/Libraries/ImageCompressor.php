<?php

namespace App\Libraries;

/**
 * ImageCompressor Library
 * 
 * ลดขนาดรูปภาพเอกสารก่อนอัปโหลดเพื่อให้ไฟล์ไม่เกิน limit ของ server
 */
class ImageCompressor
{
    protected $maxWidth = 1600;      // ความกว้างสูงสุด (pixels)
    protected $maxHeight = 2000;     // ความสูงสูงสุด (pixels) - สำหรับเอกสาร A4
    protected $maxFileSize = 0.9;    // ขนาดไฟล์สูงสุด (MB) - ปรับลดเพื่อมุดผ่าน Nginx 1MB
    protected $jpegQuality = 85;     // คุณภาพ JPEG (1-100)
    protected $pngQuality = 6;       // คุณภาพ PNG (0-9, 9 = เล็กสุด)

    /**
     * ตั้งค่าขนาดสูงสุด
     */
    public function setMaxDimensions($width, $height)
    {
        $this->maxWidth = $width;
        $this->maxHeight = $height;
        return $this;
    }

    /**
     * ตั้งค่าขนาดไฟล์สูงสุด (MB)
     */
    public function setMaxFileSize($sizeMB)
    {
        $this->maxFileSize = $sizeMB;
        return $this;
    }

    /**
     * ตั้งค่าคุณภาพ JPEG
     */
    public function setJpegQuality($quality)
    {
        $this->jpegQuality = max(1, min(100, $quality));
        return $this;
    }

    /**
     * Compress รูปภาพ
     * 
     * @param string $sourcePath Path ไฟล์ต้นทาง
     * @param string|null $destPath Path ปลายทาง (ถ้าไม่ระบุจะ overwrite ต้นทาง)
     * @return array ['success' => bool, 'message' => string, 'original_size' => int, 'new_size' => int]
     */
    public function compress($sourcePath, $destPath = null)
    {
        // Check for GD library
        if (!extension_loaded('gd')) {
            return ['success' => false, 'message' => 'เครื่องเซิร์ฟเวอร์ไม่ได้ติดตั้ง GD Library (จำเป็นสำหรับการปรับขนาดรูปภาพ)'];
        }

        if (!file_exists($sourcePath)) {
            return ['success' => false, 'message' => 'ไม่พบไฟล์ต้นทาง'];
        }

        $originalSize = filesize($sourcePath);
        $maxBytes = $this->maxFileSize * 1024 * 1024;

        // ถ้าไฟล์เล็กกว่า limit อยู่แล้ว ไม่ต้อง compress
        if ($originalSize <= $maxBytes) {
            if ($destPath && $destPath !== $sourcePath) {
                copy($sourcePath, $destPath);
            }
            return [
                'success' => true,
                'message' => 'ไฟล์มีขนาดเล็กอยู่แล้ว ไม่ต้อง compress',
                'original_size' => $originalSize,
                'new_size' => $originalSize,
                'compressed' => false
            ];
        }

        // ตรวจสอบประเภทไฟล์
        $mimeType = mime_content_type($sourcePath);
        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($mimeType, $imageTypes)) {
            // ถ้าเป็น PDF หรือไฟล์อื่น ไม่สามารถ compress ได้
            return [
                'success' => false, 
                'message' => 'ไม่สามารถ compress ไฟล์ประเภท ' . $mimeType,
                'original_size' => $originalSize
            ];
        }

        try {
            // โหลดรูปภาพ
            $image = $this->loadImage($sourcePath, $mimeType);
            if (!$image) {
                return ['success' => false, 'message' => 'ไม่สามารถโหลดรูปภาพได้'];
            }

            // ดึงขนาดเดิม
            $origWidth = imagesx($image);
            $origHeight = imagesy($image);

            // คำนวณขนาดใหม่
            $newWidth = $origWidth;
            $newHeight = $origHeight;

            // Resize ถ้าใหญ่เกิน
            if ($origWidth > $this->maxWidth || $origHeight > $this->maxHeight) {
                $ratioWidth = $this->maxWidth / $origWidth;
                $ratioHeight = $this->maxHeight / $origHeight;
                $ratio = min($ratioWidth, $ratioHeight);

                $newWidth = round($origWidth * $ratio);
                $newHeight = round($origHeight * $ratio);
            }

            // สร้างรูปใหม่
            $newImage = imagecreatetruecolor($newWidth, $newHeight);

            // สำหรับ PNG - รักษาความโปร่งใส
            if ($mimeType === 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
                imagefill($newImage, 0, 0, $transparent);
            }

            // Resize
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            // กำหนด path ปลายทาง
            $outputPath = $destPath ?: $sourcePath;

            // บันทึกไฟล์ - พยายามลด quality จนกว่าจะได้ขนาดที่ต้องการ
            $quality = $this->jpegQuality;
            $attempts = 0;
            $maxAttempts = 5;

            do {
                // บันทึกเป็น JPEG เพื่อลดขนาด (เว้นแต่เป็น PNG และต้องการรักษาความโปร่งใส)
                if ($mimeType === 'image/png') {
                    imagepng($newImage, $outputPath, $this->pngQuality);
                } else {
                    imagejpeg($newImage, $outputPath, $quality);
                }

                $newSize = filesize($outputPath);
                
                // ถ้ายังใหญ่เกิน ลด quality ลง
                if ($newSize > $maxBytes && $mimeType !== 'image/png') {
                    $quality -= 10;
                    $attempts++;
                } else {
                    break;
                }
            } while ($quality > 20 && $attempts < $maxAttempts);

            // ถ้ายังใหญ่เกินและเป็น PNG ให้แปลงเป็น JPEG
            if ($newSize > $maxBytes && $mimeType === 'image/png') {
                // เปลี่ยนนามสกุลเป็น jpg
                $jpgPath = preg_replace('/\.png$/i', '.jpg', $outputPath);
                if ($jpgPath === $outputPath) {
                    $jpgPath = $outputPath . '.jpg';
                }

                // สร้างพื้นหลังขาวสำหรับ PNG ที่มีความโปร่งใส
                $whiteImage = imagecreatetruecolor($newWidth, $newHeight);
                $white = imagecolorallocate($whiteImage, 255, 255, 255);
                imagefill($whiteImage, 0, 0, $white);
                imagecopy($whiteImage, $newImage, 0, 0, 0, 0, $newWidth, $newHeight);

                imagejpeg($whiteImage, $jpgPath, $this->jpegQuality);
                imagedestroy($whiteImage);

                // ลบไฟล์ PNG เดิม
                if (file_exists($outputPath) && $outputPath !== $sourcePath) {
                    @unlink($outputPath);
                }

                $outputPath = $jpgPath;
                $newSize = filesize($outputPath);

                log_message('info', "ImageCompressor: แปลง PNG เป็น JPG: {$outputPath}");
            }

            // Cleanup
            imagedestroy($image);
            imagedestroy($newImage);

            $compressionRatio = round((1 - ($newSize / $originalSize)) * 100, 1);

            log_message('info', "ImageCompressor: Compressed {$sourcePath}");
            log_message('info', "ImageCompressor: {$this->formatSize($originalSize)} -> {$this->formatSize($newSize)} (ลดลง {$compressionRatio}%)");

            return [
                'success' => true,
                'message' => "Compress สำเร็จ: ลดจาก {$this->formatSize($originalSize)} เหลือ {$this->formatSize($newSize)}",
                'original_size' => $originalSize,
                'new_size' => $newSize,
                'compression_ratio' => $compressionRatio,
                'new_dimensions' => "{$newWidth}x{$newHeight}",
                'output_path' => $outputPath,
                'compressed' => true
            ];

        } catch (\Exception $e) {
            log_message('error', "ImageCompressor Error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Compress ไฟล์ที่ upload มา (CodeIgniter UploadedFile)
     */
    public function compressUploadedFile($uploadedFile)
    {
        if (!$uploadedFile || !$uploadedFile->isValid()) {
            return ['success' => false, 'message' => 'ไฟล์ไม่ถูกต้อง'];
        }

        return $this->compress($uploadedFile->getTempName());
    }

    /**
     * โหลดรูปภาพตาม mime type
     */
    protected function loadImage($path, $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            default:
                return false;
        }
    }

    /**
     * Format ขนาดไฟล์
     */
    protected function formatSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
