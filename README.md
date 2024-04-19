# PHP-Project
CS1IAD Portfolio 3 Report
Name: Samuel Dev
Basic information

Your Website URL:  http://230411191.cs2410-web01pvm.aston.ac.uk/portfolio3Final/login.php

Source code link (if applicable):   https://github.com/Weaponised115/PHP-Project

One registered user account: 
Username	Password
admin	admin123!

Brief Description of Technologies and Structure 
Server Side
PHP was the main method for the server-side script. I intertwined vanilla PHP and object-oriented PHP methods. All server requests, SQL interactions and content were implemented with vanilla PHP, while the data manipulation and organizing was all done with OOP PHP.

Client Side
I used basic HYML, CSS and a little bit of JavaScript in order to structure and design the website. I made a simplistic looking website that mainly focuses on the backend features. The structure of the website was done using html, the styling using CSS, and JavaScript for validation.

System Structure
I did struggle to follow the MVC architecture during this project, however I do feel that mu program had an extremely similar method. My PHP was the ‘controller’, as it handled all requests and processed interactions and submissions with the database. My HTML was used with PHP to display content, acting as the ‘view’, and finally MySQL database acted as the ‘model’ using users and projects, to interact with the site.

Implementation of the required functions: 
P1. View the basic information of all projects 	projects.php
-This page will display the basic details of all projects in the database

P2. Click one project in the list to view the project details 	projectDetails.php	
-This will allow for a more in-depth view of specific projects, with extra details like user email and ID 

P3. Search all projects using title and starting date 	Search.php		
-User’s can search for projects, wildcards have been used so the exact title doesn’t need to be typed, as well as lowercase manipulation

P4. Register to become a registered user	login.php		
-Users can register and be added to the database with a hashed password

R1. Log in the system 	Login.php		
-If registered, users can log in with their correct user and password details

R2. Add your project	create.php		
-Registered users can add projects to the database

R3. Update your project	projectDetails.php		
-Registered users can edit projects

R4. Logout the system logout.php		
-Registered users can logout
 
Implemented security features
Features 

Session Management	-login.php and logout.php-	
Session management is used in almost all files to provide privileges, to track session status, sessions are created and ended through the two stated files.

Authentication -login.php-	
A users credentials are validated with entries in the ‘users’ database, if a user enters the correct details, access is granted, if incorrect then the user is alerted

Form Validation	-login.php create.php search.php-	
Used for input fields to only allow valid data

SQL injection prevention	-dbConnect.php login.php create.php search.php-	
There are multiple methods used, I have used prepare and bind to prevent SQL injections, this separates the user input from SQL codes
Password Hashing 	-login.php create.php-	
All passwords are hashed before being stored in the database
