<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductModel extends Model
{
    private function usingMySql(): bool
    {
        return DB::connection()->getDriverName() === 'mysql';
    }

    public function sp_GetAllProducts()
    {
        if ($this->usingMySql()) {
            return DB::select('CALL sp_GetAllProducts()');
        }

        return DB::table('Product as PROD')
            ->join('Magazijn as MAGA', 'PROD.Id', '=', 'MAGA.ProductId')
            ->select([
                'PROD.Id',
                'PROD.Naam',
                'PROD.Barcode',
                'MAGA.VerpakkingsEenheid',
                'MAGA.AantalAanwezig',
            ])
            ->get()
            ->all();
    }

    public function sp_GetLeverantieInfo(int $productId)
    {
        if ($this->usingMySql()) {
            return DB::select('CALL sp_GetLeverantieInfo(:id)', ['id' => $productId]);
        }

        return DB::table('Leverancier as LEVE')
            ->join('ProductPerLeverancier as PPLE', 'LEVE.Id', '=', 'PPLE.LeverancierId')
            ->select([
                'LEVE.Id',
                'LEVE.Naam',
                'LEVE.Contactpersoon',
                'LEVE.Leveranciernummer',
                'LEVE.Mobiel',
            ])
            ->where('PPLE.ProductId', $productId)
            ->distinct()
            ->get()
            ->all();
    }

    public function sp_GetLeverancierInfo(int $productId)
    {
        if ($this->usingMySql()) {
            return DB::select('CALL sp_GetLeverancierInfo(:id)', ['id' => $productId]);
        }

        return DB::table('Product as PROD')
            ->join('ProductPerLeverancier as PPLE', 'PPLE.ProductId', '=', 'PROD.Id')
            ->join('Magazijn as MAGA', 'MAGA.ProductId', '=', 'PROD.Id')
            ->select([
                'PROD.Naam',
                'PPLE.DatumLevering',
                'PPLE.Aantal',
                'PPLE.DatumEerstVolgendeLevering',
                'MAGA.AantalAanwezig',
            ])
            ->where('PROD.Id', $productId)
            ->orderBy('PPLE.DatumLevering')
            ->get()
            ->all();
    }

    public function sp_GetAllergenenByProduct(int $productId)
    {
        if ($this->usingMySql()) {
            return DB::select('CALL sp_GetAllergenenByProduct(:id)', ['id' => $productId]);
        }

        return DB::table('Product as PROD')
            ->leftJoin('ProductPerAllergeen as PPA', 'PPA.ProductId', '=', 'PROD.Id')
            ->leftJoin('Allergeen as ALGE', 'ALGE.Id', '=', 'PPA.AllergeenId')
            ->select([
                'PROD.Id as ProductId',
                'PROD.Naam as ProductNaam',
                'PROD.Barcode',
                'ALGE.Naam as AllergeenNaam',
                'ALGE.Omschrijving as AllergeenOmschrijving',
            ])
            ->where('PROD.Id', $productId)
            ->get()
            ->all();
    }
}
