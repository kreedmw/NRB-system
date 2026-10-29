-- ============================================================
-- Electronic National Registration Bureau (NRB)
-- Core Database Schema
-- File: 01_core.sql
-- MySQL 8+
-- ============================================================
CREATE DATABASE IF NOT EXISTS nrb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE nrb;

SET
    FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- ROLES TABLE
-- ============================================================
DROP TABLE IF EXISTS roles;

CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- ============================================================
-- PERMISSIONS TABLE
-- ============================================================
DROP TABLE IF EXISTS permissions;

CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    module VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- ============================================================
-- ROLE PERMISSIONS
-- ============================================================
DROP TABLE IF EXISTS role_permissions;

CREATE TABLE role_permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_role_permission_role FOREIGN KEY(role_id) REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_role_permission_permission FOREIGN KEY(permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_role_permission (role_id, permission_id)
) ENGINE = InnoDB;

-- ============================================================
-- USERS TABLE
-- ============================================================
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30),
    employee_number VARCHAR(100) UNIQUE,
    profile_photo VARCHAR(255),
    failed_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL,
    last_login TIMESTAMP NULL,
    status ENUM('active', 'inactive', 'locked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_roles FOREIGN KEY(role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE = InnoDB;

CREATE INDEX idx_users_username ON users(username);

CREATE INDEX idx_users_email ON users(email);

-- ============================================================
-- USER SESSIONS
-- ============================================================
DROP TABLE IF EXISTS user_sessions;

CREATE TABLE user_sessions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    session_token VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    device VARCHAR(255),
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_session_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;

-- ============================================================
-- LOGIN HISTORY
-- ============================================================
DROP TABLE IF EXISTS login_history;

CREATE TABLE login_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    ip_address VARCHAR(45),
    device TEXT,
    browser VARCHAR(100),
    operating_system VARCHAR(100),
    login_status ENUM('success', 'failed'),
    failure_reason VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_login_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE
    SET
        NULL
) ENGINE = InnoDB;

-- ============================================================
-- PASSWORD HISTORY
-- ============================================================
DROP TABLE IF EXISTS password_history;

CREATE TABLE password_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_password_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;

-- ============================================================
-- AUDIT LOGS
-- ============================================================
DROP TABLE IF EXISTS audit_logs;

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    module VARCHAR(100),
    action VARCHAR(100),
    description TEXT,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45),
    device_information TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE
    SET
        NULL
) ENGINE = InnoDB;

CREATE INDEX idx_audit_module ON audit_logs(module);

CREATE INDEX idx_audit_date ON audit_logs(created_at);

-- ============================================================
-- NOTIFICATIONS
-- ============================================================
DROP TABLE IF EXISTS notifications;

CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    title VARCHAR(255),
    message TEXT,
    type ENUM('info', 'warning', 'success', 'danger') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notification_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;

-- ============================================================
-- API TOKENS
-- ============================================================
DROP TABLE IF EXISTS api_tokens;

CREATE TABLE api_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(100),
    expires_at TIMESTAMP NULL,
    revoked BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_api_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;

-- ============================================================
-- SYSTEM SETTINGS
-- ============================================================
DROP TABLE IF EXISTS system_settings;

CREATE TABLE system_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- ============================================================
-- DUMMY ROLES
-- ============================================================
INSERT INTO
    roles (name, description)
VALUES
    ('Super Administrator', 'Full system access'),
    (
        'National Administrator',
        'National level management'
    ),
    ('District Administrator', 'District operations'),
    ('Registration Officer', 'Citizen registration'),
    ('Supervisor', 'Approval management'),
    ('Card Printing Officer', 'National ID printing'),
    ('Auditor', 'System auditing'),
    ('Read Only User', 'View only access');

-- ============================================================
-- DUMMY PERMISSIONS
-- ============================================================
INSERT INTO
    permissions (name, module, description)
VALUES
    (
        'manage_users',
        'users',
        'Create and manage users'
    ),
    ('manage_roles', 'roles', 'Manage roles'),
    (
        'register_citizen',
        'citizen',
        'Register citizens'
    ),
    (
        'approve_registration',
        'workflow',
        'Approve applications'
    ),
    ('print_card', 'cards', 'Print national cards'),
    ('view_reports', 'reports', 'View reports'),
    ('manage_settings', 'settings', 'System settings'),
    ('view_audit', 'audit', 'View audit logs'),
    ('api_access', 'api', 'API access');

-- ============================================================
-- SUPER ADMIN PERMISSIONS ARE INHERITED
-- ============================================================
-- The Super Administrator role should not store duplicate
-- permission rows for every other role. Access is derived via the
-- application role inheritance model instead.
-- ============================================================
-- DUMMY USERS
-- Password: password
-- (Use Argon2ID in production)
-- ============================================================
INSERT INTO
    users (
        role_id,
        username,
        email,
        password,
        first_name,
        last_name,
        phone,
        employee_number
    )
VALUES
    (
        1,
        'admin',
        'admin@nrb.gov',
        '$argon2id$v=19$m=65536,t=4,p=2$dummyhash',
        'System',
        'Administrator',
        '+265880000000',
        'NRB001'
    ),
    (
        4,
        'registrar01',
        'registrar@nrb.gov',
        '$argon2id$v=19$m=65536,t=4,p=2$dummyhash',
        'John',
        'Banda',
        '+265881111111',
        'NRB002'
    );

-- ============================================================
-- DISTRICTS
-- ============================================================
DROP TABLE IF EXISTS districts;

CREATE TABLE districts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    code VARCHAR(20) NULL,
    region VARCHAR(100) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- ============================================================
-- CITIZENS
-- ============================================================
DROP TABLE IF EXISTS citizens;

CREATE TABLE citizens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    national_registration_number VARCHAR(50) DEFAULT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100) NULL,
    surname VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    date_of_birth DATE NOT NULL,
    place_of_birth VARCHAR(150) NULL,
    nationality VARCHAR(100) DEFAULT 'Malawian',
    national_origin VARCHAR(100) DEFAULT NULL,
    marital_status ENUM('Single', 'Married', 'Divorced', 'Widowed') DEFAULT 'Single',
    occupation VARCHAR(150) NULL,
    education_level VARCHAR(150) NULL,
    blood_group VARCHAR(10) NULL,
    disability_status VARCHAR(100) NULL,
    religion VARCHAR(100) NULL,
    district_of_birth BIGINT UNSIGNED NULL,
    current_status ENUM(
        'Active',
        'Deceased',
        'Missing',
        'Suspended',
        'Archived'
    ) DEFAULT 'Active',
    registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_citizen_birth_district FOREIGN KEY (district_of_birth) REFERENCES districts(id) ON DELETE
    SET
        NULL,
        CONSTRAINT fk_citizens_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE
    SET
        NULL
) ENGINE = InnoDB;

CREATE INDEX idx_citizens_name ON citizens(surname, first_name);

CREATE UNIQUE INDEX idx_citizens_nrn_unique ON citizens(national_registration_number);

CREATE INDEX idx_citizens_birth_date ON citizens(date_of_birth);

-- ============================================================
-- BIOMETRIC STORAGE
-- ============================================================
DROP TABLE IF EXISTS citizen_photos;

CREATE TABLE citizen_photos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    citizen_id BIGINT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) DEFAULT NULL,
    capture_method ENUM('manual_upload', 'mobile_capture', 'scanner') DEFAULT 'manual_upload',
    quality_status ENUM('clear', 'needs_review', 'missing') DEFAULT 'clear',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_citizen_photo FOREIGN KEY (citizen_id) REFERENCES citizens(id) ON DELETE CASCADE
) ENGINE = InnoDB;

DROP TABLE IF EXISTS citizen_fingerprints;

CREATE TABLE citizen_fingerprints (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    citizen_id BIGINT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) DEFAULT NULL,
    fingerprint_type VARCHAR(50) NOT NULL DEFAULT 'right_thumb',
    capture_method ENUM('manual_upload', 'mobile_capture', 'scanner') DEFAULT 'manual_upload',
    quality_status ENUM('clear', 'needs_review', 'missing') DEFAULT 'clear',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_citizen_fingerprint FOREIGN KEY (citizen_id) REFERENCES citizens(id) ON DELETE CASCADE
) ENGINE = InnoDB;

-- ============================================================
-- SYSTEM SETTINGS DATA
-- ============================================================
INSERT INTO
    system_settings (setting_key, setting_value, description)
VALUES
    (
        'system_name',
        'Electronic National Registration Bureau',
        'Application name'
    ),
    (
        'session_timeout',
        '1800',
        'Session timeout seconds'
    ),
    (
        'maintenance_mode',
        'false',
        'Enable maintenance mode'
    ),
    (
        'max_upload_size',
        '5242880',
        'Maximum upload size'
    );

-- ============================================================
-- ENABLE FOREIGN KEYS
-- ============================================================
SET
    FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- END CORE SCHEMA
-- ============================================================