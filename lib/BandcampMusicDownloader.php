<?php
/**
 * @file
 * @version 0.1
 * @copyright 2018 wesen
 * @author wesen
 * @license MIT
 */

namespace BandcampMusicDownloader;

use Curl\Curl;
use Sunra\PhpSimple\HtmlDomParser;

/**
 * Downloads all tracks of an album from the website "bandcamp.com".
 */
class BandcampMusicDownloader
{
    /**
     * The curl client
     *
     * @var Curl $curl
     */
    private $curl;

    /**
     * BandcampMusicDownloader constructor.
     *
     * @throws \ErrorException
     */
    public function __construct()
    {
        $this->curl = new Curl();
    }

    /**
     * Downloads all tracks of an album from the website "bandcamp.com".
     *
     * @param String $_url The main url to the bandcamp album
     */
    public function downloadTracks(String $_url)
    {
        echo "Fetching album title ... ";
        $albumTitle = $this->getAlbumTitle($_url);
        echo "Done\n";

        echo "Fetching artist url prefix ... ";
        $urlPrefix = $this->getUrlPrefix($_url);
        echo "Done\n";

        echo "Loading main url ... ";
        $dom = HtmlDomParser::file_get_html($_url);
        echo "Done\n";

        echo "Finding track links ... ";
        $trackLinks = $dom->find("div.trackView table.track_list div.title a");
        echo "Done\n";

        echo "Downloading tracks ...\n";
        $outputDirectory = __DIR__ . "/../" . $albumTitle;
        mkdir($outputDirectory);
        foreach ($trackLinks as $trackLink)
        {
            $trackUrl = $urlPrefix . $trackLink->getAttribute("href");
            $this->downloadTrack($trackUrl, $outputDirectory);
        }
        echo "Done\n";
    }

    /**
     * Returns the album title from the main url of the bandcamp album.
     *
     * @param String $_mainUrl The main url of the bandcamp album
     *
     * @return String The album title
     */
    private function getAlbumTitle(String $_mainUrl): String
    {
        $matches = array();
        preg_match("/https:\/\/[^\.]+\.bandcamp\.com\/album\/(.+)/", $_mainUrl, $matches);
        $albumTitle = $matches[1];

        return $albumTitle;
    }

    /**
     * Returns the url prefix for all albums and tracks from the artist.
     *
     * @param String $_mainUrl The main url of the bandcamp album
     *
     * @return String The url prefix for all albums and tracks from the artist
     */
    private function getUrlPrefix(String $_mainUrl): String
    {
        $matches = array();
        preg_match("/(.*bandcamp\.com).*/", $_mainUrl, $matches);
        $urlPrefix = $matches[1];

        return $urlPrefix;
    }

    /**
     * Downloads a single track and saves it to a file in a specific output directory.
     *
     * @param String $_url The url to the track
     * @param String $_outputDirectory The output directory
     */
    private function downloadTrack(String $_url, String $_outputDirectory)
    {
        // Fetch track name
        $matches = array();
        preg_match("/https:\/\/[^\.]+\.bandcamp\.com\/track\/(.+)/", $_url, $matches);
        $trackName = $matches[1];

        echo "  Downloading track \"" . $trackName . "\" ... ";
        $html = HtmlDomParser::file_get_html($_url);

        $matches = array();
        preg_match("/file\":{\"mp3-128\":\"([^\}]*)\"}/", $html, $matches);
        $audioFileLink = $matches[1];

        $this->curl->get($audioFileLink);

        file_put_contents($_outputDirectory . "/" . $trackName, $this->curl->response);

        echo "Done\n";
    }
}
