<?php

// Selects a random Villain, with specified level range
// Will only return monsters that were not already selected for your Session
$session_id = "s12345678";
$maxlevel = 5;
$random_monster_query = sprintf("
SELECT villain.*
FROM `villain`
WHERE villain.villain_id NOT IN 
(
SELECT villain.villain_id
FROM villain

LEFT OUTER JOIN villain_instance
ON villain.villain_id = villain_instance.villain_id

LEFT JOIN session_villain
ON villain_instance.vinst_id = session_villain.vinst_id

LEFT OUTER JOIN session
ON session_villain.session_id = session.session_id

WHERE session.session_id = '%s'
)

AND (villain.villain_level < %f)

ORDER BY RAND() LIMIT 1;

", $session_id, $maxlevel);

echo $random_monster_query;


$player_id = "p11111111";   // Test user Greg in the db
$player_info_query = sprintf("

SELECT hero_instance.*, hero.hero_name, player.player_name, player.player_id
FROM hero_instance

LEFT OUTER JOIN player_hero_instance
ON hero_instance.hinst_id = player_hero_instance.hinst_id

LEFT JOIN player
ON player_hero_instance.player_id = player.player_id

LEFT JOIN hero
ON hero_instance.hinst_hero_id = hero.hero_id

WHERE player.player_id = '%s';

", $player_id);

echo "<p>" . $player_info_query . "</p>\n";


// --------------------------------------------------------------- //

$player_info_query = sprintf("

SELECT 
    hero_instance.*, 
    hero.hero_name, hero.hero_id,
    player.player_name, player.player_id
FROM hero_instance

LEFT OUTER JOIN player_hero_instance
ON hero_instance.hinst_id = player_hero_instance.hinst_id

LEFT JOIN player
ON player_hero_instance.player_id = player.player_id

LEFT JOIN hero
ON hero_instance.hero_id = hero.hero_id

WHERE player.player_id = '%s';

", $player_id);
$player_info_result = $conn->query($player_info_query);

//echo "<p>" . $player_info_query . "</p>\n";

if ($player_info_result->num_rows > 0) {

    echo "<h2>My Hero Character:</h2>\n";

    $player_row = $player_info_result->fetch_assoc();
    print_r($player_row);

    echo "<h2>My abilities:</h2>\n";

    $player_ability_query = sprintf("
    
        SELECT ability.*
        FROM ability
                    
        LEFT OUTER JOIN hero_ability
        ON hero_ability.hero_id = '%s'

        WHERE hero_ability.hero_id = '%s'
        GROUP BY ability.ability_id
        ;

    ", $player_row["hero_id"], $player_row["hero_id"]);

    //echo "<p>" . $player_ability_query . "</p>\n";

    $player_ability_result = $conn->query($player_ability_query);

    if ($player_ability_result->num_rows > 0) {

        while($player_ability_row = $player_ability_result->fetch_assoc()) {

            echo "<pre>\n";
            print_r($player_ability_row);
            echo "</pre>\n";

        }

    }
}

// Get the next incomplete task from a particular session ID
$next_task_query = "SELECT * FROM session_task WHERE session_task.session_id = 's12345678' and session_task.task_complete = '0' LIMIT 1";


?>