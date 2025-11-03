CREATE OR ALTER PROCEDURE usp_Insert
    @TableName NVARCHAR(128),
    @Json NVARCHAR(MAX)
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @SQL NVARCHAR(MAX);

    -- Get columns
    DECLARE @Columns NVARCHAR(MAX) = JSON_VALUE(@Json, '$.columns');
    IF @Columns IS NULL OR @Columns = ''
        SET @Columns = '*';

    -- Start SQL
    SET @SQL = N'INSERT INTO ' + QUOTENAME(@TableName) + ' (' + @Columns + ') VALUES ';

    -- Handle Row Values
    DECLARE @i INT = 0;
    DECLARE @rowCount INT = ISNULL(
        (SELECT COUNT(*) FROM OPENJSON(JSON_QUERY(@Json, '$.rows'))),
        0
    );

    WHILE @i < @rowCount
    BEGIN
        DECLARE @CurrentRow NVARCHAR(MAX) = JSON_QUERY(@Json, '$.rows[' + CAST(@i AS NVARCHAR) + ']');

        DECLARE @i2 INT = 0;
        DECLARE @itemCount INT = ISNULL(
            (SELECT COUNT(*) FROM OPENJSON(JSON_QUERY(@CurrentRow), '$')),
            0
        );

        DECLARE @Items NVARCHAR(MAX) = '('

        WHILE @i2 < @itemCount
        BEGIN
            DECLARE @Val NVARCHAR(MAX);
            DECLARE @Type INT;

            SELECT 
                @Val = [value],
                @Type = [type]
            FROM OPENJSON(@CurrentRow)
            WHERE [key] = CAST(@i2 AS NVARCHAR);

            DECLARE @Formatted NVARCHAR(MAX);

            EXEC usp_FormatJsonScalar
                @RawValue = @Val,
                @RawType = @Type,
                @Formatted = @Formatted OUTPUT;

            SET @Items += @Formatted;

            IF @i2 < @itemCount - 1
            BEGIN
                SET @Items += ','
            END
            SET @i2 += 1
        END

        SET @Items += ')'

        IF @i < @rowCount - 1
        BEGIN
            SET @Items += ','
        END

        SET @SQL += @Items

        SET @i += 1;
    END

    -- Debug
    PRINT @SQL;

    -- Execute
    EXEC sp_executesql @SQL;
END
