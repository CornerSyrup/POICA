DROP SCHEMA IF EXISTS Usership CASCADE;
DROP SCHEMA IF EXISTS School CASCADE;
DROP SCHEMA IF EXISTS Attendance CASCADE;
DROP SCHEMA IF EXISTS Applic CASCADE;

CREATE SCHEMA IF NOT EXISTS Usership;
CREATE SCHEMA IF NOT EXISTS School;
CREATE SCHEMA IF NOT EXISTS Attendance;
CREATE SCHEMA IF NOT EXISTS Applic;

-- user table
CREATE TABLE Usership.Users (
    userID SERIAL,
    -- Student ID and Teacher ID common field.
    sid CHAR(5) NOT NULL,
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

-- table store teachers' data
CREATE TABLE School.Teachers(
	tID CHAR(6) NOT NULL,
	-- triple for ja, 4 char
	fName VARCHAR(12) NOT NULL,
	-- triple for ja, 12 char
	lName VARCHAR(36) NOT NULL,
	-- php return pwd hash in length of 60
    pwd CHAR(60) NOT NULL,
	-- SHA256 hash result in length of 64
    suica CHAR(64),
	CONSTRAINT PK_Teacher PRIMARY KEY (teacherID)
);

CREATE TABLE Usership.TeacherAccess (
    userID CHAR(6) NOT NULL,
    moment TIMESTAMP NOT NULL DEFAULT NOW(),
    CONSTRAINT FK_Access_Teacher FOREIGN KEY (userID) REFERENCES School.Teachers (teacherID)
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

CREATE TABLE Attendance.LessonBase (
    code CHAR(4) NOT NULL,
    -- triple for ja, 20 char
    fullName VARCHAR(60) NOT NULL,
    CONSTRAINT PK_Lessons PRIMARY KEY (code)
);

CREATE TABLE Attendance.Lessons (
    lessonID SERIAL,
    code CHAR(4) NOT NULL,
    yr CHAR(2) NOT NULL,
    teacher CHAR(6) NOT NULL,
    CONSTRAINT PK_LessonTeacher PRIMARY KEY (lessonID),
    CONSTRAINT FK_Teacher_Lesson FOREIGN KEY (code) REFERENCES Attendance.LessonBase (code),
    CONSTRAINT FK_LessonTeacher FOREIGN KEY (teacher) REFERENCES School.Teachers (teacherID)
);

CREATE TABLE Attendance.Lesson_Student (
    lesson INT NOT NULL,
    student INT NOT NULL,
    CONSTRAINT UQ_Pair UNIQUE (lesson, student),
    CONSTRAINT FK_Lesson FOREIGN KEY (lesson) REFERENCES Attendance.Lessons (lessonID),
    CONSTRAINT FK_Student FOREIGN KEY (student) REFERENCES Usership.Users (userID)
);

CREATE TABLE Attendance.AttendLog (
    userID INT NOT NULL,
    lessonID INT NOT NULL,
    checkIn TIMESTAMP NOT NULL DEFAULT NOW(),
    checkOut TIMESTAMP,
    CONSTRAINT FK_Attend_Student FOREIGN KEY (userID) REFERENCES Usership.Users (userID),
    CONSTRAINT FK_Attend_Lesson FOREIGN KEY (lessonID) REFERENCES Attendance.Lessons (lessonID),
    CONSTRAINT FK_Attend_Enrol FOREIGN KEY (userID, lessonID) REFERENCES Attendance.Lesson_Student (student, lesson)
);

CREATE TABLE Applic.Applications (
	entry SERIAL,
	-- status of application handling
	stat BIT(2) DEFAULT B'00' NOT NULL,
	applyUser INT NOT NULL,
	applyDate DATE NOT NULL DEFAULT CURRENT_DATE,
	formData JSON NOT NULL,
	formType VARCHAR(6) NOT NULL,
	CONSTRAINT PK_Application PRIMARY KEY (entry),
	CONSTRAINT FK_App_Student FOREIGN KEY (applyUser) REFERENCES Usership.Users (userID)
);

CREATE TABLE Applic.Prefills (
	entryID SERIAL,
	userID INT NOT NULL,
	enFName VARCHAR(30),
	enLName VARCHAR(30),
	birthDay DATE,
	-- true for male, false for female
	gender BIT(1),
	-- triple size for ja, 150 char
	addr VARCHAR(450),
	-- graduate year and month, for graduates
	gradMonth DATE,
	phone CHAR(10),
	CONSTRAINT PK_Prefill PRIMARY KEY (entryID),
	CONSTRAINT FK_User FOREIGN KEY (userID) REFERENCES Usership.Users(userID)
);
