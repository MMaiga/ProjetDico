
     $("#myTextField").on('keyup', function() { // everytime keyup event
             var input = $(this).val(); // We take the input value
             if ( input.length >= 2 ) { // Minimum characters = 2 (you can change)
                     $('#match').html('<img src="' + window.loader + '" />'); // Loader icon apprears in the <div id="match"></div>
                     var data = {input: input}; // We pass input argument in Ajax
                     $.ajax({
                             type: "POST",
                             url: ROOT_URL + "ajax/autocomplete/update/data", // call the php file ajax/tuto-autocomplete.php (check the routine we defined)
                             data: data, // Send dataFields var
                             dataType: 'json', // json method
                            // timeout: 8000,
                             success: function(response){ // If success
                                     $('#match').html(response.wordList); // Return data (UL list) and insert it in the <div id="match"></div>
                                     $('#matchList li').on('click', function() { // When click on an element in the list
                                             $('#myTextField').val($(this).text()); // Update the field with the new element
                                             //$('#sub').click();
                                             search();
                                              $('#match').text('');// Clear the <div id="match"></div>
                                     });
                             },
                             error: function(data, errorThrown) { // if error
                                     $('#match').text(errorThrown);
                             }
                     });
             } else {
                     $('#match').text(''); // If less than 2 characters, clear the <div id="match"></div>
             }
     });

 function search() {
    $('#match').text('');
    var query=$("#myTextField").val();
    
    var data = {q: query}; // We pass q argument in Ajax
            $.ajax({
                    type: "GET",
                    url: ROOT_URL + "search?q="+data, // call the php file ajax/tuto-autocomplete.php (check the routine we defined)
                   // data: data, // Send dataFields var
                  //  timeout: 8000,
                    success: function(response){ // If success
                            console.log('resultats='+response);
                            $('#results').html(response); // Return data (UL list) and insert it in the <div id="match"></div>
                            $('html, body').animate({
                                    scrollTop: $("#results").offset().top
                                }, 0);
                    },
                    error: function(data, errorThrown) { // if error
                            console.log(errorThrown);
                            $('#results').text(errorThrown);
                             $('html, body').animate({
                                    scrollTop: $("#results").offset().top
                                }, 0);
                    }
            });

 }


 function Relation() {

            $.ajax({
                    type: "GET",
                    url: ROOT_URL + "relations",
                    success: function(response){ // If success
                            console.log(response);
                            $('#results').html(response); // Return data (UL list) and insert it in the <div id="match"></div>
                            $('html, body').animate({
                                    scrollTop: $("#results").offset().top
                                }, 0);
                    },
                    error: function(data, errorThrown) { // if error
                            console.log(errorThrown);
                            $('#results').text(errorThrown);
                            $('html, body').animate({
                                    scrollTop: $("#results").offset().top
                                }, 0);
                    }
            });

 }
 function home() {

            $.ajax({
                    type: "GET",
                    url: ROOT_URL+ "accueil",
                    success: function(response){ // If success
                            console.log(response);
                            //return response;
                            $('#results').html(response); // Return data (UL list) and insert it in the <div id="match"></div>
                            $('html, body').animate({
                                    scrollTop: $("#results").offset().top
                                }, 0);
                    },
                    error: function(data, errorThrown) { // if error
                            console.log(errorThrown);
                            $('#results').text(errorThrown);
                            $('html, body').animate({
                                    scrollTop: $("#results").offset().top
                                }, 0);
                    }
            });

 }

 function Type() {

            $.ajax({
                    type: "POST",
                    url: ROOT_URL + "types",
                    success: function(response){ // If success
                            console.log(response);
                            $('#results').html(response); // Return data (UL list) and insert it in the <div id="match"></div>

                    },
                    error: function(data, errorThrown) { // if error
                            console.log(errorThrown);
                            $('#results').text(errorThrown);
                    }
            });

 }

 function replace(val){
       document.getElementById('myTextField').value=val.id;
       //document.getElementById("sub").click();
       search();
   }

$("input[type='checkbox']").on('click', function(){
  var checked = $(this).attr('checked');
          if (checked) {
            console.log('checked');
           alert('checked');
         } else {

           alert('unchecked');
       }
   });


$('.nav a').click(function () { $(".navbar-to-collapse").collapse("hide") });