<?php

/* for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$CFG = [];
require('.autoindex/config.php');
include('.autoindex.cfg/config.php');

// the favicon path should start from .autoindex.cfg if it is set
if( $CFG['favicon'] === '' )
	$CFG['favicon'] = '/.autoindex/favicon.png';
else
	$CFG['favicon'] = '/.autoindex.cfg/' . $CFG['favicon'];



$path = urldecode( $_SERVER['REQUEST_URI'] ); // Unicode support

?><!DOCTYPE html>

<meta charset="utf-8">
<title><?= $CFG['title'], $path; ?></title>
<link rel="icon" href="<?= $CFG['favicon']; ?>">
<link rel="stylesheet" href="/.autoindex/style.css">
<link rel="stylesheet" href="/.autoindex/nav/nav.css">
<link rel="stylesheet" href="/.autoindex/search/search.css">
<?php
	if( file_exists('.autoindex.cfg/style.css') )
		echo '<link rel="stylesheet" href="/.autoindex.cfg/style.css">';
?>

<nav><?php
	echo '<a href="/">', $_SERVER['HTTP_HOST'], '</a>';
	
	$breadcrumbs = explode( '/', $path );
	
	// remove first and last items as these are empty
	array_shift( $breadcrumbs );
	array_pop  ( $breadcrumbs );
	
	$url = '/';
	foreach( $breadcrumbs as $crumb ){
		$url .= $crumb .'/';
		echo '<a href="', $url ,'">', $crumb ,'</a>';
	}
?></nav>

<table>
	<tr>
		<th class="name">Filename</th>
		<th class="size">Filesize</th>
		<th class="date">Last Modified</th>
	</tr>
	<?php
		// sort folders before files
		$dirs  = [];
		$files = [];
		
		// read directory
		$d = dir( '.'. $path );
		while( ( $entry = $d->read() ) !== false ){
			
			// ignore hidden files and folders
			if( $entry[0] === '.' )
				continue;
			
			if( is_dir( $d->path . $entry ) ) $dirs [] = $entry;
			else                              $files[] = $entry;
		}
		$d->close();
		
		// sort
		sort( $dirs,  SORT_NATURAL | SORT_FLAG_CASE );
		sort( $files, SORT_NATURAL | SORT_FLAG_CASE );
		
		// show .. folder, unless when already at document root
		if( $path !== '/' ) array_unshift( $dirs, '..' );
		
		$items = array_merge( $dirs, $files );
		
		foreach( $items as $name ){
			$file = substr( $path, 1 ) . $name;
			$href = './'. $name; // ./ so it works with leading spaces
			
			$type = is_file( $file ) ? 'file' : 'dir';
			$mime = $size = $date = '';
			
			if( $type === 'file' ){
				$mime = mime_content_type( $file );
				$size = filesize( $file );
				
				// get file extension
				$ext = '';
				if( $pos = strrpos( $name, '.' ) ) // has file extension in the first place
					$ext = substr( $name, $pos + 1 );
				
				if( $CFG['parse_urldecode'] && $ext === 'url' ){
					$href = file_get_contents( $file );
					$mime = 'text/x-url'; // custom file type
				}
			}
			
			if( $name !== '..' ){
				$date = date('Y-m-d H:i:s', filemtime( $file ) );
				$date = '<time datetime="'. $date .'">'. $date .'</time>';
			}
			
			echo
				'<tr class="entry">',
					'<td class="name"><a class="item ', $type ,'" href="', $href ,'" data-mime="', $mime ,'">', $name ,'</a></td>',
					'<td class="size">', $size ,'</td>',
					'<td class="date">', $date ,'</td>',
				'</tr>';
		}
	?>
</table>

<footer>
	<a href="https://github.com/desto-git/autoindex.php">autoindex.php | MIT</a>
</footer>

<script src="/.autoindex/nav/nav.js"></script>
<script src="/.autoindex/search/search.js"></script>
<script src="/.autoindex/config.js"></script>
<?php
	if( file_exists('.autoindex.cfg/config.js') )
		echo '<script src="/.autoindex.cfg/config.js"></script>';
?>