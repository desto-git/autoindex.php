<?php

$PARSE_URL = false; // use .url files as links
$TITLE = ''; // prefix for the title


/* for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
$path = urldecode( $_SERVER['REQUEST_URI'] ); // Unicode support

?><!DOCTYPE html>

<meta charset="utf-8">
<title><?= $TITLE, $path; ?></title>
<link rel="icon" href="/.autoindex/favicon.png">
<link rel="stylesheet" href="/.autoindex/style.css">

<nav><?php
	echo '<a href="/">', $_SERVER['HTTP_HOST'], '</a>';
	
	$breadcrumbs = explode( '/', $path );
	
	// remove first and last items as these are empty
	array_shift( $breadcrumbs );
	array_pop(   $breadcrumbs );
	
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
			
			if( is_dir( $d->path . $entry ) )
				$dirs[ $entry ] = 'dir';
			else
				$files[ $entry ] = 'file';
		}
		$d->close();
		
		// sort
		ksort( $dirs,  SORT_NATURAL | SORT_FLAG_CASE );
		ksort( $files, SORT_NATURAL | SORT_FLAG_CASE );
		
		// sometimes the .. folder isn't at the top (e.g. another folder starts with '(')
		// this way it will always be the first item in the list
		if( $path !== '/' ) $dirs = ['..' => 'dir'] + $dirs;
		$items = array_merge( $dirs, $files );
		
		foreach( $items as $name => $type ){
			$file = substr( $path, 1 ) . $name;
			$href = './'. $name; // ./ so it works with leading spaces
			
			$mime = $size = $date = '';
			
			if( $type === 'file' ){
				$mime = mime_content_type( $file );
				$size = filesize( $file );
				
				// get file extension
				$ext = substr( $name, strrpos( $name, '.' ) + 1 );
				
				if( $ext === 'url' && $PARSE_URL ){
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