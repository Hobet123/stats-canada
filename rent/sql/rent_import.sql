SELECT
    geo,

    CASE
        WHEN rental_unit_type LIKE 'Apartment%' THEN 'Apartment'
        WHEN rental_unit_type LIKE 'House%' THEN 'House'
        WHEN rental_unit_type LIKE 'Room%' THEN 'Room'
        ELSE 'Unknown'
    END AS property_type,

    CASE
        WHEN rental_unit_type LIKE '%No bedroom%' THEN 0
        WHEN rental_unit_type LIKE '%1 bedroom%' THEN 1
        WHEN rental_unit_type LIKE '%2 bedrooms%' THEN 2
        WHEN rental_unit_type LIKE '%3 or more%' THEN 3
        WHEN rental_unit_type LIKE 'Room%' THEN 1
        ELSE 0
    END AS bedrooms,

    STR_TO_DATE(CONCAT(ref_date, '-01'), '%Y-%m-%d') AS ref_date,

    CAST(value AS UNSIGNED) AS value

FROM rent_old
LIMIT 20;