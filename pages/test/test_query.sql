-- SELECT *
-- FROM residence JOIN (SELECT lodge, avg(rating) as rating
--                      FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
--                      GROUP BY lodge) as avgRatingPerResidence
--     ON residence.residenceID = avgRatingPerResidence.lodge;

-- SELECT residence.*, residencetype.name as type , rating
-- FROM residence JOIN residencetype ON residence.type = residenceTypeID 
--                 JOIN (SELECT lodge, avg(rating) as rating
--                      FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
--                      GROUP BY lodge) as avgRatingPerResidence
--                 ON residence.residenceID = avgRatingPerResidence.lodge;
            
-- SELECT residence.*, residencetype.name as type , rating
--         FROM residence JOIN residencetype ON residence.type = residenceTypeID 
--                         LEFT OUTER JOIN (SELECT lodge, avg(rating) as rating
--                              FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
--                              GROUP BY lodge) as avgRatingPerResidence
--                         ON residence.residenceID = avgRatingPerResidence.lodge;

-- SELECT residence.*, residencetype.name as typeStr , rating
--         FROM residence JOIN residencetype ON residence.type = residenceTypeID 
--                         LEFT OUTER JOIN (SELECT lodge, avg(rating) as rating
--                              FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
--                              GROUP BY lodge) as avgRatingPerResidence
--                         ON residence.residenceID = avgRatingPerResidence.lodge
--              WHERE capacity >= 0 AND nBeds >= 0 AND  ( pricePerDay BETWEEN 0 AND 5000  ) AND typeStr = 'House' AND (rating BETWEEN 0 AND 10)

-- SELECT residence.*, residencetype.name as typeStr , rating
--         FROM residence JOIN residencetype ON residence.type = residenceTypeID 
--                         LEFT OUTER JOIN (SELECT lodge, avg(rating) as rating
--                              FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
--                              GROUP BY lodge) as avgRatingPerResidence
--                         ON residence.residenceID = avgRatingPerResidence.lodge
--             WHERE capacity >= 0 AND nBeds >= 0 AND  ( pricePerDay BETWEEN 0 AND 5000  ) AND typeStr = 'house'  AND  ( rating BETWEEN 0 AND 10  )

SELECT residence.*, residencetype.name as typeStr , rating
        FROM residence JOIN residencetype ON residence.type = residenceTypeID 
                        LEFT OUTER JOIN (SELECT lodge, avg(rating) as rating
                             FROM comment JOIN reservation ON (comment.booking = reservation.reservationID) 
                             GROUP BY lodge) as avgRatingPerResidence
                        ON residence.residenceID = avgRatingPerResidence.lodge