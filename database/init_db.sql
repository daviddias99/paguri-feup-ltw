DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS residencetype;
DROP TABLE IF EXISTS residence;
DROP TABLE IF EXISTS reservation;
DROP TABLE IF EXISTS availability;
DROP TABLE IF EXISTS residencePhoto;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS reply;
DROP TABLE IF EXISTS commodity;
DROP TABLE IF EXISTS residencehascommodity;

CREATE TABLE user(
    userID INTEGER CONSTRAINT userPK PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    firstName TEXT NOT NULL,
    lastName TEXT NOT NULL,
    password TEXT NOT NULL,
    biography TEXT,
    photo TEXT
);

CREATE TABLE residencetype(
    residenceTypeID INTEGER CONSTRAINT residenceTypePK PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE residence(
    residenceID INTEGER CONSTRAINT residencePK PRIMARY KEY,
    owner INTEGER NOT NULL REFERENCES user,
    title TEXT NOT NULL,
    description TEXT,
    pricePerDay REAL NOT NULL CHECK(pricePerDay>=0),
    location TEXT NOT NULL,
    capacity INTEGER NOT NULL CHECK(capacity>=0),
    nBedrooms INTEGER CHECK(nBedrooms>=0),
    nBathrooms INTEGER CHECK(nBathrooms>=0),
    nBeds INTEGER CHECK(nBeds>=0),
    type INTEGER NOT NULL REFERENCES residenceType
);

CREATE TABLE residencePhoto(
    photoID INTEGER CONSTRAINT photoPK PRIMARY KEY,
    lodge INTEGER NOT NULL REFERENCES residence,
    filepath TEXT NOT NULL,
    priority INTEGER DEFAULT 0
);

CREATE TABLE reservation(
    reservationID INTEGER CONSTRAINT reservationPK PRIMARY KEY,
    lodge INTEGER NOT NULL REFERENCES residence,
    customer INTEGER NOT NULL REFERENCES user,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    numPeople INTEGER NOT NULL CHECK(numPeople>=0),
    CHECK(endDate > startDate)
);

CREATE TABLE availability(
    availabilityID INTEGER CONSTRAINT availabilityPK PRIMARY KEY,
    lodge INTEGER NOT NULL REFERENCES residence,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    CHECK(endDate > startDate)
);

CREATE TABLE comment(
    commentID INTEGER CONSTRAINT commentPK PRIMARY KEY,
    booking INTEGER NOT NULL REFERENCES reservation,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    rating INTEGER NOT NULL CHECK(rating >= 0 and rating <= 5),
    datestamp DATE NOT NULL
);

CREATE TABLE reply(
    replyID INTEGER CONSTRAINT replyPK PRIMARY KEY,
    author INTEGER NOT NULL REFERENCES user,
    parent INTEGER NOT NULL REFERENCES comment,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    datestamp DATE NOT NULL
);

CREATE TABLE commodity(
    commodityID INTEGER CONSTRAINT commodityPK PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE residencehascommodity(
	lodge INTEGER NOT NULL REFERENCES Residence,
	item INTEGER NOT NULL REFERENCES Commodity,
	CONSTRAINT residenceHasCommodityPK PRIMARY KEY(lodge, item)
);