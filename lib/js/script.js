
function sessionValid()
{

    $.ajax({
        url: '..tests/testValidateSession.php',
        dataType: 'json',
        success: function(data){

            return data.session_validate;

        }
    })
    .done(function() {
        console.log("success");
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });

}