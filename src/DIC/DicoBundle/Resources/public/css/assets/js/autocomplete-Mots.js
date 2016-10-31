$(document).ready(function(){
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
                             timeout: 3000,
                             success: function(response){ // If success
                                     $('#match').html(response.wordList); // Return data (UL list) and insert it in the <div id="match"></div>
                                     $('#matchList li').on('click', function() { // When click on an element in the list
                                             $('#myTextField').val($(this).text()); // Update the field with the new element
                                             //$('#sub').click();
                                             search();
                                             $('#match').text(''); // Clear the <div id="match"></div>
                                     });
                             },
                             error: function(data, errorThrown) { // if error
                                     $('#match').text('problem !!!');
                             }
                     });
             } else {
                     $('#match').text(''); // If less than 2 characters, clear the <div id="match"></div>
             }
     });
 });
 function search() {

    var query=$("#myTextField").val();

    var data = {q: query}; // We pass q argument in Ajax
            $.ajax({
                    type: "POST",
                    url: ROOT_URL + "search", // call the php file ajax/tuto-autocomplete.php (check the routine we defined)
                    data: data, // Send dataFields var
                    timeout: 5000,
                    success: function(response){ // If success
                            console.log(response);
                            $('#results').html(response); // Return data (UL list) and insert it in the <div id="match"></div>

                    },
                    error: function(data, errorThrown) { // if error
                            console.log('problem !');
                            $('#results').text('problem !');
                    }
            });


  /*  $.get(ROOT_URL + "/search?q=" + encodeURIComponent(query),
            function (data) {
                var t = $("table#results tbody").empty();
                if (!data || data.length == 0) return;
                      console.log(data);
                      data.forEach(function (row) {

                    var Mot = row.Word;
      if(Mot.nf == ""){
        $('#WordDiv').html("<font style='text-transform: uppercase;'><b>"+Mot.name+"</b></font>");
      }
      else{
        $('#WordDiv').html("<font style='text-transform: uppercase;'><b>"+Mot.nf+"</b></font>");
      }
                });

                showMot(data[0].Word.name);
            }, "json");
    return false;*/
 }
