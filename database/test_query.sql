-- SELECT *
-- FROM residence JOIN (SELECT lodge, avg(rating) as rating
--                      FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
--                      GROUP BY lodge) as avgRatingPerResidence
--     ON residence.residenceID = avgRatingPerResidence.lodge;

SELECT residence.*, residencetype.name as type , rating
FROM residence JOIN residencetype ON residence.type = residenceTypeID 
                JOIN (SELECT lodge, avg(rating) as rating
                     FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
                     GROUP BY lodge) as avgRatingPerResidence
                ON residence.residenceID = avgRatingPerResidence.lodge;
            