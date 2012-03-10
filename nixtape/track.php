<?php

/* GNU FM -- a free network service for sharing your music listening habits

   Copyright (C) 2009 Free Software Foundation, Inc

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

require_once('database.php');
require_once('templating.php');
require_once('data/sanitize.php');
require_once('data/Server.php');
require_once('data/TagCloud.php');
require_once('track-menu.php');

$track = new Track($_GET['track'], $_GET['artist']);
$smarty->assign('track', $track);

$album = new Album($track->album_name, $track->artist_name);
$smarty->assign('album', $album);

try {
	$artist = new Artist($track->artist_name);
} catch (Exception $e) {
	$smarty->assign('pageheading', 'Artist not found.');
	$smarty->assign('details', 'The artist ' . $track->artist_name . ' was not found in the database.');
	$smarty->display('error.tpl');
	die();
}

$smarty->assign('artist', $artist);
$smarty->assign('flattr_uid', $artist->flattr_uid);
$smarty->assign('url', $track->getURL());
$smarty->assign('pagetitle', $artist->name . ' : ' . $track->name);

if (isset($this_user) && $this_user->manages($artist->name)) {
	$smarty->assign('edit_link', $track->getEditURL());
}

if ($track->duration) {
	// Give the duration in MM:SS
	$mins = floor($track->duration / 60);
	$sec = floor($track->duration % 60);
	if (strlen($sec) == 1) {
		$sec = '0' . $sec;
	}
	$duration = $mins . ':' . $sec;
	$smarty->assign('duration', $duration);
}

$smarty->assign('extra_head_links', array(
		array(
			'rel'   => 'meta',
			'type'  => 'application/rdf+xml',
			'title' => 'Track Metadata',
			'href'  => $base_url . '/rdf.php?fmt=xml&page=' . urlencode(str_replace($base_url, '', $track->getURL()))
			)
		));

try {
	$tagCloud = TagCloud::generateTagCloud('Tags', 'tag', 10, $track->name, 'track');
} catch ( Exception $e) {
	$tagCloud = array();
}
$smarty->assign('tagcloud', $tagCloud);

$submenu = track_menu($track, 'Overview');
$smarty->assign('submenu', $submenu);
$smarty->assign('headerfile', 'track-header.tpl');
$smarty->display('track.tpl');
