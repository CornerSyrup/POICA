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

CREATE TABLE Applic.Prefill (
	entryID SERIAL,
	userID INT NOT NULL enFName VARCHAR(30),
	enLName VARCHAR(30),
	birthDay DATE,
	-- true for male, false for female
	gender BIT(1),
	-- triple size for ja, 100 char
	addr VARCHAR(300),
	-- graduate year and month, for graduates
	gradMonth DATE,
	phone CHAR(10),
	CONSTRAINT PK_Prefill PRIMARY KEY (entryID),
	CONSTRAINT FK_User FOREIGN KEY (userID) REFERENCES Usership.Users(userID)
);