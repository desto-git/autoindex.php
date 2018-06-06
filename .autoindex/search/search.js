// include a quick search ()
; const search = (function(){ 'use strict';

// clear the search field automatically
let cfg = {
	// timeout: number
}

const setConfig = function( data ){
	if( !data ) return;

	if( typeof data.timeout === 'number' ) cfg.timeout = data.timeout;
}



let $html   = document.documentElement;
let $items  = document.getElementsByClassName('entry');

let items = [];
for( let i = 0; i < $items.length; ++i ){
	// toLowerCase() for case independent search
	items.push( $items[i].firstChild.firstChild.innerText.toLowerCase() );
}



// insert search field to the filename column
let $search = document.createElement('span');
$search.id = 'search';

let $filename = document.querySelector('th.name');
$filename.appendChild( $search );



const search = function( find ){
	// case independent
	find = find.toLowerCase();

	// look for files starting with the search term
	for( let i = 0; i < items.length; ++i ){
		if( items[i].indexOf( find ) === 0 ){
			nav.jumpToIndex( i );
			return;
		}
	}

	// no matches, remove selection
	nav.jumpToIndex( -1 );
}



$html.addEventListener( 'keydown', function( e ){
	if( e.which === 8 ){ // backspace
		if( $search.innerHTML === '' ) return;

		$search.innerHTML = '';
		e.preventDefault();
	}
} );

let timeoutId = null;
$html.addEventListener( 'keypress', function( e ){
	if( document.activeElement === $search ) return;

	// focus search field when starting typing
	// 0  = non-character input (FF only, other browser won't fire in the first place)
	// 8  = Backspace
	// 13 = Enter
	if( !e.ctrlKey && e.which != 0 && e.which != 8 && e.which != 13 ){
		clearTimeout( timeoutId );
		timeoutId = setTimeout( function(){
			$search.innerText = '';
		}, cfg.timeout );

		$search.innerText += String.fromCharCode( e.which );
		search( $search.innerText );
	}
} );



$html.addEventListener( 'nav:userNavigation', function(){
	$search.innerText = '';
} );



return {
	setConfig: setConfig
};

}());
