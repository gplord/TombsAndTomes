<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#heroquery").click(function(){
        $.post( "poll-heroes.php", { session_id: 's12345678' })
        .done(function( data ) {
            //console.log( "Data Loaded: " + data );
            var content = JSON.parse(data);
            // console.log(content);

            $.each(content, function(i, hero) { 
                console.log(hero);
                $("#heroes").append("<p>" + hero.hero_name + "</p>");
            });

        });
    });

    // $("#villainquery").click(function(){
    //     $.post( "poll-active-villains.php", { session_id: 's12345678' })
    //     .done(function( data ) {
    //         //console.log( "Data Loaded: " + data );
    //         var content = JSON.parse(data);
    //         // console.log(content);

    //         $.each(content, function(i, villain) { 
    //             console.log(villain);
    //             $("#villains").append("<p>" + villain.villain_name + "</p>");
    //         });

    //     });
    // });

});
</script>
</head>
<body>

<button id="heroquery">Get Heroes</button>
<button id="villainquery">Get Villain</button>

<div></div>
<div id="heroes"></div>
<div id="villains"></div>

</body>
</html>