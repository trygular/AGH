SELECT DISTINCT TABLE_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE COLUMN_NAME IN ('columnA','ColumnB')
        AND TABLE_SCHEMA='lmcsAdminTemp';



SELECT TABLE_NAME, COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE COLUMN_NAME LIKE '%' AND TABLE_SCHEMA='lmcsAdminTemp';
