PRAGMA foreign_keys = ON;

INSERT INTO user(username, email, salt, pwdHash, biography, photo)
VALUES
    ('admin', 'admin@paguri.com', '', '', 'this is my turf', '/path/to/image'),
    ('Piu piu monstro', 'piupiu@gmail.com', '', '', 'queria estar vivendo mas estou no twitter', '/path/to/image'),
    ('paguroidea', 'paguroidea@hotmail.com', '', '', 'looking for a new shell', '/path/to/image');

INSERT INTO residencetype(name)
VALUES
    ('house'),
    ('apartment'),
    ('room'),
    ('castle');

INSERT INTO residence(owner, title, description, pricePerDay, location, capacity, nBedrooms, nBathrooms, nBeds, type)
VALUES
    (1, 'Planet Mars', 'The whole planet is your residence', 999999999, 'Planet Mars', 0, 0, 0, 0, 1),
    (1, 'Mega Castle', 'very big castle', 1, 'Portugal', 24, 20, 18, 20, 4),
    (2, 'Toca do bicho', 'shared room with me. come in come in, its freeeee', 0, 'Russia', 1, 0, 0, 1, 3);

INSERT INTO reservation(lodge, customer, startDate, endDate, numPeople)
VALUES
    (3, 3, '2019-11-21 12:00:00', '2019-11-24 12:00:00', 1),
    (2, 1, '2019-11-17 12:00:00', '2019-11-21 12:00:00', 1);

INSERT INTO availability(lodge, startDate, endDate)
VALUES
    (2, '2019-09-21 12:00', '2019-12-21 12:00'),
    (3, '2019-10-21 12:00', '2019-11-29 12:00');

INSERT INTO photo(lodge, filepath, priority)
VALUES
    (2, '', 10),
    (2, '', 6),
    (3, '', 2),
    (3, '', 1);

INSERT INTO comment(author, lodge, title, content, rating, datestamp)
VALUES
    (2, 1, 'When will this be available???', 'I WANT TO GO TO MARSS', 0, '2019-09-15 12:24'),
    (3, 3, 'Very confortable', 'Loved the experience', 5, '2019-09-21 15:11');

INSERT INTO reply(author, parent, title, content, datestamp)
VALUES
    (1, 1, 'Ask Ellon', 'Try twitter, he may answer', '2019-09-16 12:00');

INSERT INTO comodity(name)
VALUES
    ('elevator'),
    ('pets allowed'),
    ('kitchen'),
    ('internet'),
    ('towels'),
    ('breakfast');

INSERT INTO residencehascomodity(lodge, item)
VALUES
    (2, 3),
    (2, 4),
    (2, 5),
    (2, 6),
    (3, 2),
    (3, 5);