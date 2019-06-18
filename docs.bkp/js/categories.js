$(function() {

    $( "#category" ).autocomplete({
	    minLength: 1,
	    source: function( request, response ) {
	        $.ajax({
	            url: "consult.php",
	            dataType: "json",
	            data: {
	            	action: 'autocomplete_cat',
	                parameter: $('#category').val()
	            },
	            success: function(data) {
	               response(data);
	            }

	        });
	    },
	    focus: function( event, ui ) {
	        $("#category").val( ui.item.name );
	        loadData();
	        return false;
	    },
	    select: function( event, ui ) {
	        $("#category").val( ui.item.name );
	        return false;
	    }
    })
    .autocomplete( "instance" )._renderItem = function( p, item ) {
      return $( "<p>" )
        .append( "<a><b>" + item.name + "</b></a>" )
        .appendTo( p );
    };

});
