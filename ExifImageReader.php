<?php

/**
 * Class ExifImageReader
 * Responsible for reading and displaying EXIF data from image files in a specified directory.
 */
class ExifImageReader {
    /**
     * @var string The directory containing the images.
     */
    private string $directory;

    /**
     * ExifImageReader constructor.
     *
     * @param string $directory The directory containing the image files.
     */
    public function __construct(string $directory) {
        $this->directory = rtrim($directory, '/') . '/';
    }

    /**
     * Retrieves images with their corresponding EXIF data.
     *
     * @return array An array of images with their EXIF data.
     */
    public function getImagesWithExif(): array {
        $images = glob($this->directory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $result = [];

        foreach ($images as $image) {
            if (function_exists('exif_read_data')) {
                $exifData = @exif_read_data($image);
                $result[] = [
                    'name' => basename($image),
                    'exif' => $this->formatExifData($exifData)
                ];
            }
        }

        return $result;
    }

    /**
     * Formats the EXIF data into a string.
     *
     * @param mixed $exifData The EXIF data to format.
     * @return string The formatted EXIF data string.
     */
    private function formatExifData($exifData): string {
        if ($exifData === false) {
            return 'No data';
        }

        // Remove some keys to reduce volume (optional)
        unset($exifData['COMPUTED']);

        return implode(', ', array_map(function($key) use ($exifData) {
            return "$key: " . (isset($exifData[$key]) ? $exifData[$key] : 'No data');
        }, array_keys($exifData)));
    }

    /**
     * Displays the EXIF data in an HTML table.
     */
    public function displayExifData(): void {
        $images = $this->getImagesWithExif();

        echo '<table border="1">';
        echo '<tr><th>Image Name</th><th>EXIF Data</th></tr>';

        foreach ($images as $imageData) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($imageData['name']) . '</td>';
            echo '<td>' . htmlspecialchars($imageData['exif']) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    }
}
