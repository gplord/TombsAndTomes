<?php
$pagetitle = "My Hero Character";
include "session.php";
include "header.php";
include "functions.php";

$conn = new mysqli("localhost", "root", "root", "dungeon");
?>

<script>
$(document).ready(function(){
    $.post( "query-playercharacter.php", { session_id: '<?php echo $_SESSION['session_id']; ?>', player_id:'<?php echo $_SESSION['player_id']; ?>' })
    .done(function( data ) {
        //console.log( "Data Loaded: " + data );
        var content = JSON.parse(data);
         console.log(content);

        $.each(content, function(i, hero) { 
            console.log(hero.hero_name);
            console.log(hero.hero_image);
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

            $.each (hero.abilities, function(i, ability) {
                var newblock = $("#ability-block").clone().appendTo("#mycharacter-abilities");
                // newblock.children(".ability-name").text("Frank");
                newblock.find(".ability-name").text(ability.ability_name);
                newblock.find(".ability-desc").text(ability.ability_desc);
                newblock.collapse().show();
            });

        });

    });
});
</script>

<script id="effect-block" type="text/html">
<!-- (Working) ==== Template HTML for effects to generate ==== -->
<li class="list-group-item">
    <div class="media">
        <img class="d-flex mr-3 effect-image" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 36px">
        <div class="media-body">
            <h6 class="mt-0 mb-1 effect-name"><strong>[ Effect Name ]</strong></h6>
            <strong class="effect-duration">(# Turn(s) Remaining)</strong>
            <span class="effect-desc">[ Effect Description ]</span>
        </div>
    </div>
</li>
</script>

<div class="container mt-3">

    <div class="row collapse">
        <div class="col-12">
            <div class="alert alert-info bg-info text-white" role="alert"> 
                <strong>It's your turn!</strong> Choose an Item or Ability below to use this turn.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Combat Turn Order [Test]</div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item active">Beowulf (Greg)</li>
                        <li class="list-group-item">Abraham Van Helsing (Megan)</li>
                        <li class="list-group-item">Hermione Granger (Test)</li>
                        <li class="list-group-item text-danger">Count Dracula</li>
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
                <img id="mycharacter-image" class="card-img-top" src="/images/hero-beowulf.jpg" alt="Card image cap">
            
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

            <?php //alert_query($hero_instance_query); ?>

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
                        
                            <img class="card-img-top" src="/images/villain-countdracula.jpg" alt="Card image cap">

                            <table class='table table-hover table-inverse table-striped'>
                                <tr>
                                    <th>Level</th>
                                    <td class="text-center"><?php echo $hero_instance_row["hinst_level"]; ?></td>
                                </tr>
                                <tr>
                                    <th>Experience</th>
                                    <td class="text-center"><?php echo sprintf ("%d / %d", $hero_instance_row["hinst_xp"], $hero_instance_row["hinst_xpnext"]); ?></td>
                                </tr>
                                <tr>
                                    <th>Health</th>
                                    <td class="text-center"><?php echo sprintf ("%d / %d", $hero_instance_row["hinst_hp"], $hero_instance_row["hinst_hp_max"]); ?></td>
                                </tr>
                                <tr>
                                    <th>Energy</th>
                                    <td class="text-center"><?php echo sprintf ("%d / %d", $hero_instance_row["hinst_energy"], $hero_instance_row["hinst_energy_max"]); ?></td>
                                </tr>
                                <tr>
                                    <th>Strength</th>
                                    <td class="text-center"><?php echo $hero_instance_row["hinst_str"]; ?></td>
                                </tr>
                                <tr>
                                    <th>Dexterity</th>
                                    <td class="text-center"><?php echo $hero_instance_row["hinst_dex"]; ?></td>
                                </tr>
                                <tr>
                                    <th>Intellect</th>
                                    <td class="text-center"><?php echo $hero_instance_row["hinst_int"]; ?></td>
                                </tr>
                                <tr>
                                    <th>Cunning</th>
                                    <td class="text-center"><?php echo $hero_instance_row["hinst_cng"]; ?></td>
                                </tr>
                            </table>

                        </div>
                        <div class="col-md-8">
                            <h4 class="card-title">Count Dracula</h4>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nibh massa, iaculis eu consequat et, cursus in quam.</p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="media">
                                        <img class="d-flex mr-3" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 72px">
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1">Warrior Strength</h5>
                                            Beowulf lunges, grappling with his brute strength. This deals additional physical damage, but leaves him vulnerable to additional damage on the villain's next attack.
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="media">
                                        <img class="d-flex mr-3" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 72px">
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1">Sword and Shield</h5>
                                            Beowulf attacks the villain with his sword and shield, dealing physical damage and protecting himself from incoming damage on the villain's next turn.
                                        </div>
                                    </div>
                                    
                                </li>
                                <li class="list-group-item">
                                    <div class="media">
                                        <img class="d-flex mr-3" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 72px">
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1">Commanding Presence</h5>
                                            Beowulf does not attack this turn, but instead commands the battle, inspiring his allies to both deal more damage and receive less damage for the next two turns.
                                        </div>
                                    </div>
                                </li>
                            </ul>


                            <div class="card bg-dark my-3">
                                <div class="card-header">
                                    Active Effects
                                </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <div class="media">
                                                <img class="d-flex mr-3" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 36px">
                                                <div class="media-body">
                                                    <h6 class="mt-0 mb-1"><strong>Vampiric Drain</strong></h6>
                                                    <strong>(1 Turn Remaining)</strong>
                                                    Vampiric magic reduces your strength by 2 points.
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="media">
                                                <img class="d-flex mr-3" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 36px">
                                                <div class="media-body">
                                                    <h6 class="mt-0 mb-1"><strong>Combat Inspiration</strong></h6>
                                                    <strong>(2 Turns Remaining)</strong><br />
                                                    +2 Strength<br> -1 Incoming Damage
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="invisible">

<li class="list-group-item collapse" id="ability-block">
    <div class="media">
        <img class="d-flex mr-3 ability-image" src="/images/icon-placeholder.png" alt="Generic placeholder image" style="width: 72px">
        <div class="media-body">
            <h5 class="ability-name">[ Ability Name ]</h5>
            <span class="ability-desc">[ Ability Description ]</span>
        </div>
        <div class="mr-auto p-2 collapse">
            <button class="btn btn-primary">Use this Ability</button>
        </div>
    </div>
</li>

</div>

<script>
$('#combatlog').scrollTop($('#combatlog')[0].scrollHeight);
</script>

<?php
include("footer.php");
?>