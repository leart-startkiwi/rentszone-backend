<?php

namespace Botble\CarRentals\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface CarInterface extends RepositoryInterface
{
    public function getCars(array $filters = [], array $params = []);
}
