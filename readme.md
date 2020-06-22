# Laravel - Getting Started (Pluralsight Course)
This repository holds the starting source code of the "PHP Development with Laravel - Working with Models & Data" course on Pluralsight.

Clone this repository to start with the same code I start with in this course.

# Usage
Simply clone this repo and run `composer install` to install all the required dependencies. Make sure to rename the `.env.example` file to `.env` and also run `php artisan key:generate` to generate an application key for this Laravel app.

Training assignment

Create a Q/A application (e.g. reddit, quora, stackoverflow, etc.) based on RESTful calls with the following steps/features:

Step 1: No auth

Post questions
Add answers
Delete questions if no answer is associated with it 
List the posted questions based on the date of the last answer added (newest answered on top) AND based on the number of answers (descending). List the date of the last answer, as well as the number of answers associated (*)

Step 2: Add auth

way to create users and sign-in/recover password
associate questions and answers to a given user (the one currently signed in will be the author of the question/answer)
can only edit question/answer if that user is the original author of it (I can only edit MY posts)
can only delete question/answer if that user is the original author of it
listed questions from () now also have a username or email attached to it (e.g. last answer by Jack on XX.XX.XXXX) (*)
users not authenticated still get to see a list of answers as per (**)

Pointers:
use API routes instead of web routes (keep in mind it will automatically add the /api prefix to all url's)
use postman to test endpoints
send valid and structured json responses always! If listing a question, return fields like title/content, created/updated by/at and associated answers (nested)
