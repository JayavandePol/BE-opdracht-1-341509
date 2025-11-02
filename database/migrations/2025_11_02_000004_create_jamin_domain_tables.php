<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Allergeen', function (Blueprint $table) {
            $table->smallIncrements('Id');
            $table->string('Naam', 30);
            $table->string('Omschrijving', 100);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerkingen', 250)->nullable();
            $table->timestamp('DatumAangemaakt', 6)->useCurrent();
            $table->timestamp('DatumGewijzigd', 6)->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('Product', function (Blueprint $table) {
            $table->mediumIncrements('Id');
            $table->string('Naam', 255);
            $table->string('Barcode', 13);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerkingen', 255)->nullable();
            $table->timestamp('DatumAangemaakt', 6)->useCurrent();
            $table->timestamp('DatumGewijzigd', 6)->useCurrent()->useCurrentOnUpdate();
            $table->unique('Barcode', 'UK_Product_Barcode');
        });

        Schema::create('Leverancier', function (Blueprint $table) {
            $table->smallIncrements('Id');
            $table->string('Naam', 60);
            $table->string('Contactpersoon', 60);
            $table->string('Leveranciernummer', 11);
            $table->string('Mobiel', 15);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerkingen', 255)->nullable();
            $table->timestamp('DatumAangemaakt', 6)->useCurrent();
            $table->timestamp('DatumGewijzigd', 6)->useCurrent()->useCurrentOnUpdate();
            $table->unique('Leveranciernummer', 'UK_Leverancier_Leveranciernummer');
        });

        Schema::create('Magazijn', function (Blueprint $table) {
            $table->mediumIncrements('Id');
            $table->unsignedMediumInteger('ProductId');
            $table->decimal('VerpakkingsEenheid', 4, 1);
            $table->unsignedSmallInteger('AantalAanwezig')->nullable();
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerkingen', 255)->nullable();
            $table->timestamp('DatumAangemaakt', 6)->useCurrent();
            $table->timestamp('DatumGewijzigd', 6)->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ProductId', 'FK_Magazijn_ProductId_Product_Id')
                ->references('Id')
                ->on('Product')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::create('ProductPerAllergeen', function (Blueprint $table) {
            $table->mediumIncrements('Id');
            $table->unsignedMediumInteger('ProductId');
            $table->unsignedSmallInteger('AllergeenId');
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerkingen', 255)->nullable();
            $table->timestamp('DatumAangemaakt', 6)->useCurrent();
            $table->timestamp('DatumGewijzigd', 6)->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ProductId', 'FK_ProductPerAllergeen_ProductId_Product_Id')
                ->references('Id')
                ->on('Product')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('AllergeenId', 'FK_ProductPerAllergeen_AllergeenId_Allergeen_Id')
                ->references('Id')
                ->on('Allergeen')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::create('ProductPerLeverancier', function (Blueprint $table) {
            $table->mediumIncrements('Id');
            $table->unsignedSmallInteger('LeverancierId');
            $table->unsignedMediumInteger('ProductId');
            $table->date('DatumLevering');
            $table->unsignedInteger('Aantal');
            $table->date('DatumEerstVolgendeLevering')->nullable();
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerkingen', 255)->nullable();
            $table->timestamp('DatumAangemaakt', 6)->useCurrent();
            $table->timestamp('DatumGewijzigd', 6)->useCurrent()->useCurrentOnUpdate();

            $table->foreign('LeverancierId', 'FK_ProductPerLeverancier_LeverancierId_Leverancier_Id')
                ->references('Id')
                ->on('Leverancier')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('ProductId', 'FK_ProductPerLeverancier_ProductId_Product_Id')
                ->references('Id')
                ->on('Product')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProductPerLeverancier');
        Schema::dropIfExists('ProductPerAllergeen');
        Schema::dropIfExists('Magazijn');
        Schema::dropIfExists('Leverancier');
        Schema::dropIfExists('Product');
        Schema::dropIfExists('Allergeen');
    }
};
