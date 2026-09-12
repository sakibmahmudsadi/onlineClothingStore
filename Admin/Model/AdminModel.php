<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/ProductModel.php';
require_once __DIR__ . '/UserModel.php';
require_once __DIR__ . '/OrderModel.php';

class AdminModel
{
    public static function dashboard(): array
    {
        return [
            'products' => ProductModel::count(),
            'customers' => UserModel::countCustomers(),
            'orders' => OrderModel::count(),
            'pending_orders' => OrderModel::pendingCount()
        ];
    }
}
