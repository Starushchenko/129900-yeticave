<?
// Таблица товаров
$lots_list = [
    [
        "title" => "2014 Rossignol District Snowboard",
        "category" => "Доски и лыжи",
        "price" => "10999",
        "src" => "img/lot-1.jpg",
        "desc" => ''
    ],
    [
        "title" => "DC Ply Mens 2016/2017 Snowboard",
        "category" => "Доски и лыжи",
        "price" => "159999",
        "src" => "img/lot-2.jpg",
        "desc" => ''
    ],
    [
        "title" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "category" => "Крепления",
        "price" => "8000",
        "src" => "img/lot-3.jpg",
        "desc" => ''
    ],
    [
        "title" => "Ботинки для сноуборда DC Mutiny Charocal",
        "category" => "Ботинки",
        "price" => "10999",
        "src" => "img/lot-4.jpg",
        "desc" => ''
    ],
    [
        "title" => "Куртка для сноуборда DC Mutiny Charocal",
        "category" => "Одежда",
        "price" => "7500",
        "src" => "img/lot-5.jpg",
        "desc" => ''
    ],
    [
        "title" => "Маска Oakley Canopy",
        "category" => "Разное",
        "price" => "5400",
        "src" => "img/lot-6.jpg",
        "desc" => ''
    ]
];

// ставки пользователей, которыми надо заполнить таблицу
$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) . ' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) . ' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) . ' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];

// Массив категорий
$lots_categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
?>