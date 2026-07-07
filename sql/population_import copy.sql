LOAD DATA INFILE '/var/lib/mysql-files/17100009.csv'
INTO TABLE population
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(
  ref_date,
  geo,
  dguid,
  uom,
  uom_id,
  scalar_factor,
  scalar_id,
  vector,
  coordinate,
  @value,
  @status,
  @symbol,
  @terminated1,
  decimals
)
SET
  value = NULLIF(@value, ''),
  status = NULLIF(@status, ''),
  symbol = NULLIF(@symbol, ''),
  terminated1 = NULLIF(@terminated1, '');


/*
  /var/lib/mysql-files/
  SHOW VARIABLES LIKE 'secure_file_priv';



  sudo cp /var/www/html/stats/data/17100009.csv /var/lib/mysql-files/

*/