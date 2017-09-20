CREATE DATABASE yeticave;
USE yeticave;

CREATE TABLE categories (
  id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name VARCHAR(50)                             NOT NULL,
  UNIQUE INDEX (name)
);

CREATE TABLE lots (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  title       VARCHAR(100)                            NOT NULL,
  description TEXT                                    NOT NULL,
  create_date DATETIME                                NOT NULL,
  finish_date DATETIME                                NOT NULL,
  image       VARCHAR(100),
  start_price INT(10) UNSIGNED                        NOT NULL,
  bet_step    INT UNSIGNED                            NOT NULL,
  fav_count   SMALLINT(10) UNSIGNED                   NOT NULL,
  author_id   INT UNSIGNED                            NOT NULL,
  winner_id   INT UNSIGNED                            NOT NULL,
  category_id INT UNSIGNED                            NOT NULL,
  INDEX (title),
  INDEX (start_price)
);

CREATE TABLE bets (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  bet_date  DATETIME                                NOT NULL,
  bet_value SMALLINT(10) UNSIGNED                   NOT NULL,
  author_id INT UNSIGNED                            NOT NULL,
  lot_id    INT UNSIGNED                            NOT NULL,
  INDEX (bet_value)
);

CREATE TABLE users (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  reg_date        DATE                                    NOT NULL,
  email           VARCHAR(100)                            NOT NULL,
  name            VARCHAR(100)                            NOT NULL,
  password_hash   VARCHAR(100)                            NOT NULL,
  avatar_path     VARCHAR(100),
  contacts        VARCHAR(500),
  INDEX (name),
  UNIQUE INDEX (email)
);

