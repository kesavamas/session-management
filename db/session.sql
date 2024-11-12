DROP TABLE IF EXISTS session;

CREATE TABLE session(
	id VARCHAR(255) UNIQUE,
	payload TEXT,
	last_access BIGINT
);