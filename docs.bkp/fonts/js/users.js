$(function() {

    // Atribui evento e função para limpeza dos campos
    $('#search').on('input', cleanFields);

    // Dispara o Autocomplete a partir do primeiro caracter
    $( "#search" ).autocomplete({
	    minLength: 1,
	    source: function( request, response ) {
	        $.ajax({
	            url: "consult.php",
	            dataType: "json",
	            data: {
	            	action: 'autocomplete',
	                parameter: $('#search').val()
	            },
	            success: function(data) {
	               response(data);
	            }

	        });
	    },
	    focus: function( event, ui ) {
	        $("#search").val( ui.item.email );
	        loadData();
	        return false;
	    },
	    select: function( event, ui ) {
	        $("#search").val( ui.item.email );
	        return false;
	    }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<p>" )
        .append( "<a><b>" + item.email + "</b></a>" )
        .appendTo( ul );
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function loadData(){
    	var search = $('#search').val();

    	if(search != "" && search.length >= 1){
    		$.ajax({
	            url: "consult.php",
	            dataType: "json",
	            data: {
	            	action: 'consult',
	                parameter: $('#search').val()
	            },
	            success: function( data ) {
	               $('#buckets').val(data[0].bucket);
	               $('#groups').val(data[0].groups);
	            }
	        });
    	}
    }

    // Função para limpar os campos caso a search esteja vazia
    function cleanFields(){
       var search = $('#search').val();

       if(search == ""){
	  	   $('#buckets').val('');
           $('#groups').val('')
       }
    }
});