LOAD DATA INFILE '/var/lib/mysql-files/immigration_clean.csv'
INTO TABLE immigration
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(
    ref_date,
    geo,
    dguid,
    age,
    gender,
    place_of_birth,
    immigrant_status_and_period_of_immigration,
    uom,
    uom_id,
    scalar_factor,
    scalar_id,
    vector,
    coordinate,
    @value,
    @status,
    @symbol,
    @terminated,
    @decimals
)
SET
    value = NULLIF(@value, ''),
    status = NULLIF(@status, ''),
    symbol = NULLIF(@symbol, ''),
    terminated = NULLIF(@terminated, ''),
    decimals = NULLIF(@decimals, '');