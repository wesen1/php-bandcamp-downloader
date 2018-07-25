<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 wesen
 * @author wesen
 * @license MIT
 */

$loader = require_once(__DIR__ ."/vendor/autoload.php");
$loader->addPsr4("BandcampMusicDownloader\\", __DIR__ . "/lib");

use BandcampMusicDownloader\BandcampMusicDownloader;

if ($argc != 2)
{
    echo "Usage: php BandcampMusicDownloader <albumUrl>\n";
    exit(0);
}

$albumUrl = $argv[1];

try
{
    $bandCampMusicDownloader = new BandcampMusicDownloader();
}
catch (\ErrorException $_exception)
{
    echo "Error while creating BandcampMusicDownloader: " . $_exception->getMessage() . "\n";
}
$bandCampMusicDownloader->downloadTracks($albumUrl);
