CREATE SCHEMA IF NOT EXISTS cert_manager;

USE cert_manager;

CREATE TABLE IF NOT EXISTS template_type
(
    constant    VARCHAR(255) NOT NULL,
    name        VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL DEFAULT '',

    PRIMARY KEY (constant)
);


CREATE TABLE IF NOT EXISTS template
(
    template_id VARCHAR(255) NOT NULL,
    constant    VARCHAR(255) NOT NULL,
    name        VARCHAR(255) NOT NULL,
    body        TEXT         NOT NULL,
    created_on  DATETIME DEFAULT NOW(),
    updated_on  DATETIME,
    created_by  INT,
    type        VARCHAR(255),

    FOREIGN KEY (type) REFERENCES template_type (constant)
);
