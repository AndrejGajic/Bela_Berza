CREATE DATABASE IF NOT EXISTS dbBelaBerza;

USE dbBelaBerza;

CREATE TABLE Stock
(
	IdStock              INTEGER NOT NULL AUTO_INCREMENT,
	companyName          VARCHAR(30) NOT NULL,
	value                DECIMAL(10,2) NULL,
	rate                 DECIMAL(5,2) NULL,
	imagePath            VARCHAR(34) NOT NULL,
	availableQty         INTEGER NULL,
	isVolatile           boolean NOT NULL DEFAULT 0,
	CONSTRAINT XPKStock PRIMARY KEY (IdStock)
);

CREATE TABLE User
(
	IdUser               INTEGER NOT NULL AUTO_INCREMENT,
	username             VARCHAR(30) NOT NULL,
	password             VARCHAR(30) NOT NULL,
	email                VARCHAR(50) NOT NULL,
	name                 VARCHAR(20) NOT NULL,
	surname              VARCHAR(30) NOT NULL,
	imagePath            VARCHAR(34) NULL,
	balance              DECIMAL(10,2) NOT NULL DEFAULT 0,
	CONSTRAINT XPKUser PRIMARY KEY (IdUser)
);

CREATE TABLE UserOwnsStock
(
	IdStock              INTEGER NOT NULL,
	IdUser               INTEGER NOT NULL,
	quantity             INTEGER NULL DEFAULT 0 CHECK ( quantity >= 0 ),
	CONSTRAINT XPKUserOwnsStock PRIMARY KEY (IdStock,IdUser),
	CONSTRAINT R_4 FOREIGN KEY (IdStock) REFERENCES Stock (IdStock),
	CONSTRAINT R_5 FOREIGN KEY (IdUser) REFERENCES User (IdUser)
);

CREATE TABLE Transaction
(
	IdTransaction        INTEGER NOT NULL AUTO_INCREMENT,
	timestamp            TIMESTAMP NOT NULL,
	amount               DECIMAL(10,2) NOT NULL,
	IdUser               INTEGER NOT NULL,
	type                 INTEGER NOT NULL,
	CONSTRAINT XPKTransaction PRIMARY KEY (IdTransaction),
	CONSTRAINT R_7 FOREIGN KEY (IdUser) REFERENCES User (IdUser)
);

CREATE TABLE Administrator
(
	IdAdministrator      INTEGER NOT NULL AUTO_INCREMENT,
	username             VARCHAR(30) NOT NULL,
	password             VARCHAR(30) NOT NULL,
	email                VARCHAR(50) NULL,
	CONSTRAINT XPKAdministrator PRIMARY KEY (IdAdministrator)
);

CREATE TABLE Registration
(
	IdRegistration       INTEGER NOT NULL AUTO_INCREMENT,
	name                 VARCHAR(20) NOT NULL,
	surname              VARCHAR(30) NOT NULL,
	email                VARCHAR(50) NOT NULL,
	password             VARCHAR(30) NOT NULL,
	username             VARCHAR(30) NOT NULL,
	ipAddress            VARCHAR(15) NULL,
	status               boolean NULL,
	IdAdministrator      INTEGER NULL,
	CONSTRAINT XPKRegistration PRIMARY KEY (IdRegistration),
	CONSTRAINT R_8 FOREIGN KEY (IdAdministrator) REFERENCES Administrator (IdAdministrator)
);

CREATE TABLE PrivilegedUser
(
	IdUser               INTEGER NOT NULL,
	startDate            DATE NOT NULL,
	endDate              DATE NOT NULL,
	CONSTRAINT XPKPrivilegedUser PRIMARY KEY (IdUser),
	CONSTRAINT R_10 FOREIGN KEY (IdUser) REFERENCES User (IdUser)
		ON DELETE CASCADE
);

CREATE TABLE BankAccount
(
	BankAccountNumber    VARCHAR(18) NOT NULL,
	balance              DECIMAL(10,2) NOT NULL DEFAULT 0 CHECK ( balance >= 0 ),
	CONSTRAINT XPKBankAccount PRIMARY KEY (BankAccountNumber)
);

CREATE TABLE CreditCard
(
	CreditCardNumber     VARCHAR(16) NOT NULL,
	BankAccountNumber    VARCHAR(18) NOT NULL,
	OwnerName            VARCHAR(20) NULL,
	OwnerSurname         VARCHAR(30) NULL,
	CVC                  INTEGER NULL,
	expirationDate       VARCHAR(7) NULL,
	CONSTRAINT XPKCreditCard PRIMARY KEY (CreditCardNumber),
	CONSTRAINT R_11 FOREIGN KEY (BankAccountNumber) REFERENCES BankAccount (BankAccountNumber)
);


CREATE TABLE StockTransaction
(
	IdUser               INTEGER NOT NULL,
	IdStock              INTEGER NOT NULL,
	IdStockTransaction   INTEGER NOT NULL AUTO_INCREMENT,
	totalPrice           DECIMAL(10,2) NULL,
	quantity             INTEGER NULL,
	type                 INTEGER NULL,
	CONSTRAINT XPKStockTransaction PRIMARY KEY (IdStockTransaction),
	CONSTRAINT R_12 FOREIGN KEY (IdUser) REFERENCES User (IdUser),
	CONSTRAINT R_13 FOREIGN KEY (IdStock) REFERENCES Stock (IdStock)
);