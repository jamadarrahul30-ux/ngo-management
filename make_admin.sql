-- IMPORTANT: Replace the email below with the email of your existing active user.
-- Run this in phpMyAdmin after selecting the `ngo` database.
UPDATE users SET role = 'Admin' WHERE email = 'REPLACE_WITH_YOUR_EMAIL' AND status = 'Active';
-- Confirm that exactly the intended account has role Admin:
SELECT user_id, name, email, role, status FROM users WHERE role = 'Admin';
