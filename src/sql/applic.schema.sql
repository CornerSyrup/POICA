DROP SCHEMA IF EXISTS Applic CASCADE;

CREATE SCHEMA IF NOT EXISTS Applic;

CREATE TABLE Applic.InterResultAttend(
	interID SERIAL,
	-- iso 3166-1
	nation CHAR(3),
	resCardNo CHAR(12),
	immiDate DATE,
	enterDate DATE,
	resExpDate DATE,
	gradEx DATE,
	CONSTRAINT PK_Inter PRIMARY KEY (interID)
);

CREATE TABLE Applic.Applications(
	appID SERIAL,
	-- status of application handling
	stat BIT(2) NOT NULL,
	appUserID INT NOT NULL,
	applyDate DATE NOT NULL,
	-- 4 kind enum, exclude other
	purpose BIT(2) NOT NULL,
	-- type of document, 1~7, exclude other
	typeDoc BIT(3) NOT NULL,
	-- carbon copy of issued doc
	carbon SMALLINT,
	-- true for ja, false for en
	lang BIT(1),
	interSubForm INT,
	CONSTRAINT PK_Application PRIMARY KEY (appID),
	CONSTRAINT FK_App_Student FOREIGN KEY (appUserID) REFERENCES Usership.Users (userID),
	CONSTRAINT FK_App_International FOREIGN KEY (interSubForm) REFERENCES Applic.InterResultAttend (interID)
);