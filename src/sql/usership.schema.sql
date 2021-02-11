DROP SCHEMA IF EXISTS Usership CASCADE;

CREATE SCHEMA IF NOT EXISTS Usership;

-- user table
CREATE TABLE Usership.Users(
    userID SERIAL,
    -- Student ID and Teacher ID common field.
    TSID VARCHAR(6) NOT NULL CHECK (
        char_length(TSID) = 5
        OR char_length(TSID) = 6
    ),
    -- last 2 digit of year
    studentYear CHAR(2) NOT NUL,
    -- php return pwd hash in length of 60
    pwd CHAR(60) NOT NULL,
    -- triple size for ja, 4 char
    jaFName VARCHAR(12) NOT NULL,
    -- triple size for ja, 12 char
    jaLName VARCHAR(36) NOT NULL,
    -- triple size for ja, 30 char
    jaFKana VARCHAR(90) NOT NULL,
    jaLKana VARCHAR(45) NOT NULL,
    -- SHA256 hash result in length of 64
    suica CHAR(64),
    CONSTRAINT PK_User PRIMARY KEY (userID),
    CONSTRAINT UQ_Suica UNIQUE (suica),
    CONSTRAINT UQ_Student UNIQUE (TSID, studentYear)
);

-- access control, logging
CREATE TABLE Usership.AccessLog(
    userID INT NOT NULL,
    -- postgre time stamp
    moment TIMESTAMP NOT NULL DEFAULT now(),
    CONSTRAINT FK_Access_User FOREIGN KEY (userID) REFERENCES Usership.Users (userID)
);