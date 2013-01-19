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
require_once('data/Album.php');

try {
	$artist = new Artist($_GET['artist']);
} catch (Exception $e) {
	$smarty->assign('pageheading', 'Artist not found.');
	$smarty->assign('details', 'The artist ' . $_GET['artist'] . ' was not found in the database.');
	$smarty->display('error.tpl');
	die();
}

if (!isset($this_user) || !$this_user->manages($artist->name)) {
	$smarty->assign('pageheading', 'Permission denied');
	$smarty->assign('error', 'You don\'t have permission to edit this artist\'s details.');
	$smarty->display('error.tpl');
	die();
}

$edit = false;
if (isset($_GET['album'])) {
	$edit = true;

	try {
		$album = new Album($_GET['album'], $artist->name);
	} catch (Exception $e) {
		$smarty->assign('pageheading', 'Album not found.');
		$smarty->assign('details', 'The album ' . $_GET['album'] . ' was not found in the database.');
		$smarty->display('error.tpl');
		die();
	}
	
}

$smarty->assign('artist', $artist);
$smarty->assign('edit', $edit);
if ($edit) {
	$name = $album->name;
	$smarty->assign('name', $name);
	$smarty->assign('image', $album->image);
	$smarty->assign('pageheading', '<a href="' . $artist->getURL() . '">' . $artist->name . '</a> &mdash; Edit Album');
} else {
	$smarty->assign('pageheading', '<a href="' . $artist->getURL() . '">' . $artist->name . '</a> &mdash; Add Album');
}

if (isset($_POST['submit'])) {

	if(!$edit) {
		if (empty($_POST['name'])) {
			$errors[] = 'An album name must be specified.';
		}
		$name = $_POST['name'];
	}

	if (empty($_POST['image'])) {
		$image = '';
	} else if (!preg_match('/^[a-z0-9\+\.\-]+\:/i', $_POST['image'])) {
		$errors[] = 'Cover image must be a valid URL';
	} else if (preg_match('/\s/', $_POST['homepage'])) {
		$errors[] = 'Cover image must be a URL, as such it cannot contain whitespace.';
	} else {
		$image = $_POST['image'];
	}

	if ($errors) {
		$smarty->assign('errors', $errors);
		$smarty->assign('image', $image);
		$smarty->assign('name', $_POST['name']);
	} else {
		if($edit) {
			$album->setImage($image);
		} else {
			$album = Album::create($name, $artist->name, $image);
		}
		// If the creation was successful send the user back to the view page
		header('Location: ' . $album->getURL());
	}
}
$smarty->display('album-add.tpl');
