DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS residence;
DROP TABLE IF EXISTS reservation;
DROP TABLE IF EXISTS availability;
DROP TABLE IF EXISTS photo;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS reply;
DROP TABLE IF EXISTS residencehascomodity;
DROP TABLE IF EXISTS comodity;

CREATE TABLE user(
    userID INT CONSTRAINT userPK PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    salt TEXT NOT NULL,
    pwdHash TEXT NOT NULL,
    biography TEXT,
    photo TEXT
);

CREATE TABLE residence(
    residenceID INT CONSTRAINT residencePK PRIMARY KEY,
    owner INT NOT NULL REFERENCES user,
    title TEXT NOT NULL,
    description TEXT,
    pricePerDay REAL NOT NULL CHECK(pricePerDay>=0),
    location TEXT NOT NULL,
    capacity INT NOT NULL CHECK(capacity>=0),
    nBedrooms INT CHECK(nBedrooms>=0),
    nBathrooms INT CHECK(nBathrooms>=0),
    nBeds INT CHECK(nBeds>=0),
    type TEXT NOT NULL
);

CREATE TABLE reservation(
    reservationID INT CONSTRAINT reservationPK PRIMARY KEY,
    lodge INT NOT NULL REFERENCES residence,
    customer INT NOT NULL REFERENCES user,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    numPeople INT NOT NULL CHECK(numPeople>=0),
    CHECK(endDate > startDate)
);

CREATE TABLE availability(
    availabilityID INT CONSTRAINT availabilityPK PRIMARY KEY,
    lodge INT NOT NULL REFERENCES residence,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    CHECK(startDate > endDate)
);

CREATE TABLE photo(
    photoID INT CONSTRAINT photoPK PRIMARY KEY,
    lodge INT NOT NULL REFERENCES residence,
    filepath TEXT NOT NULL,
    priority INT DEFAULT 0
);

CREATE TABLE comment(
    commentID INT CONSTRAINT commentPK PRIMARY KEY,
    author INT NOT NULL REFERENCES user,
    lodge INT NOT NULL REFERENCES residence,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    rating INT NOT NULL CHECK(rating >= 0 and rating <= 5),
    datestamp DATE NOT NULL
);

CREATE TABLE reply(
    replyID INT CONSTRAINT replyPK PRIMARY KEY,
    author INT NOT NULL REFERENCES user,
    parent INT NOT NULL REFERENCES comment,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    datestamp DATE NOT NULL
);

CREATE TABLE residencehascomodity(
	lodge INT NOT NULL REFERENCES Residence,
	item INT NOT NULL REFERENCES Comodity,
	CONSTRAINT residenceHasComodityPK PRIMARY KEY(lodge, item)
);

CREATE TABLE comodity(
    comodityID INT CONSTRAINT comodityPK PRIMARY KEY,
    name TEXT NOT NULL
);