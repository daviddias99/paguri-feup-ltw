PRAGMA foreign_keys = ON;


INSERT INTO residencetype(name)
VALUES
    ('house'),
    ('apartment'),
    ('room'),
    ('castle');

INSERT INTO commodity(name)
VALUES
    ('elevator'),
    ('pets allowed'),
    ('kitchen'),
    ('internet'),
    ('towels'),
    ('breakfast');

INSERT INTO user(userID,username,email,firstName,lastName,password,biography,photo)
VALUES
(1,'daviddias99','david.luis@hotmail.com','David','Silva','$2y$12$pCN6ESriuEH1QzyoRgnYAuWPvtXE7ijkpIu8pktihjf3r2aKA41ym','Sou apaixonado pela natureza','default.jpg'),
(2,'mario_gil','advent@king.com','Mario','Gil','$2y$12$B82dgOrGgwjWK999m/CG/uaryWqvU8wbhsTaWpv6sbh3QqVSmW4MC','Adoro animais e alugar casas','default.jpg'),
(3,'luispcunha','luis@mistercimba.com','Luis','Cunha','$2y$12$ZyUquIFbu7H49mr9adIaWeVK.Sxxn0lA6j3mhxCZ8HRGiRKJAYkB6','Adoro xadrez e belas paisagens','default.jpg');


INSERT INTO residence(residenceID,owner, title, description, pricePerDay, capacity, nBedrooms, nBathrooms, nBeds, type, address, city, country, latitude, longitude)
VALUES
(1,1,'Casa muito bela','Casa muito bela e acolhedora. Terei muito gosto em receber pessoas cá.',1.0,4,2,2,2,1,'Hospital de São João 9623 4200-450 Porto, Portugal','Porto','Portugal',41.1793458575122,-8.60190620046046),
(2,1,'Apartamento espetacular com vista para tudo','Tal como disse, tem uma vista muito boa. E bons acessos!',125.0,2,1,5,5,2,'Rua Júlio Amaral de Carvalho 45 4200-135 Porto Portugal', 'Porto','Portugal',41.1762378139045,-8.60118520055539),
(3,1,'Casa remodelada T3','Excelente estado',1.0,4,3,2,3,1,'fep porto','Porto','Portugal',41.1752349,-8.5987891),
(4,2,'Casa de um colega meu','Esta casa não é minha. Mas estou a alugar na mesma. Nao digam ao dono.',89.0,4,3,2,3,1,'Cerrado do outeiro','Paços de Ferreira','Portugal',41.2753664,-8.3805515),
(5,2,'Castelo rustico','Castelo medieval em otimo estado',5000.0,100,30,15,30,4,'fmup','Porto','Portugal',41.1790259,-8.5997891);


INSERT INTO reservation(reservationID, lodge, customer, startDate, endDate, numPeople)
VALUES
(1,1,2,'2018-12-25','2018-12-26',1),
(2,2,2,'2019-11-27','2019-11-29',1),
(3,4,1,'2019-12-24','2019-12-27',1),
(4,5,3,'2020-01-07','2020-02-12',1),
(5,3,2,'2018-12-23','2018-12-27',1),
(6,3,1,'2018-12-30','2019-01-05',1);

INSERT INTO availability(availabilityID,lodge,startDate,endDate)
VALUES
(1,1,'2020-01-01','2020-01-31'),
(2,1,'2019-12-23','2019-12-27'),
(3,2,'2019-11-22','2019-11-31'),
(4,3,'2018-12-20','2019-01-08'),
(5,4,'2019-12-20','2019-12-31'),
(6,5,'2019-12-28','2020-03-10');

INSERT INTO residencehascommodity(lodge,item)
VALUES
(1,1),
(1,3),
(1,4),
(2,2),
(2,6),
(3,3),
(3,4),
(4,2),
(4,3),
(4,6),
(5,1),
(5,2),
(5,3),
(5,4),
(5,5),
(5,6);

INSERT INTO comment(commentID, booking, title, content, rating, datestamp)
VALUES
(1,1,'Gostei muito de estar aqui, mando uma boa review','Os vizinhos sao um pouco barulhentos',6.0,'2019/12/18 04:58'),
(2,5,'Foi incrível','Uma experiência memorável mesmo. Adorei os dias que passei com a minha família.',8.0,'2019/12/18 05:15'),
(3,6,'Foi espetacular','Estava mesmo a precisar de uns dias de relax. hehe :)',8.0,'2019/12/18 05:16');

INSERT INTO reply(replyID, author, parent, title, content, datestamp)
VALUES
(1,2,1,'Faltou dizer','O bolo rei que deixou para a minha chegada estava muito bom','2019/12/18 04:59'),
(2,1,1,'Ainda bem que gostou','De nada, recomende aos seus amigos!','2019/12/18 05:00');
