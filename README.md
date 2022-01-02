# Board-Game-Library-v3
Author: Robert Gorish
Last Updated: 1/1/22

The purpose of this project is to catalogue my boardgame collection as well as provide a means of searching through that collection for various games based on criteria such as number of players and amount of gametime.

The first two versions of this library were a Google sheet. This one is powered by a mySQL database. To connect one, create a .env file based on the .env_example file provided.

There are two hosting folders, public and publicReadOnly:

 The publicReadOnly folder only displays the table listing the games, the ability to view details on individual games, and the ability to filter the table. Filtering options are by number of players, maximum time or approximate time in minutes, direct name search for the game, which library a game belongs to, and redundant expansions. (Redundant expansions are those in which the expansion is added to the game and the game will never be played without the expansion.)

 The public folder is the full CRUD. In addition to all the features included in the publicReadOnly version, the full version includes the ability to add, edit, and delete games from the database.

 Adding games is done in two ways, either manually or through an API call to BoardGameGeek (BGG). When you add from BGG, you can search for the game and all matching titles will be displayed. Select the one that matches and the game title, number of players, time in minutes, image of the game, and the description will be directly pulled.


Recommendations for setup:

The database accepts links to images. These images can be hosted elsewhere or uploaded and hosted directly. Because there are two different versions of the application, the public and publicReadOnly folders each have an images folder. It is recommended that you delete the images folder in the publicReadOnly folder and replace it with a symbolic link the the images folder in the public folder.

Depending on the changes you may wish to make, you may wish to do the same for app.css. Replace the app.css file in publicReadOnly with a symbolic link to the app.css file in public.


