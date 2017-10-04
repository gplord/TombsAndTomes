<?php
session_start();
$title = "Select a Hero";
include("header.php");
?>

<div class="container">

    <div class="row">
        <div class="col-12">
            <div class="alert alert-info bg-info text-white" role="alert"> 
                <strong>Test Page:</strong> This page is intended for design and layout testing, and does not connect with the database.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md mb-3">
            <div class="card" style="width: 20rem;">
                <img class="card-img-top" src="/images/hero-beowulf.jpg" alt="Card image cap">
                <div class="card-body">
                    <h4 class="card-title">Beowulf</h4>
                    <p class="card-text">Hero of the Geats, famed for his strength and courage, and renowned for his battles with Grendel and Grendel's mother in defense of Danish King Hrothgar.</p>
                </div>
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item">Sword and Shield</li>
                    <li class="list-group-item">Warrior Strength</li>
                    <li class="list-group-item">Commanding Presence</li>
                </ul>
                <div class="card-body">
                    <button class="btn btn-primary">Select this Hero</button>
                </div>
            </div>
        </div>
        <div class="col-md mb-3">
            <div class="card" style="width: 20rem;">
                <img class="card-img-top" src="/images/hero-hermionegranger.jpg" alt="Card image cap">
                <div class="card-body">
                    <h4 class="card-title">Hermione Granger</h4>
                    <p class="card-text">Young student of magic at Hogwarts School of Witchcraft and Wizardry, who often uses her quick wit, deft recall, and encyclopaedic knowledge to help her friends.</p>
                </div>
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item">Incendio (Fire Spell)</li>
                    <li class="list-group-item">Vulnera Sanentur (Healing Spell)</li>
                    <li class="list-group-item">Potion Brewing</li>
                </ul>
                <div class="card-body">
                    <button class="btn btn-primary">Select this Hero</button>
                </div>
            </div>
        </div>
        <div class="col-md mb-3">
            <div class="card" style="width: 20rem;">
                <img class="card-img-top" src="/images/hero-sherlockholmes.jpg" alt="Card image cap">
                <div class="card-body">
                    <h4 class="card-title">Sherlock Holmes</h4>
                    <p class="card-text">A "consulting detective" known for his proficiency with observation, forensic science, and logical reasoning, which he employs when investigating cases.</p>
                </div>
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item">Brawling/Pistol</li>
                    <li class="list-group-item">Deduction</li>
                    <li class="list-group-item">Interrogation</li>
                </ul>
                <div class="card-body">
                    <button class="btn btn-primary">Select this Hero</button>
                </div>
            </div>
        </div>
        <div class="col-md mb-3">
            <div class="card" style="width: 20rem;">
                <img class="card-img-top" src="/images/hero-abrahamvanhelsing.jpg" alt="Card image cap">
                <div class="card-body">
                    <h4 class="card-title">Abraham Van Helsing</h4>
                    <p class="card-text">Van Helsing is an aged Dutch doctor with a wide range of interests and accomplishments. A vampire hunter and the archenemy of Count Dracula.</p>
                </div>
                    <ul class="list-group list-group-flush">
                    <li class="list-group-item">Revolver/Knife</li>
                    <li class="list-group-item">Sanctified Weapons</li>
                    <li class="list-group-item">Lockpicking</li>
                </ul>
                <div class="card-body">
                    <button class="btn btn-primary">Select this Hero</button>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
include("footer.php");
?>