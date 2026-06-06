-- Import AFTER schema_hosting.sql (select your database first in phpMyAdmin)
-- Default password for all users: password

INSERT INTO users (name, email, password_hash, role, phone, is_active) VALUES
('Super Admin', 'admin@fraudshield.ai', '$2y$12$AKtw4DYUhk7Gkn0EZHPpCeYqDJo9FNLm9qSc9jI/72hwDeq5QyS4.', 'admin', '+1-555-0100', 1),
('John Anderson', 'john@example.com', '$2y$12$AKtw4DYUhk7Gkn0EZHPpCeYqDJo9FNLm9qSc9jI/72hwDeq5QyS4.', 'user', '+1-555-0101', 1),
('Sarah Mitchell', 'sarah@example.com', '$2y$12$AKtw4DYUhk7Gkn0EZHPpCeYqDJo9FNLm9qSc9jI/72hwDeq5QyS4.', 'user', '+1-555-0102', 1),
('Mike Thompson', 'mike@example.com', '$2y$12$AKtw4DYUhk7Gkn0EZHPpCeYqDJo9FNLm9qSc9jI/72hwDeq5QyS4.', 'user', '+1-555-0103', 1),
('Emily Chen', 'emily@example.com', '$2y$12$AKtw4DYUhk7Gkn0EZHPpCeYqDJo9FNLm9qSc9jI/72hwDeq5QyS4.', 'user', '+1-555-0104', 1);

INSERT INTO transactions (user_id, transaction_ref, amount, merchant, location, category, card_last4, transaction_time, status, risk_score, risk_level, fraud_probability) VALUES
(2, 'TXN-20240101-001', 45.99, 'Starbucks Coffee', 'New York, NY', 'food', '4532', '2024-01-15 09:23:00', 'safe', 0.0821, 'low', 0.0821),
(2, 'TXN-20240101-002', 5847.00, 'Unknown Merchant #4821', 'Lagos, Nigeria', 'general', '4532', '2024-01-15 03:14:00', 'fraud', 0.9234, 'high', 0.9234),
(2, 'TXN-20240101-003', 129.99, 'Amazon Prime', 'Seattle, WA', 'shopping', '4532', '2024-01-16 14:05:00', 'safe', 0.0512, 'low', 0.0512),
(2, 'TXN-20240101-004', 2800.00, 'Crypto Exchange XYZ', 'Unknown Location', 'crypto', '4532', '2024-01-17 02:47:00', 'fraud', 0.8791, 'high', 0.8791),
(2, 'TXN-20240101-005', 78.50, 'Shell Gas Station', 'Chicago, IL', 'fuel', '4532', '2024-01-18 11:30:00', 'safe', 0.1023, 'low', 0.1023);

INSERT INTO notifications (user_id, title, message, type, is_read, transaction_id) VALUES
(2, 'Fraud Alert', 'High-risk transaction detected: $5,847', 'fraud_alert', 0, 2);

INSERT INTO system_logs (user_id, action, entity_type, description, severity) VALUES
(1, 'system_startup', 'system', 'FraudShield AI initialized', 'info');
