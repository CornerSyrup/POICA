DROP SCHEMA IF EXISTS Applic CASCADE;

CREATE SCHEMA IF NOT EXISTS Applic;

CREATE TABLE Applic.Applications (
	appID SERIAL,
	-- status of application handling
	stat BIT(2) DEFAULT B'00' NOT NULL,
	applyUser INT NOT NULL,
	applyDate DATE NOT NULL DEFAULT NOW(),
	formData JSON,
	CONSTRAINT PK_Application PRIMARY KEY (appID),
	CONSTRAINT FK_App_Student FOREIGN KEY (applyUser) REFERENCES Usership.Users (userID)
);