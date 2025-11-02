<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Allergenen procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS Sp_GetAllAllergenen');
        DB::unprepared(<<<'SQL'
CREATE PROCEDURE Sp_GetAllAllergenen()
BEGIN
    SELECT ALGE.Id,
           ALGE.Naam,
           ALGE.Omschrijving
    FROM Allergeen AS ALGE;
END
SQL
        );

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_CreateAllergeen');
        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_CreateAllergeen(
    IN p_name VARCHAR(50),
    IN p_description VARCHAR(255)
)
BEGIN
    INSERT INTO Allergeen (Naam, Omschrijving)
    VALUES (p_name, p_description);

    SELECT LAST_INSERT_ID() AS new_id;
END
SQL
        );

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_DeleteAllergeen');
        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_DeleteAllergeen(
    IN p_id INT
)
BEGIN
    DELETE FROM Allergeen
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END
SQL
        );

        DB::unprepared('DROP PROCEDURE IF EXISTS Sp_GetAllergeenById');
        DB::unprepared(<<<'SQL'
CREATE PROCEDURE Sp_GetAllergeenById(
    IN p_id INT
)
BEGIN
    SELECT ALGE.Id,
           ALGE.Naam,
           ALGE.Omschrijving
    FROM Allergeen AS ALGE
    WHERE ALGE.Id = p_id;
END
SQL
        );

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_UpdateAllergeen');
        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_UpdateAllergeen(
    IN p_id INT,
    IN p_naam VARCHAR(50),
    IN p_omschrijving VARCHAR(255)
)
BEGIN
    UPDATE Allergeen
    SET Naam = p_naam,
        Omschrijving = p_omschrijving,
        DatumGewijzigd = SYSDATE(6)
    WHERE Id = p_id;

    SELECT ROW_COUNT() AS affected;
END
SQL
        );

        // Product procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetAllProducts');
        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_GetAllProducts()
BEGIN
    SELECT PROD.Id,
           PROD.Naam,
           PROD.Barcode,
           MAGA.VerpakkingsEenheid,
           MAGA.AantalAanwezig
    FROM Product AS PROD
    INNER JOIN Magazijn AS MAGA ON PROD.Id = MAGA.ProductId;
END
SQL
        );

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetLeverancierInfo');
        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_GetLeverancierInfo(
    IN p_productId INT
)
BEGIN
    SELECT PROD.Naam,
           PPLE.DatumLevering,
           PPLE.Aantal,
           PPLE.DatumEerstVolgendeLevering,
           MAGA.AantalAanwezig
    FROM Product AS PROD
    INNER JOIN ProductPerLeverancier AS PPLE ON PPLE.ProductId = PROD.Id
    INNER JOIN Magazijn AS MAGA ON MAGA.ProductId = PROD.Id
    WHERE PROD.Id = p_productId;
END
SQL
        );

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetLeverantieInfo');
        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_GetLeverantieInfo(
    IN p_Id INT
)
BEGIN
    SELECT DISTINCT LEVE.Id,
                    LEVE.Naam,
                    LEVE.Contactpersoon,
                    LEVE.Leveranciernummer,
                    LEVE.Mobiel
    FROM Leverancier AS LEVE
    INNER JOIN ProductPerLeverancier AS PPLE ON LEVE.Id = PPLE.LeverancierId
    WHERE PPLE.ProductId = p_Id;
END
SQL
        );

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetAllergenenByProduct');
        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_GetAllergenenByProduct(
    IN p_productId INT
)
BEGIN
    SELECT PROD.Id AS ProductId,
           PROD.Naam AS ProductNaam,
           PROD.Barcode,
           ALGE.Naam AS AllergeenNaam,
           ALGE.Omschrijving AS AllergeenOmschrijving
    FROM Product AS PROD
    LEFT JOIN ProductPerAllergeen AS PPA ON PPA.ProductId = PROD.Id
    LEFT JOIN Allergeen AS ALGE ON ALGE.Id = PPA.AllergeenId
    WHERE PROD.Id = p_productId;
END
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS Sp_GetAllAllergenen');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_CreateAllergeen');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_DeleteAllergeen');
        DB::unprepared('DROP PROCEDURE IF EXISTS Sp_GetAllergeenById');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_UpdateAllergeen');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetAllProducts');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetLeverancierInfo');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetLeverantieInfo');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetAllergenenByProduct');
    }
};
