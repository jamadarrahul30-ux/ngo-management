-- Run this in phpMyAdmin with the `ngo` database selected.
CREATE TABLE IF NOT EXISTS beneficiary_feedback (
  feedback_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  beneficiary_id INT NOT NULL,
  feedback TEXT NOT NULL,
  feedback_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  submitted_by VARCHAR(150) DEFAULT NULL,
  INDEX (beneficiary_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
