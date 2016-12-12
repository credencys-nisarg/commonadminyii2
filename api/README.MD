With the above minimal amount of effort, you have already finished your task of creating the RESTful APIs for accessing the user data. The APIs you have created include:

GET /users: list all users page by page;
HEAD /users: show the overview information of user listing;
POST /users: create a new user;
GET /users/123: return the details of the user 123;
HEAD /users/123: show the overview information of user 123;
PATCH /users/123 and PUT /users/123: update the user 123;
DELETE /users/123: delete the user 123;
OPTIONS /users: show the supported verbs regarding endpoint /users;
OPTIONS /users/123: show the supported verbs regarding endpoint /users/123.

Extra apis :

POST /users/login: login using username and password
POST /users/change-password: change password with username, new_password and old_password
POST /users/forgot-password: send email to user using username


Demo Url :  
http://localhost/commonadminyii2/api/web/v1/users
GET


To making Apis working, please verify below steps:
- Directory "api/runtime" must be with executable and write permissions
- Updated composer to use auth and rest classes