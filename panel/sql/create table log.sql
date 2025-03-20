CREATE TABLE log (
    [key] CHAR (10)     NOT NULL,
    ts    DATETIME      NOT NULL,
    value VARCHAR (255) NOT NULL,
    PRIMARY KEY (
        [key],
        ts
    )
);