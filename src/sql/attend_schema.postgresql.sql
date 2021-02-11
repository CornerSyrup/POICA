DROP SCHEMA IF EXISTS Attendance CASCADE;

CREATE SCHEMA IF NOT EXISTS Attendance;

CREATE TABLE Attendance.LessonBase (
    code CHAR(4) NOT NULL,
    -- triple for ja, 20 char
    fullName CHAR(60) NOT NULL,
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

CREATE TABLE Attendance.AttendLog (
    userID INT NOT NULL,
    lessonID INT NOT NULL,
    checkIn TIMESTAMP NOT NULL DEFAULT NOW(),
    checkOut TIMESTAMP NOT NULL,
    CONSTRAINT FK_Attend_Student FOREIGN KEY (userID) REFERENCES Usership.Users (userID),
    CONSTRAINT FK_Attend_Lesson FOREIGN KEY (lessonID) REFERENCES Attendance.Lessons (lessonID)
);