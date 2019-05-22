jQuery( function( $ ) {

  // FitVids
  $( '.entry-content' ).fitVids();


  // Sticky Header options
  var options = {
    offset: 725,
  };
  // Initialize Sticky Header with options
  var header = new Headhesive( '.nav-secondary', options );


  // Sticky sidebar: make room for sticky header. Note: match scroll comparison to offset Sticky Header options (above)
  $( window ).scroll( function() {
    var scroll = $( window ).scrollTop();
    if ( scroll > 725 ) {
      $( '.sidebar' ).addClass( 'sidebar-sticky-nav' );
    }
    else {
      $( '.sidebar' ).removeClass( 'sidebar-sticky-nav' );
    }

  } );


  /** FooBox */
//   $( '.fbx-instance' ).on( 'foobox.beforeShow', function( e ) {
//     // var $element = $(e.fb.item.element),		//the anchor tag
//     $fooboxInstance = e.fb.instance,		//the foobox instance
//         $modal = e.fb.instance.modal.element;	//the modal object
//
//     console.log( 'inside' );
//
//   } );
//
// } );


// if (FooBox && $.isArray(FooBox.instances)){
// 	var max_width = 1260, max_height = 1280;
// 	$.each(FooBox.instances, function(i, fbx){
// 		if (fbx.objects && $.isArray(fbx.objects.handlers)){
// 			$.each(fbx.objects.handlers, function(j, handler){
// 				if (handler.type == 'image'){
// 					handler.getSize = function(item){
// 						if (item.width != null && item.height != null) {
// 							if (item.width > max_width || item.height > max_height){
// 								var width_ratio = max_width / item.width;
// 								var height_ratio = max_height / item.height;
// 								var ratio = width_ratio > height_ratio ? height_ratio : width_ratio;
// 								item.width *= ratio;
// 								item.height *= ratio;
// 							}
// 							return new FooBox.Size(item.width, item.height);
// 						}
// 						return new FooBox.Size(0, 0);
// 					};
// 					return false;
// 				}
// 			});
// 		}
// 	});
// }
