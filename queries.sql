USE yeticave;

INSERT INTO categories
SET name = 'Доски и лыжи';
INSERT INTO categories
SET name = 'Крепления';
INSERT INTO categories
SET name = 'Ботинки';
INSERT INTO categories
SET name = 'Одежда';
INSERT INTO categories
SET name = 'Инструменты';
INSERT INTO categories
SET name = 'Разное';

INSERT INTO users
SET
  reg_date      = '2017-09-20',
  email         = 'ignat.v@gmail.com',
  name          = 'Игнат',
  password_hash = '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa',
  avatar_path   = 'img/avatar.jpg',
  contacts      = '+79787777712';

INSERT INTO users
SET
  reg_date      = '2017-09-20',
  email         = 'kitty_93@li.ru',
  name          = 'Леночка',
  password_hash = '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa',
  avatar_path   = 'img/avatar.jpg',
  contacts      = '+79788888712';

INSERT INTO users
SET
  reg_date      = '2017-09-20',
  email         = 'warrior07@mail.ru',
  name          = 'Руслан',
  password_hash = '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW',
  avatar_path   = 'img/avatar.jpg',
  contacts      = 'пишите на мыло warrior07@mail.ru или ищите ВК Руся';

INSERT INTO lots
SET
  title       = '2014 Rossignol District Snowboard',
  description = 'Описание отсутствует.',
  create_date = '2017-09-20 20:04:00',
  finish_date = '2017-09-22 12:00:00',
  image       = 'img/lot-1.jpg',
  start_price = '10999',
  bet_step    = '100',
  fav_count   = '0',
  category_id = '1',
  author_id   = '1',
  winner_id   = NULL;

INSERT INTO lots
SET
  title       = 'DC Ply Mens 2016/2017 Snowboard',
  description = 'Описание отсутствует.',
  create_date = '2017-09-20 20:04:00',
  finish_date = '2017-09-22 12:00:00',
  image       = 'img/lot-2.jpg',
  start_price = '159999',
  bet_step    = '100',
  fav_count   = '5',
  category_id = '1',
  author_id   = '2',
  winner_id   = NULL;

INSERT INTO lots
SET
  title       = 'Крепления Union Contact Pro 2015 года размер L/XL',
  description = 'Описание отсутствует.',
  create_date = '2017-09-19 20:04:00',
  finish_date = '2017-09-20 12:00:00',
  image       = 'img/lot-3.jpg',
  start_price = '8000',
  bet_step    = '100',
  fav_count   = '0',
  category_id = '2',
  author_id   = '1',
  winner_id   = '3';

INSERT INTO lots
SET
  title       = 'Ботинки для сноуборда DC Mutiny Charocal',
  description = 'Описание отсутствует.',
  create_date = '2017-09-16 18:04:00',
  finish_date = '2017-09-25 19:00:00',
  image       = 'img/lot-4.jpg',
  start_price = '10999',
  bet_step    = '100',
  fav_count   = '3',
  category_id = '3',
  author_id   = '1',
  winner_id   = NULL;

INSERT INTO lots
SET
  title       = 'Куртка для сноуборда DC Mutiny Charocal',
  description = 'Описание отсутствует.',
  create_date = '2017-09-20 20:04:00',
  finish_date = '2017-09-22 12:00:00',
  image       = 'img/lot-5.jpg',
  start_price = '7500',
  bet_step    = '300',
  fav_count   = '0',
  category_id = '4',
  author_id   = '3',
  winner_id   = NULL;

INSERT INTO lots
SET
  title       = 'Маска Oakley Canopy',
  description = 'Описание отсутствует.',
  create_date = '2017-09-01 20:04:00',
  finish_date = '2018-03-16 12:00:00',
  image       = 'img/lot-6.jpg',
  start_price = '5400',
  bet_step    = '100',
  fav_count   = '0',
  category_id = '6',
  author_id   = '1',
  winner_id   = NULL;

INSERT INTO bets
SET
  bet_date  = '2017-09-20 21:00:00',
  bet_value = '7200',
  author_id = '2',
  lot_id    = '6';

INSERT INTO bets
SET
  bet_date  = '2017-09-20 22:30:00',
  bet_value = '7500',
  author_id = '3',
  lot_id    = '6';

INSERT INTO bets
SET
  bet_date  = '2017-09-20 09:13:30',
  bet_value = '9000',
  author_id = '3',
  lot_id    = '3';

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
  JOIN categories
    ON categories.id = lots.category_id
  LEFT JOIN bets
    ON bets.lot_id = lots.id
WHERE lots.finish_date > NOW()
GROUP BY lots.id
ORDER BY lots.create_date DESC;

/* Получение лота, если название 'Маска Oakley Canopy' или описание включает слово 'Описание' */
SELECT *
FROM lots
WHERE title = 'Маска Oakley Canopy' OR description = '%Описание%';

/* Обновление название лота, у которого id = 6 */
UPDATE lots
SET title = 'Маска Oakley Canopy ЭКСКЛЮЗИВ!'
WHERE id = 6;

/* Получение ставок для лота с id = 6, сортированных по дате добавления в обратном порядке*/
SELECT *
FROM bets
WHERE lot_id = 6
ORDER BY bet_date DESC;