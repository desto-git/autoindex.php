// include a search field
; var search = (function(){ 'use strict';

// clear the search field automatically
var clear = {
	id: null,
	timeout: 1000
}



var $html   = document.documentElement;
var $items  = document.getElementsByClassName('entry');

var items = [];
for( var i = 0; i < $items.length; ++i ){
	// toLowerCase() for case independent search
	items.push( $items[i].firstChild.firstChild.innerText.toLowerCase() );
}



// insert search field to the filename column
var $search = document.createElement('span');
$search.id = 'search';

var $filename = document.querySelector('th.name');
$filename.appendChild( $search );



let search = function( find ){
	// case independent
	find = find.toLowerCase();
	
	// look for files starting with the search term
	for( var i = 0; i < items.length; ++i ){
		if( items[i].indexOf( find ) === 0 ){
			nav.jumpToIndex( i );
			return;
		}
	}
	
	// no matches, remove selection
	nav.jumpToIndex( -1 );
}

$html.addEventListener( 'keypress', function( e ){
	if( document.activeElement === $search ) return;

	// focus search field when starting typing
	// 0  = non-character input (FF only, other browser won't fire in the first place)
	// 8  = Backspace
	// 13 = Enter
	if( !e.ctrlKey && e.which != 0 && e.which != 8 && e.which != 13 ){
		clearTimeout( clear.id );
		clear.id = setTimeout( function(){
			$search.innerText = '';
		}, clear.timeout );
		
		$search.innerText += String.fromCharCode( e.which );
		search( $search.innerText );
	}
} );



$html.addEventListener( 'nav:userNavigation', function(){
	$search.innerText = '';
} );



return {};

}());