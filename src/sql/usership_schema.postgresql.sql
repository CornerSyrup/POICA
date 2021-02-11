DROP SCHEMA IF EXISTS Usership CASCADE;

CREATE SCHEMA IF NOT EXISTS Usership;

-- user table
CREATE TABLE Usership.Users (
    userID SERIAL,
    -- Student ID and Teacher ID common field.
    sid VARCHAR(5) NOT NULL,
    -- last 2 digit of year
    yr CHAR(2) NOT NULL,
    -- php return pwd hash in length of 60
    pwd CHAR(60) NOT NULL,
    -- triple size for ja, 4 char
    fName VARCHAR(12) NOT NULL,
    -- triple size for ja, 12 char
    lName VARCHAR(36) NOT NULL,
    -- triple size for ja, 30 char
    fKana VARCHAR(90) NOT NULL,
    lKana VARCHAR(45) NOT NULL,
    -- SHA256 hash result in length of 64
    suica CHAR(64),
    CONSTRAINT PK_User PRIMARY KEY (userID),
    CONSTRAINT UQ_Suica UNIQUE (suica),
    CONSTRAINT UQ_Student UNIQUE (sid, yr)
);

-- access control, logging
CREATE TABLE Usership.StudentAccess (
    userID INT NOT NULL,
    -- postgre time stamp
    moment TIMESTAMP NOT NULL DEFAULT NOW(),
    CONSTRAINT FK_Access_User FOREIGN KEY (userID) REFERENCES Usership.Users (userID)
);

CREATE TABLE Usership.TeacherAccess (
    userID CHAR(6) NOT NULL,
    moment TIMESTAMP NOT NULL DEFAULT NOW(),
    CONSTRAINT FK_Access_Teacher FOREIGN KEY (userID) REFERENCES School.Teachers (teacherID)
);