(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 $( document ).ready( function( $ ) {
		 $( '#jco_userecho2bbpress_final' ).submit( function( event ) {
			 event.preventDefault();

			 var category_map = JSON.parse( $( '[name="jco[category_map]"]' ).val() );
			 var topics = JSON.parse( $( '[name="jco[topics]"]' ).val() );
			 var numtopics = $( '[name="jco[numtopics]"]' ).val();
			 var i = 1;
			 //console.log( numtopics );
			 topics.forEach(insertTopic);
			 //insertTopic(topics[0]);

			 async function insertTopic(topic){
			 	var postdata = {
				 	'action': 'jco_insert_topic',
				 	'category_map': JSON.stringify( category_map ),
				 	'topic_id': topic,
					'numtopics': numtopics,
					'thistopic': i
			 	};

			 	$.post(
				 	params.ajaxurl,
				 	postdata,
				 	function( response ) {
					 $('#ajax-status').prepend( response );
				 });
				 await sleep(5000);
				 i++;
			 }
			 function sleep(ms) {
				 return new Promise( resolve => setTimeout( resolve, ms ) );
			 }

	});
});

})( jQuery );
