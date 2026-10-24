NGO MANAGEMENT SYSTEM - REQUESTED UPDATES

1) Make an existing account Admin
- Open http://localhost:8080/phpmyadmin (adjust the port if your XAMPP uses a different one).
- Select database `ngo`, open the SQL tab, and run the contents of make_admin.sql after replacing REPLACE_WITH_YOUR_EMAIL with the active account email.
- Log out and log back in so the session picks up the new role.
- This project already reads the `role` field from the users table at login. This script promotes an existing account; it does not create a user or change passwords.

2) Employees tab
- The Users menu/page is now labelled Employees and shows employee names, emails, roles, and statuses. A link is added to the main navigation.

3) Beneficiary feedback
- Click a beneficiary name to open that beneficiary's feedback page.
- Import beneficiary_feedback.sql into the `ngo` database using phpMyAdmin. The feedback page will then show feedback records for that beneficiary.
- The feedback table stores feedback text, date, and submitter. This update adds viewing and database storage structure; it does not add a feedback-submission form.

Copy the extracted ngo_management folder into C:\xampp\htdocs\ and back up your existing folder first. Keep your existing db.php if it contains your correct database connection settings.
