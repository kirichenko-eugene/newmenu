window.addEventListener('load', function(){
    $('#search').on('input', getDishesByFilter);
});

var getDishesByFilter = function(){
    let request_data = {'name': $('#search').val() };

    console.log(request_data);
    
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        dataType: 'html',
        data: request_data, 
        success: function(response_html){
          $("#display").html(response_html);
        },
        error: function(jqXHR, status, msg){
            console.log(jqXHR); console.log(msg+' '+status);
        }
    });
};