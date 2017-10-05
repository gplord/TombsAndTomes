<?php
$pagetitle = "My Hero Character";
include "session.php";
include "header.php";
include "functions.php";
$conn = new mysqli("localhost", "root", "root", "dungeon");
?>

<script>

// Globals
var session_update;         // Global session update id, for polling
var mycharacter;            // Global character object, for comparison with poll results
var activevillain;          // Global villain object, for comparison with poll results
var pollTimeout = 3000;     // Time in ms for poll to refire

// On Page load
$(document).ready(function(){

    UpdateSheet();                                          // Initial page load
    setInterval(function() { UpdatePoll() }, pollTimeout);  // Begin regular polling at interval set above

});

// Simple polling function, checks server for update value, returns 1 (Up to date) or 0 (Not up to date)
function UpdatePoll() {
    $.post( "poll-update.php", { 'session_update': session_update })   // Poll with value derived from first UpdateSheet() call
    .done(function( data ) {
        // console.log(data);   // Debug
        if (data == 0) {
            console.log("Poll returned false: Update required.");
            UpdateSheet();
        } else if (data == 1) {
            console.log("Poll returned true: Up to date.");
        }
    });
}

// Master function for querying character/session/villain info from db and updating the sheet
function UpdateSheet() {
    // Ajax call to our query function for the player's character
    // Includes necessary Session and Villain info, for game state management
    $.post( "query-playercharacter.php", { session_id: '<?php echo $_SESSION['session_id']; ?>', player_id:'<?php echo $_SESSION['player_id']; ?>' })
    .done(function( data ) {

        // Server will return Json-formatted hero/session/villain info -- parse this to an object
        var content = JSON.parse(data);
        //  console.log(content);   // Debug

        $("#mycharacter-abilities").empty();
        $("#mycharacter-effects").empty();
        $("#combat-turnorder").empty();
        $("#villain-abilities").empty();
        $("#villain-effects").empty();

        // For each hero returned (there will be only one matching the query criteria)
        $.each(content, function(i, hero) { 

            // Save this hero query record back to a global variable, for future polls
            mycharacter = hero;
            session_update = hero.session_update;
            $("#mycharacter-name").text(hero.hero_name);
            $("#mycharacter-image").attr("src", "/images/"+hero.hero_image);
            $("#mycharacter-desc").text(hero.hero_desc);
            $("#mycharacter-level").text(hero.hinst_level);
            $("#mycharacter-xp").text(hero.hinst_xp + " / " + hero.hinst_xpnext);
            $("#mycharacter-health").text(hero.hinst_hp + " / " + hero.hinst_hp_max);
            $("#mycharacter-energy").text(hero.hinst_energy + " / " + hero.hinst_energy_max);
            $("#mycharacter-str").text(hero.hinst_str);
            $("#mycharacter-dex").text(hero.hinst_dex);
            $("#mycharacter-int").text(hero.hinst_int);
            $("#mycharacter-cng").text(hero.hinst_cng);

            // For each ability, clone and append an ability block with the ability info
            $.each (hero.abilities, function(i, ability) {
                var newblock = $("#ability-block").clone().appendTo("#mycharacter-abilities");
                newblock.find(".ability-name").text(ability.ability_name);
                newblock.find(".ability-desc").text(ability.ability_desc);
                newblock.collapse().show();
            });
            // For each effect, clone and append an effect block with the effect info
            $.each (hero.effects, function(i, effect) {
                var newblock = $("#effect-block").clone().appendTo("#mycharacter-effects");
                newblock.find(".effect-name").text(effect.effect_name);
                newblock.find(".effect-desc").text(effect.effect_desc);
                newblock.find(".effect-duration").text(effect.effect_durationleft);
                // newblock.collapse().show();
            });

            // If it is this player's turn, show the turn reminder and enable the ability use buttons
            // (Server will enforce using abilities only on a player's turn)
            if (hero.player_current == 1) {
                $("#alert-currentturn").collapse().show();
                $(".currentturn").show();
            }

        });

    });

    // Query for the current turn order of all players
    // This will return the other players' characters, names, the turn order, and the current turn
    $.post( "query-turnorder.php", {})
    .done(function( data ) {
        var content = JSON.parse(data);

        // For each player returned
        $.each(content, function(i, hero) {

            // Create a new list item for their place in the turn order list
            var orderBlock = "<li class='list-group-item'></li>";
            var newBlock = $(orderBlock).appendTo("#combat-turnorder");         // Append to list       
            newBlock.text(hero.hero_name + " (" + hero.player_firstname + ")");  // Write character's/player's name

            // Highlight the currently active player's turn
            if (hero.player_current == 1) {
                newBlock.addClass("active");
            }
        });
    });

    // Query for the currently active villain, if there is one
    // (There should always be one in this example build)
    // TODO: Branch this page into an adaptive character sheet that shows either Combat or Exploration contexts
    $.post( "query-activevillain.php", {})
    .done(function( data ) {
        var content = JSON.parse(data);
        // console.log(content);   // Debug

        // For every villain returned (should be only one)
        $.each(content, function(i, villain) { 

            // Save this hero query record back to a global variable, for future polls
            activevillain = villain;

            // Write stats to the stats table
            $("#villain-name").text(villain.villain_name);
            $("#villain-image").attr("src", "/images/"+villain.villain_image);
            $("#villain-desc").text(villain.villain_desc);
            $("#villain-level").text(villain.vinst_level);
            $("#villain-health").text(villain.vinst_hp + " / " + villain.vinst_hp_max);
            $("#villain-energy").text(villain.vinst_energy + " / " + villain.vinst_energy_max);
            $("#villain-str").text(villain.vinst_str);
            $("#villain-dex").text(villain.vinst_dex);
            $("#villain-int").text(villain.vinst_int);
            $("#villain-cng").text(villain.vinst_cng);

            // For each ability, clone and append an ability block with the ability info
            $.each (villain.abilities, function(i, ability) {
                var newblock = $("#ability-block-villain").clone().appendTo("#villain-abilities");
                newblock.find(".ability-name").text(ability.ability_name);
                newblock.find(".ability-desc").text(ability.ability_desc);
                newblock.collapse().show();
            });
            // For each effect, clone and append an effect block with the effect info
            $.each (villain.effects, function(i, effect) {
                var newblock = $("#effect-block").clone().appendTo("#villain-effects");
                newblock.find(".effect-name").text(effect.effect_name);
                newblock.find(".effect-desc").text(effect.effect_desc);
                newblock.find(".effect-duration").text(effect.effect_durationleft);
            });

            //console.log(villain.villain_name);  // Debug
            var orderBlock = "<li class='list-group-item text-danger'></li>";   // Basic HTML for turn order list item
            var newBlock = $(orderBlock).appendTo("#combat-turnorder");         // Append to the turn order list      
            newBlock.text(villain.villain_name + " (Villain)");                 // Write their name

        });

    });

    console.log("Update completed.");

}

</script>

<div class="container mt-3">

    <div id="alert-currentturn" class="row collapse">
        <div class="col-12">
            <div class="alert alert-info bg-info text-white" role="alert"> 
                <strong>It's your turn!</strong> Choose an Item or Ability below to use this turn.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Combat Turn Order</div>
                <div class="card-body">
                    <ul id="combat-turnorder" class="list-group">
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">Combat Log [Test] <span style="float: right">(5 Turns)</span></div>
                <div class="card-body">
                    <ul id="combatlog" class="list-group" style="height: 197px; overflow: scroll">
                        <li class="list-group-item">Beowulf lunges with Warrior Strength, inflicting 4 points of damage on Count Dracula.</li>
                        <li class="list-group-item">Abraham Van Helsing uses Sacred Weapons to inflict 3 points of damage on Count Dracula.</li>
                        <li class="list-group-item">Hermione Granger casts Incendio, igniting Count Dracula for 3 points of Fire Damage.</li>
                        <li class="list-group-item">Count Dracula sneers, shouting "I vant to suck your blood!" <em>(He appears moderately injured.)</em></li>
                        <li class="list-group-item active">Count Dracula uses Vampiric Drain to steal 2 points of Beowulf's health, restoring 2 points to his own.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <h3>My Character</h3>
    <div class="row">
        <div class="col-md-4 col-lg-4">

            <div class="card mb-3">
                <img id="mycharacter-image" class="card-img-top" src="/images/hero-placeholder.jpg" alt="Card image cap">
            
                <table id="mycharacter-stats" class='table table-hover table-striped'>
                    <tr>
                        <th>Level</th>
                        <td class="text-center" id="mycharacter-level"></td>
                    </tr>
                    <tr>
                        <th>Experience</th>
                        <td class="text-center" id="mycharacter-xp"></td>
                    </tr>
                    <tr>
                        <th>Health</th>
                        <td class="text-center" id="mycharacter-health"></td>
                    </tr>
                    <tr>
                        <th>Energy</th>
                        <td class="text-center" id="mycharacter-energy"></td>
                    </tr>
                    <tr>
                        <th>Strength</th>
                        <td class="text-center" id="mycharacter-str"></td>
                    </tr>
                    <tr>
                        <th>Dexterity</th>
                        <td class="text-center" id="mycharacter-dex"></td>
                    </tr>
                    <tr>
                        <th>Intellect</th>
                        <td class="text-center" id="mycharacter-int"></td>
                    </tr>
                    <tr>
                        <th>Cunning</th>
                        <td class="text-center" id="mycharacter-cng"></td>
                    </tr>
                </table>

            </div>


            <div class="card mb-3">
                <div class="card-header">
                    Active Effects
                </div>
                    <ul id="mycharacter-effects" class="list-group list-group-flush">
                    </ul>
            </div>

        </div>
        <div class="col-md-8 col-lg-8">

            <div class="card mb-3">
                <div class="">
                    <a class="float-right m-3" data-toggle="collapse" href="#collapseBio" aria-expanded="false" aria-controls="collapseBio">
                        View Description
                    </a>
                    <h3 id="mycharacter-name" class="card-header">[ My Character Name ]</h3>
                </div>
                <div class="card-body collapse" id="collapseBio">
                    <p id="mycharacter-desc" class="card-text">[ My Character Description ]</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Abilities</strong>
                </div>
                        
                <ul id="mycharacter-abilities" class="list-group list-group-flush">
                </ul>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card text-white bg-dark mb-3">
                <div class="card-header bg-danger">Current Villain</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                        
                            <img id="villain-image" class="card-img-top" src="/images/villain-placeholder.jpg" alt="Card image cap">

                            <table id="villain-stats" class='table table-hover table-inverse table-striped'>
                                <tr>
                                    <th>Level</th>
                                    <td id="villain-level" class="text-center"></td>
                                </tr>
                                <tr>
                                    <th>Health</th>
                                    <td id="villain-health" class="text-center"></td>
                                </tr>
                                <tr>
                                    <th>Energy</th>
                                    <td id="villain-energy" class="text-center"></td>
                                </tr>
                                <tr>
                                    <th>Strength</th>
                                    <td id="villain-str" class="text-center"></td>
                                </tr>
                                <tr>
                                    <th>Dexterity</th>
                                    <td id="villain-dex" class="text-center"></td>
                                </tr>
                                <tr>
                                    <th>Intellect</th>
                                    <td id="villain-int" class="text-center"></td>
                                </tr>
                                <tr>
                                    <th>Cunning</th>
                                    <td id="villain-cng" class="text-center"></td>
                                </tr>
                            </table>

                        </div>
                        <div class="col-md-8">
                            <h4 id="villain-name" class="card-title">[ Villain Name ]</h4>
                            <p id="villain-desc" class="card-text">[ Villain Description ]</p>
                            <ul id="villain-abilities" class="list-group list-group-flush">
                            </ul>

                            <div class="card bg-dark my-3">
                                <div class="card-header">
                                    Villain's Active Effects
                                </div>
                                    <ul id="villain-effects" class="list-group list-group-flush">
                                    </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- This block holds and hides the templates jQuery can use to constitute the dynamic blocks the sheet will load -->
<div class="invisible">

    <!-- Template block for jQuery to clone in creating new abilities from the database -->
    <li id="ability-block" class="list-group-item collapse">
        <div class="media">
            <img class="ability-image d-flex mr-3" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 72px">
            <div class="media-body">
                <h5 class="ability-name">[ Ability Name ]</h5>
                <span class="ability-desc">[ Ability Description ]</span>
            </div>
        </div>
        <div class="currentturn collapse text-center">
            <button class="currentturn btn btn-primary mt-2">Use this Ability</button>
        </div>
    </li>

    <!-- Template block for jQuery to clone in creating new Villain abilities (same as above, without interaction buttons)-->
    <li class="list-group-item collapse" id="ability-block-villain">
        <div class="media">
            <img class="d-flex mr-3 ability-image" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 72px">
            <div class="media-body">
                <h5 class="ability-name">[ Ability Name ]</h5>
                <span class="ability-desc">[ Ability Description ]</span>
            </div>
        </div>
    </li>

    <!-- Template block for jQuery to clone in creating new effects from the database -->
    <li class="list-group-item" id="effect-block">
        <div class="media">
            <img class="d-flex mr-3 effect-image" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 36px">
            <div class="media-body">
                <h6 class="mt-0 mb-1 effect-name"><strong>[ Effect Name ]</strong></h6>
                <strong>(<span class="effect-duration">#</span> Turn(s) Remaining)</strong><br>
                <span class="effect-desc">[ Effect Description ]</span>
            </div>
        </div>
    </li>

</div>

<script>
$('#combatlog').scrollTop($('#combatlog')[0].scrollHeight); // Scroll to most recent (bottom) entry in combat log
</script>

<?php
include("footer.php");
?>