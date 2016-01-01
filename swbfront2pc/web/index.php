<?php
include_once '../../classes/tools.php';
include_once '../../classes/database.php';
// initialize database
database::construct('localhost', 'root', 'technik,01', 'master');

// Get Top 100 users
// order by rating:
// prating = (playerpoints+heropoints)*(kills/deaths)
// prating = (250+100) * (210 / 250)
// 294 = 350 * 0,84
// higher prating = better ranking
$query = "SELECT "
        . "name,"
        . "stats_starts,"
        . "stats_finishes,"
        . "stats_kills,"
        . "stats_deaths,"
        . "stats_playerpoints,"
        . "stats_heropoints,"
        . "stats_lastPlayed,"
        . "(stats_kills/stats_deaths) as pkd,"
        . "((stats_playerpoints+stats_heropoints)*(stats_kills/stats_deaths)) as prating"
        . " FROM `users` WHERE "
        . "stats_timePlayed > 0 AND "
        . "stats_kills > 0 AND "
        . "stats_deaths > 0"
        . " ORDER BY "
        . "prating DESC"
        . " LIMIT 0,100";
$sql = database::query($query);
// if we found him insert the round
if (database::num_rows($sql) > 1) {
    $rank = 1;
    echo '//GamerTag,..Rank, Rating, Starts, Finishes, Kills, Deaths, HeroPoints, TotalTimePlayed,.. LastGamePlayed, LongestLiving,' . "\r\n";
    while ($row = database::fetch_object($sql)) {
        echo strip_tags($row->name) . ','
        . $rank.','
        . $row->stats_playerpoints.','
        . $row->stats_starts.','
        . $row->stats_finishes.','
        . $row->stats_kills.','
        . $row->stats_deaths.','
        . $row->stats_heropoints.','
        . 'Unknown,'
        . date("d.m.Y H:i:s",$row->stats_lastPlayed).','
        . 'Unknown,' . "\r\n";
        $rank++;
    }
}