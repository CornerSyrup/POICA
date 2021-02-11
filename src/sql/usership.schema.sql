DROP SCHEMA IF EXISTS Usership CASCADE;

CREATE SCHEMA IF NOT EXISTS Usership;

-- user table
CREATE TABLE Usership.Users(
    userID SERIAL,
    studentID CHAR(5) NOT NULL,
    studentYear CHAR(2) NOT NULL,
    -- php return pwd hash in length of 60
    pwd CHAR(60) NOT NULL,
    -- triple size for ja, 4 char
    jaFName VARCHAR(12) NOT NULL,
    -- triple size for ja, 12 char
    jaLName VARCHAR(36) NOT NULL,
    -- triple size for ja, 30 char
    jaFKana VARCHAR(45) NOT NULL,
    jaLKana VARCHAR(45) NOT NULL,
    suica CHAR(16),
    CONSTRAINT PK_User PRIMARY KEY (userID),
    CONSTRAINT UQ_Suica UNIQUE (suica),
    CONSTRAINT UQ_Student UNIQUE (studentID, studentYear)
);

-- access control, logging
CREATE TABLE Usership.AccessLog(
    userID INT NOT NULL,
    moment TIMESTAMP NOT NULL,
    CONSTRAINT FK_Access_User FOREIGN KEY (userID) REFERENCES Usership.Users (userID)
);