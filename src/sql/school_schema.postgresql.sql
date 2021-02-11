DROP SCHEMA IF EXISTS School CASCADE;

CREATE SCHEMA IF NOT EXISTS School;

-- table store teachers' data
CREATE TABLE School.Teachers(
	teacherID CHAR(6) NOT NULL,
	-- triple for ja, 4 char
	firstName VARCHAR(12) NOT NULL,
	-- triple for ja, 12 char
	lastName VARCHAR(36) NOT NULL,
	CONSTRAINT PK_Teacher PRIMARY KEY (teacherID)
);

-- table for classes, not lessons
CREATE TABLE School.Classes(
	classID SERIAL,
	code CHAR(8) NOT NULL,
	yr CHAR(2) NOT NULL,
	teacher CHAR(6) NOT NULL,
	CONSTRAINT PK_Class PRIMARY KEY (classID),
	CONSTRAINT FK_ClassTeacher FOREIGN KEY (teacher) REFERENCES School.Teachers (teacherID)
);

CREATE TABLE School.Class_Student (
	classID INT NOT NULL,
	userID INT NOT NULL,
	CONSTRAINT FK_Class FOREIGN KEY (classID) REFERENCES School.Classes (classID),
	CONSTRAINT FK_Student FOREIGN KEY (userID) REFERENCES Usership.Users (userID)
);