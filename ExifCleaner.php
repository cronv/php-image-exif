<?php

/**
 * Class ExifCleaner
 *
 * This class is responsible for cleaning EXIF data from image files.
 */
class ExifCleaner
{
    private string $inputPath;
    private string $outputPath;

    /**
     * ExifCleaner constructor.
     *
     * @param string $inputPath  The path to the input directory containing images.
     * @param string $outputPath The path to the output directory where cleaned images will be saved.
     */
    public function __construct(string $inputPath, string $outputPath)
    {
        ini_set('memory_limit', '3G');
        $this->inputPath = rtrim($inputPath, '/') . '/';
        $this->outputPath = rtrim($outputPath, '/') . '/';

        if (!is_dir($this->outputPath)) {
            mkdir($this->outputPath, 0777, true);
        }
    }

    /**
     * Cleans EXIF data from the images in the input directory.
     */
    public function cleanExif(): void
    {
        $files = glob($this->inputPath . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        foreach ($files as $file) {
            $this->removeExif($file);
        }
    }

    /**
     * Removes EXIF data from a single image file.
     *
     * @param string $file The image file from which to remove EXIF data.
     */
    private function removeExif(string $file): void
    {
        $imageType = \exif_imagetype($file);
        $newFileName = $this->outputPath . basename($file);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $this->processJpeg($file, $newFileName);
                break;
            case IMAGETYPE_PNG:
                $this->processPng($file, $newFileName);
                break;
            case IMAGETYPE_GIF:
                $this->processGif($file, $newFileName);
                break;
            default:
                echo "Unsupported file type: {$file}\n";
                break;
        }
    }

    /**
     * Processes a JPEG image to remove EXIF data.
     *
     * @param string $file       The JPEG image file.
     * @param string $newFileName The output filename for the cleaned image.
     */
    private function processJpeg(string $file, string $newFileName): void
    {
        $image = imagecreatefromjpeg($file);
        if ($image !== false) {
            imagejpeg($image, $newFileName, 100); // 100 - maximum quality
            imagedestroy($image);
        }
    }

    /**
     * Processes a PNG image to remove EXIF data.
     *
     * @param string $file       The PNG image file.
     * @param string $newFileName The output filename for the cleaned image.
     */
    private function processPng(string $file, string $newFileName): void
    {
        $image = imagecreatefrompng($file);
        if ($image !== false) {
            imagealphablending($image, false);
            imagesavealpha($image, true);
            imagepng($image, $newFileName, 0); // 0 - maximum quality
            imagedestroy($image);
        }
    }

    /**
     * Processes a GIF image to remove EXIF data.
     *
     * @param string $file       The GIF image file.
     * @param string $newFileName The output filename for the cleaned image.
     */
    private function processGif(string $file, string $newFileName): void
    {
        $image = imagecreatefromgif($file);
        if ($image !== false) {
            imagegif($image, $newFileName);
            imagedestroy($image);
        }
    }
}
