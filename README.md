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
