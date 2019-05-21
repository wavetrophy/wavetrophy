# Users

All users of the WAVETROPHY Application are managed via the admin interface.
To access the user page, you have to be [authenticated](../authentification/index.md#login).
After that you have to toggle the "Users" menu in the sidebar and select "Users"
![Navigate to Users][user.navigation]
After the navigation, you arrive at the user dashboard
![User dashboard][user.dashboard]

## Create

To create a user, you have to select add User on the User dashboard
![Add a user][user.add.new]

On the form page, you have to fill in the corresponding values.
The user adds additional emails and phonenumbers through the app.

![User form][user.add.form]

### Email

The email of the user. This is his primary email. This is also the where the invitation email is going to be sent to.

### First Name

The first name of the user.

### Last name

The last name of the user.

### Team

The team of the user. This is a selection of all [Teams](../team/index.md). 
All teams must be [created](../team/index.md#create) before they can be referenced here.
This means, that you have to create one first, before you can add a team here.

## Edit

To edit a user, you have to click on "edit" aside the user you do like to edit.
![Edit user][user.edit]

On the form page, you can adjust all the values as you like.
![Edit user form][user.edit.form]

### First Name

The first name of the user.

### Last name

The last name of the user.

### Team

The team of the user. This is a selection of all [Teams](../team/index.md). 
All teams must be [created](../team/index.md#create) before they can be referenced here.
This means, that you have to create one first, before you can add a team here.

### Emails

A list of all emails that a user is connected with. The user usually creates additional emails (if required) through the app.
Further reading can be found on the [user emails](email.md) docs.

### Phonenumbers

A list of all phonenumber that a user is connected with. The user usually creates additional emails (if required) through the app.
Further reading can be found on the [user emails](phonenumber.md) docs.

## Delete

To delete a user, you either have to click "delete" aside the user you do like to delete or [edit](#edit) the user and then click the delete button.
![Delete User][user.delete]

[user.navigation]: user.navigation.png "Navigate to users"
[user.dashboard]: user.dashboard.png "User dashboard"
[user.add]: user.add.png "Add user"
[user.add.new]: user.add.new.png "User form"
[user.add.form]: user.add.form.png "User form"
[user.add.finished]: user.add.finished.png "Added user"
[user.edit]: user.edit.png "Edit user"
[user.edit.form]: user.edit.form.png "Edit user form"
[user.delete]: user.delete.png "Delete a user"