# Image EXIF Data Reader and Cleaner

This project provides two classes, `ExifImageReader` and `ExifCleaner`, designed to work with image files in a specific directory. The `ExifImageReader` reads and displays EXIF data from image files, while the `ExifCleaner` removes EXIF data from image files and saves clean copies.

## Classes

### ExifImageReader

The `ExifImageReader` class is responsible for reading and displaying EXIF data from image files in a specified directory. It supports JPEG, PNG, and GIF formats.

#### Example Usage

```php
<?php
$directoryPath = __DIR__ . '/out/';
$exifReader = new ExifImageReader($directoryPath);
$exifReader->displayExifData();
?>
```

### ExifCleaner

The `ExifCleaner` class is responsible for cleaning EXIF data from image files. It processes images in a given input directory and saves the cleaned images to an output directory.

#### Example Usage

```php
<?php
$inputPath = __DIR__ . '/image/';
$outputPath = __DIR__ . '/out/';

$cleaner = new ExifCleaner($inputPath, $outputPath);
$cleaner->cleanExif();

echo "EXIF data has been cleaned and images saved to: {$outputPath}\n";
?>
```

## Installation

To use these classes, you need to have PHP installed on your system. Clone or download the repository, then place the PHP files in your desired directory structure.

## Requirements

- PHP version 7.0 or higher
- PHP GD extension enabled (for image processing)
- PHP EXIF extension enabled (for reading EXIF data)
