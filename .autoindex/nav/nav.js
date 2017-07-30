// allows up/down keyboard navigation
; var nav = (function(){ 'use strict';

var $html  = document.documentElement;
var $items = document.getElementsByClassName('entry');
var $line  = document.getElementsByClassName('selected')[0];
var index  = -1;



var event = {
	navigate: new Event('nav:userNavigation') // bound to $html
}; 




function getPosFromTop( $e ){
	var offsetTop = 0;
	while( $e !== null ){
		offsetTop += $e.offsetTop;
		$e = $e.offsetParent;
	}
	return offsetTop;
}

function scrollIntoView( $e ){
	var viewport = {
		top: $html.scrollTop,
		bottom: window.innerHeight + $html.scrollTop
	};
	
	var offsetTop = getPosFromTop( $e );
	var element = {
		top: offsetTop,
		bottom: offsetTop + $e.offsetHeight
	}
	
	if( element.top < viewport.top )
		$html.scrollTop = element.top;
	else if( element.bottom > viewport.bottom )
		$html.scrollTop = element.bottom - window.innerHeight;
}



function jumpToIndex( i ){
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