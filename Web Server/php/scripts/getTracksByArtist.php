<?php
include('../classes/libraryBrowser.php');
$browser = new libraryBrowser();

$AlbumID = $_GET['ArtistID'];

$json_string = json_encode($browser->getTracksByArtist($ArtistID), JSON_PRETTY_PRINT);

echo $json_string;

?>