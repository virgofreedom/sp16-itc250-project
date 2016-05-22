# sessions table for fl12 - itc290 class

CREATE TABLE fl12_sessions ( 
PHPSessID CHAR(32) NOT NULL, 
SessionData TEXT, 
LastAccessed TIMESTAMP NOT NULL, 
PRIMARY KEY (PHPSessID),
KEY (LastAccessed)
);
