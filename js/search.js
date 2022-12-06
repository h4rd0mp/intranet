$(document).ready(function(){
    // Defining the local dataset
    $.ajax({
        url: 'listas.php',
    
        type: 'POST',
        dataType: "json",
        data: JSON.stringify(),
        contentType: 'application/json; charset=utf-8',
    
        success: function(response) {
            var listas = response;

//carSuggestions(cars);

console.log(listas);

// Constructing the suggestion engine
var listas_suggestions = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('Name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: listas
});

$('.form-control').typeahead(
{
    hint: true,
    highlight: true, /* Enable substring highlighting */
    minLength: 1 /* Specify minimum characters required for showing result */
},

{
    name: 'listas',
    source: listas_suggestions,
    display: function(item) {        // display: 'name' will also work
        return item.Name;
    },
limit: 5,
templates: {
    suggestion: function(item) {
        return '<div>'+ item.Name +'</div>';
    }
}});








        },
    
        error: function(error) {
            alert('error');
        }
    });


    /*
    console.log(cars);

        // Constructing the suggestion engine
        var cars_suggestions = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('Name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: cars
        });
    
        //cars_suggestions.initialize();
    
        // Initializing the typeahead
        $('.typeahead').typeahead({
            hint: true,
            highlight: true, /* Enable substring highlighting */
//            minLength: 1 /* Specify minimum characters required for showing result */
//        },
/*        {
            name: 'cars',
            source: cars_suggestions,
            display: function(item) {        // display: 'name' will also work
                return item.Name;
            },
            limit: 5,
            templates: {
                suggestion: function(item) {
                return '<div>'+ item.Name +'</div>';
            }
        }
    });
*/
    $('.form-control').on('typeahead:selected', function(event, selected_object, dataset) {
        window.location.href = selected_object.WebViewLink
     });
});  
