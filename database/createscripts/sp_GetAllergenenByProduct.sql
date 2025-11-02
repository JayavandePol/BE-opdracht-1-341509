use laravel;

DROP PROCEDURE IF EXISTS sp_GetAllergenenByProduct;

DELIMITER $$

CREATE PROCEDURE sp_GetAllergenenByProduct(
    IN p_productId INT
)
BEGIN

    SELECT PROD.Id AS ProductId
          ,PROD.Naam AS ProductNaam
          ,PROD.Barcode
          ,ALGE.Naam AS AllergeenNaam
          ,ALGE.Omschrijving AS AllergeenOmschrijving
    FROM  Product AS PROD
    LEFT JOIN ProductPerAllergeen AS PPA
           ON PPA.ProductId = PROD.Id
    LEFT JOIN Allergeen AS ALGE
           ON ALGE.Id = PPA.AllergeenId
    WHERE PROD.Id = p_productId;

END$$

DELIMITER ;
