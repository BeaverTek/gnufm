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

try {
	$artist = new Artist(urldecode($_GET['artist']));
} catch (exception $e) {
        $smarty->assign('error', 'Artist not found.');
        $smarty->assign('details', 'The artist '.($_GET['artist']).' was not found in the database.');
	$smarty->display('error.tpl');
	die();
}

if(!isset($this_user) || !$this_user->manages($artist->name)) {
	$smarty->assign('error', 'Permission denied');
	$smarty->assign('error', 'You don\'t have permission to edit this artist\'s details.');
	die();
}

if (isset($_POST['submit'])) {
	$artist->setBiographySummary($_POST['bio_summary']);
	$artist->setBiography($_POST['bio_content']);
	if (!empty($_POST['homepage']) && !preg_match('/^[a-z0-9\+\.\-]+\:/i', $_POST['homepage'])) {
		$errors[] = 'Home page must be a valid URL';
	} elseif (!empty($_POST['homepage']) && preg_match('/\s/', $_POST['homepage'])) {
		$errors[] = 'Home page must be a URL, as such it cannot contain whitespace.';
	} else {
		$artist->setHomepage($_POST['homepage']);
	}

	if (!empty($_POST['image']) && !preg_match('/^[a-z0-9\+\.\-]+\:/i', $_POST['image'])) {
		$errors[] = 'Image must be a valid URL';
	} elseif (!empty($_POST['image']) && preg_match('/\s/', $_POST['image'])) {
		$errors[] = 'Image must be a URL, as such it cannot contain whitespace.';
	} else {
		$artist->setImage($_POST['image']);
	}

	
	if($errors) {
		$smarty->assign('errors', $errors);
	} else {
		// If the editing was successful send the user back to the view page
		header('Location: ' . $artist->getURL());
	}
}

$smarty->assign('name', $artist->name);
$smarty->assign('id', $artist->id);
$smarty->assign('bio_summary', $artist->bio_summary);
$smarty->assign('bio_content', $artist->bio_content);
$smarty->assign('homepage', $artist->homepage);
$smarty->assign('image', $artist->image_medium);

$smarty->display("artist-manage.tpl");
?>