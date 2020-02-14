CREATE DATABASE IF NOT EXISTS cert_manager;

USE cert_manager;

CREATE TABLE IF NOT EXISTS document_type
(
    constant    VARCHAR(255) NOT NULL,
    name        VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL DEFAULT '',

    PRIMARY KEY (constant)
);

CREATE TABLE IF NOT EXISTS template
(
    template_id   VARCHAR(255) NOT NULL,
    constant      VARCHAR(255) NOT NULL,
    name          VARCHAR(255) NOT NULL,
    body          MEDIUMTEXT   NOT NULL,
    created_on    DATETIME DEFAULT NOW(),
    updated_on    DATETIME,
    created_by    INT,
    document_type VARCHAR(255),

    PRIMARY KEY (template_id),
    FOREIGN KEY (document_type) REFERENCES document_type (constant)
);

CREATE TABLE IF NOT EXISTS document_data
(
    data_key      VARCHAR(255) NOT NULL,
    document_type VARCHAR(255) NOT NULL,
    data          TEXT,

    PRIMARY KEY (document_type, data_key),
    FOREIGN KEY (document_type) REFERENCES document_type (constant)
);
