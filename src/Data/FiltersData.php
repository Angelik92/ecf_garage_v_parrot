<?php

namespace App\Data;

class FiltersData
{
    public int $page = 1;
    public null|string $quickSearch;
    public array $brands = [];
    public null|float $max;
    public null|float $min;
    public array $gearboxes = [];
    public array $fuels = [];
    public null|int $maxKm;
    public null|int $minKm;


}