# Tombs &amp; Tomes

This project is a digital, web-based companion application meant to assist in running and playing a game of *Tombs &amp; Tomes* with a group of players.  

This application will help guide a basic game sequence, offering players with Tasks, optional hints, and "password" fields where they can enter the information proving they have completed their Task.

The application will also process players' combat sequences, providing an interactive Character Sheet, with their stats, abilities, items, and information about the Villains they face.  The application will synchronize multiple players to a single MySQL database, handing off shared info and active inputs to users in their turn sequence.

The goal of this application is not to replace a proper, in-person *Tombs &amp; Tomes* game session, but to provide a companion tool to simplify the data and number-crunching overhead for a game with real-world exploration and mobility in mind.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

To install this application on a local server, you will need:

1. A **MySQL installation**, with administrative access to create and modify a single database
2. A **PHP installation**
3. (Preferred) A simple MySQL administrative tool, such as **phpMyAdmin**
4. (For basic testing) A local webserver, or webserver tool such as **MAMP** or **WAMP** will provide the easiest test environment.  (Please note that you will likely not be able to share access to this server with other players on different machines.)

**This game is still under development, and is far from finished.**  The game is not in a fully playable state, but example accounts, players, heroes, and villains have been created in a premade database, to assist with basic development and playtesting.

### Installing

1. Create a new MySQL database. (The provided connection scripts use a database called "dungeon," but you can modify this to whatever you prefer.  Please note that you will have to find and replace several connection commands, as these have not yet been centralized to a single config file.)
2. Connect to MySQL and load the new database.
3. Copy and paste the **db.sql** file, in the main project directory.
4. Run the commands in this file in the new database.  If you are using phpMyAdmin, you can access the "SQL" tab of your empty database, and paste the full contents of the db.sql file into the text box.  Hit "Go" to run the full list of commands.
5. You should see 25 tables, each pre-loaded with placeholder data.
6. On your webserver, copy the full contents of the project directory to an empty directory.  (Note: For the time being, all links presently assume this game is loaded on its own domain, or subdomain.  If you are installing it into a directory beneath another domain/subdomain, you will need to repair these links to make them relative to your path, rather than to the server's webroot.)
7. Find and replace all database connection statements (for now...) with your database info.  (This will be greatly simplified in the future.)

## Testing the Game

You can access the premade heroes and session by logging in with the following test accounts:

1. Username: test@example.com / Password: password
2. Username: test2@example.com / Password: password
3. Username: test3@example.com / Password: password

You can replace these with whatever information you prefer by accessing and modifying the "player" table in the database.

**Important**: While this game is under early development, no efforts have yet been made to secure this application, or its account data.  **Please do not store your actual email address with any password that you reuse elsewhere!  These passwords are being stored in plain text.** (For now.)

Once you are logged in, you will be able to see your character by clicking on the "My Character" link in the header.

A basic game scenario has been prepared, which you can reset at any time by reloading the db.sql file and overwriting your database.  (All game data is stored in the database, and no files will be affected.)

### To test multiple accounts simultaneously

For simple testing of a multiplayer experience, you can log multiple browsers, or private browser sessions, into each of the accounts listed above.  As one player/browser window completes its turn, the others should update automatically.  (The client checks for server updates every 3 seconds.  This is a simple synchronized server solution for users that cannot access or setup synchronous web connections.)

**Please note:** Leaving Character Sheet windows open will result in constant polling of your MySQL server.  These polls have been kept to a minimal amount (every 3 seconds by default) to lighten this load, but you can configure this rate in the **mycharacter.php** file by adjusting the **pollTimeout** variable in the first Javascript section.  (This value is in milliseconds.)

```
var pollTimeout = 3000;     // Time in ms for poll to refire
```

## Authors

* **Megan Kudzia** - [Github](https://github.com/mkudzia) / [Twitter](http://www.twitter.com/mkudzia)
* **Greg Lord** - [Github](https://github.com/gplord) / [Twitter](http://www.twitter.com/gplord)

## Attributions

### Images

Hermione Granger:   
By Reilly Brown - reillybrownart - reillybrown.deviantart - twitter.com/reilly_brown [CC BY-SA 3.0 (https://creativecommons.org/licenses/by-sa/3.0)], via Wikimedia Commons
https://commons.wikimedia.org/wiki/File%3AHarry_Potter_by_Reilly_Brown.jpg

Count Dracula:
By Thecount68 (Own work) [Public domain], via Wikimedia Commons
https://commons.wikimedia.org/wiki/File%3ADracula_vampire.jpg

Beowulf:
By J. R. Skelton [Public domain], via Wikimedia Commons
https://commons.wikimedia.org/wiki/File%3AStories_of_beowulf_fighting_the_dragon.jpg

Sherlock Holmes:
Sidney Paget [Public domain], via Wikimedia Commons
https://commons.wikimedia.org/wiki/File%3ASherlock_Holmes_-_The_Man_with_the_Twisted_Lip.jpg

Aragog:
By Rob Young from United Kingdom (Aragog) [CC BY 2.0 (http://creativecommons.org/licenses/by/2.0)], via Wikimedia Commons
https://commons.wikimedia.org/wiki/File%3AAragog_(7119116887).jpg

Grendel:
By J. R. Skelton [Public domain], via Wikimedia Commons
https://commons.wikimedia.org/wiki/File%3AStories_of_beowulf_grendel.jpg

Professor Moriarty:
By Sidney Paget (1860-1908) (The Strand Magazine) [Public domain], via Wikimedia Commons
https://commons.wikimedia.org/wiki/File%3APd_Moriarty_by_Sidney_Paget.gif

Abraham Van Helsing:
By Screenshot from "Internet Archive" of the movie Dracula (1958) [Public domain], via Wikimedia Commons
https://commons.wikimedia.org/wiki/File%3ADracula_1958_e.jpg

---

### Project Art &amp; Icons

All artwork, logos, and icons are original work, created for this project.

Logo, 3D Images, Icons:     Greg Lord (@gplord)

Illustrations:              Megan Kudzia (@mkudzia)

---

### Code &amp; Theme

This project makes use of jQuery and Bootstrap for code and layout.

jQuery:     https://jquery.com/

Bootstrap:  http://getbootstrap.com/

---

### Text

Descriptive text is largely taken from Wikipedia entries, with minor editing.  

Beowulf:                https://en.wikipedia.org/wiki/Beowulf

Hermione Granger:       https://en.wikipedia.org/wiki/Hermione_Granger

Sherlock Holmes:        https://en.wikipedia.org/wiki/Sherlock_Holmes

Abraham Van Helsing:    https://en.wikipedia.org/wiki/Abraham_Van_Helsing

Count Dracula:          https://en.wikipedia.org/wiki/Count_Dracula