<?php

// Wether or not .url files will redirect to its content rather than the file itself.
// A .url file ist just a plain text file with a .url extension containing only a link.
$CFG['parse_url'] = true;

// The prefix to use for the title.
$CFG['title'] = '';

// Path to a favicon, relative from .autoindex.cfg/
// try Ctrl + F5 or restarting your browser if it doesn't change right away
$CFG['favicon'] = ''; // = '/.autoindex/favicon.png'

$CFG['timestamp_oldest'] = 60 * 60 * 24 * 365 * 1; // = 1 year; files older than this are displayed with min_opacity
$CFG['timestamp_min_opacity'] = 0.4; // the minimum opacity from 0 to 1, where 0 = invisible and 1 = opaque

?>