USE yeticave;

INSERT INTO categories (name, class) VALUES
  ('Доски и лыжи', 'boards'),
  ('Крепления', 'attachment'),
  ('Ботинки', 'boots'),
  ('Одежда', 'clothing'),
  ('Инструменты', 'tools'),
  ('Разное', 'other');

INSERT INTO users (reg_date, email, name, password_hash, avatar_path, contacts) VALUES
  ('2017-09-20',
   'ignat.v@gmail.com',
   'Игнат',
   '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka',
   'img/avatar.jpg',
   '+79787777712'),

  ('2017-09-20',
   'kitty_93@li.ru',
   'Леночка',
   '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa',
   'img/avatar.jpg',
   '+79788888712'),

  ('2017-09-20',
   'warrior07@mail.ru',
   'Руслан',
   '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW',
   'img/avatar.jpg',
   'пишите на мыло warrior07@mail.ru или ищите ВК Руся');

INSERT INTO lots (title, description, create_date, finish_date, image, start_price, bet_step, fav_count, category_id, author_id, winner_id)
VALUES ('2014 Rossignol District Snowboard',
  'Описание отсутствует.',
  '2017-09-20 20:04:00',
  '2017-09-22 12:00:00',
  'img/lot-1.jpg',
  '10999',
  '100',
  '0',
  '1',
  '1',
  NULL),

  ('DC Ply Mens 2016/2017 Snowboard',
    'Описание отсутствует.',
    '2017-09-20 20:04:00',
    '2017-09-22 12:00:00',
    'img/lot-2.jpg',
    '159999',
    '100',
    '5',
    '1',
    '2',
    NULL),

  ('Крепления Union Contact Pro 2015 года размер L/XL',
    'Описание отсутствует.', '2017-09-19 20:04:00',
    '2017-09-20 12:00:00', 'img/lot-3.jpg',
    '8000',
    '100',
    '0',
    '2',
    '1',
    '3'),

  ('Ботинки для сноуборда DC Mutiny Charocal',
    'Описание отсутствует.',
    '2017-09-16 18:04:00',
    '2017-09-25 19:00:00',
    'img/lot-4.jpg',
    '10999',
    '100',
    '3',
    '3',
    '1',
    NULL),

  ('Куртка для сноуборда DC Mutiny Charocal',
    'Описание отсутствует.',
    '2017-09-20 18:04:00',
    '2017-09-22 19:00:00',
    'img/lot-5.jpg',
    '7500',
    '300',
    '0',
    '4',
    '3',
    NULL),

  ('Маска Oakley Canopy',
    'Описание отсутствует.',
    '2017-09-20 18:04:00',
    '2018-03-16 19:00:00',
    'img/lot-6.jpg',
    '5400',
    '100',
    '0',
    '6',
    '1',
    NULL);

INSERT INTO bets (bet_date, bet_value, author_id, lot_id)
VALUES ('2017-09-20 21:00:00',
        '7200',
        '2',
        '6'
),

  ('2017-09-20 22:30:00',
   '7500',
   '3',
   '6'
  ),

  ('2017-09-20 09:13:30',
   '9000',
   '3',
   '3'
  );

/* Получение категорий */
SELECT name
FROM categories;

/* Получение открытых лотов (конец аукциона еще не наступил), сортированных по дате добавления. В качестве текущей цены - текущая ставка. Если ставок нет, то цена - стартовая цена. */
SELECT
  title,
  start_price,
  image,
  IFNULL(MAX(bets.bet_value), lots.start_price) AS current_price,
  COUNT(bets.lot_id)                            AS bets_count,
  category_id
FROM lots
  LEFT JOIN categories
    ON categories.id = lots.category_id
  LEFT JOIN bets
    ON bets.lot_id = lots.id
WHERE lots.finish_date > NOW()
GROUP BY lots.id
ORDER BY lots.create_date DESC;

/* Получение лота, если название 'Маска Oakley Canopy' или описание включает слово 'Описание' */
SELECT *
FROM lots
WHERE title = 'Маска Oakley Canopy' OR description LIKE '%Описание%';

/* Обновление название лота, у которого id = 6 */
UPDATE lots
SET title = 'Маска Oakley Canopy ЭКСКЛЮЗИВ!'
WHERE id = 6;

/* Получение ставок для лота с id = 6, сортированных по дате добавления в обратном порядке*/
SELECT *
FROM bets
WHERE lot_id = 6
ORDER BY bet_date DESC;