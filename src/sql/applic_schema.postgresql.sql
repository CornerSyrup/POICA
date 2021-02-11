DROP SCHEMA IF EXISTS Applic CASCADE;

CREATE SCHEMA IF NOT EXISTS Applic;

CREATE TABLE Applic.Applications (
	appID SERIAL,
	-- status of application handling
	stat BIT(2) DEFAULT B'00' NOT NULL,
	applyUser INT NOT NULL,
	applyDate DATE NOT NULL DEFAULT CURRENT_DATE,
	formData JSON NOT NULL,
	formType CHAR(6) NOT NULL,
	CONSTRAINT PK_Application PRIMARY KEY (appID),
	CONSTRAINT FK_App_Student FOREIGN KEY (applyUser) REFERENCES Usership.Users (userID)
);

CREATE TABLE Applic.Prefills (
	entryID SERIAL,
	userID INT NOT NULL enFName VARCHAR(30),
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