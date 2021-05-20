
CREATE TABLE Administrator
(
	IdAdministrator      INTEGER NOT NULL,
	username             VARCHAR(30) NOT NULL,
	password             VARCHAR(30) NOT NULL,
	email                VARCHAR(50) NULL
);

ALTER TABLE Administrator
ADD CONSTRAINT XPKAdministrator PRIMARY KEY (IdAdministrator);

CREATE TABLE PrivilegedUser
(
	IdUser               INTEGER NOT NULL,
	startDate            DATE NOT NULL,
	endDate              DATE NOT NULL
);

ALTER TABLE PrivilegedUser
ADD CONSTRAINT XPKPrivilegedUser PRIMARY KEY (IdUser);

CREATE TABLE Registration
(
	IdRegistration       INTEGER NOT NULL,
	name                 VARCHAR(20) NOT NULL,
	surname              VARCHAR(30) NOT NULL,
	email                VARCHAR(50) NOT NULL,
	password             VARCHAR(30) NOT NULL,
	username             VARCHAR(30) NOT NULL,
	ipAddress            VARCHAR(15) NULL,
	status               boolean NULL,
	IdAdministrator      INTEGER NULL
);

ALTER TABLE Registration
ADD CONSTRAINT XPKRegistration PRIMARY KEY (IdRegistration);

CREATE TABLE Stock
(
	IdStock              INTEGER NOT NULL,
	companyName          VARCHAR(30) NOT NULL,
	value                DECIMAL(10,2) NULL,
	rate                 DECIMAL(5,2) NULL,
	imagePath            VARCHAR(34) NOT NULL,
	availableQty         INTEGER NULL,
	isVolatile           boolean NOT NULL DEFAULT 0
);

ALTER TABLE Stock
ADD CONSTRAINT XPKStock PRIMARY KEY (IdStock);

CREATE TABLE Transaction
(
	IdTransaction        INTEGER NOT NULL,
	timestamp            TIMESTAMP NOT NULL,
	amount               DECIMAL(10,2) NOT NULL,
	IdUser               INTEGER NOT NULL,
	type                 INTEGER NOT NULL
);

ALTER TABLE Transaction
ADD CONSTRAINT XPKTransaction PRIMARY KEY (IdTransaction);

CREATE TABLE User
(
	IdUser               INTEGER NOT NULL,
	username             VARCHAR(30) NOT NULL,
	password             VARCHAR(30) NOT NULL,
	email                VARCHAR(50) NOT NULL,
	name                 VARCHAR(20) NOT NULL,
	surname              VARCHAR(30) NOT NULL,
	imagePath            VARCHAR(34) NULL,
	balance              DECIMAL(10,2) NOT NULL DEFAULT 0
);

ALTER TABLE User
ADD CONSTRAINT XPKUser PRIMARY KEY (IdUser);

CREATE TABLE UserOwnsStock
(
	IdStock              INTEGER NOT NULL,
	IdUser               INTEGER NOT NULL,
	quantity             INTEGER NULL DEFAULT 0 CHECK ( quantity >= 0 )
);

ALTER TABLE UserOwnsStock
ADD CONSTRAINT XPKUserOwnsStock PRIMARY KEY (IdStock,IdUser);

ALTER TABLE PrivilegedUser
ADD CONSTRAINT R_10 FOREIGN KEY (IdUser) REFERENCES User (IdUser)
		ON DELETE CASCADE;

ALTER TABLE Registration
ADD CONSTRAINT R_8 FOREIGN KEY (IdAdministrator) REFERENCES Administrator (IdAdministrator);

ALTER TABLE Transaction
ADD CONSTRAINT R_7 FOREIGN KEY (IdUser) REFERENCES User (IdUser);

ALTER TABLE UserOwnsStock
ADD CONSTRAINT R_4 FOREIGN KEY (IdStock) REFERENCES Stock (IdStock);

ALTER TABLE UserOwnsStock
ADD CONSTRAINT R_5 FOREIGN KEY (IdUser) REFERENCES User (IdUser);
