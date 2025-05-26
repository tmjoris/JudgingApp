# JudgingApp
The platform is meant to simulate a Leaderboard.<br>

# Navigating the Application online
The main page is at<br>
http://leaderboardkali.tech:8080/public/scoreboard.html<br><br>
But only judges can assign points to Particpants.
The judge login page is at<br>
http://leaderboardkali.tech:8080/judge/judgelogin.html  <br><br>
Still only the admin can create judges, and the admin page is at<br>
http://leaderboardkali.tech:8080/admin/adminlogin.html<br><br>
The admin username is:
<pre> adminOne</pre> 
and password is:
<pre> for-the-first-admin</pre>


# Set up and Run the application
First clone the application to your local machine.<br>
<pre>git clone https://github.com/tmjoris/JudgingApp</pre>
Change your current directory to that of the application
<pre>cd JudgingApp</pre>
Make sure you have [docker](https://docs.docker.com/engine/install/) and [docker compose](https://docs.docker.com/compose/install/) installed, before running
<pre>docker compose up</pre>
This is will launch the application by first installing all the LAMP dependecies before starting the Apache server.<br>
The compose command would end up creating 2 containers:
<pre>judgingapp-web-1</pre>
<pre>judgingapp-db-1</pre>
To make changes to the database once the app is running, you'd ssh into the judgingapp-db-1 container and run any relevant mysql commands
<pre>docker exec -it judgingapp-db-1 mysql -u "root" -p</pre>
When asked for the password type
<pre>Enter Password:hard-to-crack</pre>

# Database Schema
![image](https://github.com/user-attachments/assets/3582893b-7488-4bf3-a355-ad32e535825d)
<b>1. Admin Table</b>
Stores admin login credentials.<br>
<pre>
CREATE TABLE admin(
    username VARCHAR(50) PRIMARY KEY,
    hashedPassword VARCHAR(255) NOT NULL
);
</pre>
<b>2. Judges Table</b></br>
Holds judge login credentials and display names.
<pre>
  CREATE TABLE judges (
    username VARCHAR(50) PRIMARY KEY,
    display_name VARCHAR(100) NOT NULL,
    hashedPassword VARCHAR(255) NOT NULL
);
</pre>
<b>3. Users Table</b></br>
Contains all participants being scored.
<pre>
  CREATE TABLE users (
    username VARCHAR(100) PRIMARY KEY
);
</pre>
<b>3. Scores Table</b></br>
Stores individual judge scores of each user.
<pre>
  CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL,
    judge_name VARCHAR(100) NOT NULL,
    points INT NOT NULL,
    FOREIGN KEY (user_name) REFERENCES users(username) ON DELETE CASCADE,
    FOREIGN KEY (judge_name) REFERENCES judges(username) ON DELETE CASCADE
);
</pre>
# Assumptions made
1. The platform scale and needs can be satisfied by one admin.
2. The users are pre-registered.
3. The judges are fine having the admin set their username and password
4. The participants are only using the platform to view their points.
5. The expected number of users is miniscule.

# Design Choices
**Database Structure:**

* The platform uses a relational database with four main tables (`admin`, `judges`, `users`, and `scores`)

  * `admin` and `judges` tables store user credentials separately to manage different roles and permissions.
  * `users` table lists all participants being scored.
  * `scores` table connects judges to users with the points they assign.

* Used a foreign key constraints with `ON DELETE CASCADE` to keep data consistent by automatically removing dependent scores when a user or judge is deleted.

**PHP Constructs:**

* By using Server-Sent Events (SSE) in the scoreboard, the server could send real-time score updates without needing page refreshes.
* The system uses JSON encoding to structure data exchanged between server and client, making it easy for me to integrate with JavaScript.
* I implemented the use of prepared statements to prevent SQL injection and increase security.



**Platform Architecture:**

* The platform is designed as a monolithic application. This approach made it simple and faster to develop.

* The system clearly separates concerns via modular PHP files (e.g., `db.php` for database connection, distinct pages for judge and admin login).

**Authentication:**

* Separate login pages for admins and judges were added to:

  * To allow the enforcement of role-based access control, ensuring only authorized judges can submit scores and only admins can manage the system.
  * Secure sensitive actions behind authentication, protects the integrity of scoring and user data.




