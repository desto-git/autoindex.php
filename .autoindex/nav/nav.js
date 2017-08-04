// allows up/down keyboard navigation
; const nav = (function(){ 'use strict';

let $html  = document.documentElement;
let $items = document.getElementsByClassName('entry');
let $line  = document.getElementsByClassName('selected')[0];
let index  = -1;



let event = {
	navigate: new Event('nav:userNavigation') // bound to $html
}; 




const getPosFromTop = function( $e ){
	let offsetTop = 0;
	while( $e !== null ){
		offsetTop += $e.offsetTop;
		$e = $e.offsetParent;
	}
	return offsetTop;
}

const scrollIntoView = function( $e ){
	const viewport = {
		top: $html.scrollTop,
		bottom: window.innerHeight + $html.scrollTop
	};
	
	const offsetTop = getPosFromTop( $e );
	const element = {
		top: offsetTop,
		bottom: offsetTop + $e.offsetHeight
	}
	
	if( element.top < viewport.top )
		$html.scrollTop = element.top;
	else if( element.bottom > viewport.bottom )
		$html.scrollTop = element.bottom - window.innerHeight;
}



const jumpToIndex = function( i ){
	// invalid index
	if( i < -1 || i > $items.length-1 ) return;
	
	index = i;
	document.activeElement.blur(); // in case a link is active (e.g. by using tab)
	
	// free selection because we only want one
	$line && $line.classList.remove('selected');
	$line = null;
	
	// just remove selection, nothing more
	if( i === -1 ) return;
	
	// highlight
	$line = $items[i];
	$line.classList.add('selected');
	
	scrollIntoView( $line );
}

$html.addEventListener( 'keydown', function( e ){
	
	if( e.which === 13 ){ // Enter
		if( $line !== null )
			window.location = $line.firstChild.firstChild.href;
	}
	
	if( e.which === 38 || e.which === 40 ){ // Arrow Up / Down
		e.preventDefault();
		
		if( e.which === 38 ){ // Up
			--index
			if( index < 0 ) index = 0;
			
		} else { // Down
			++index
			if( index > $items.length-1 ) index = $items.length-1;
		}
		
		jumpToIndex( index );
		$html.dispatchEvent(event.navigate);
	}
} );



return {
	jumpToIndex: jumpToIndex
};

}());