CREATE OR ALTER PROCEDURE usp_SelectDynamicJson
    @TableName NVARCHAR(128),
    @Json NVARCHAR(MAX)
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @SQL NVARCHAR(MAX);

    -- Get main table alias from JSON, or default to 't'
    DECLARE @MainAlias NVARCHAR(50) = JSON_VALUE(@Json, '$.mainAlias');
    IF @MainAlias IS NULL OR @MainAlias = ''
        SET @MainAlias = 't';

    -- Get columns
    DECLARE @Columns NVARCHAR(MAX) = JSON_VALUE(@Json, '$.columns');
    IF @Columns IS NULL OR @Columns = ''
        SET @Columns = @MainAlias + '.*';

    -- Start SQL
    SET @SQL = N'SELECT ' + @Columns + ' FROM ' + QUOTENAME(@TableName) + ' ' + @MainAlias + ' ';

    -- Handle joins
    DECLARE @i INT = 0;
    DECLARE @joinCount INT = ISNULL(
        (SELECT COUNT(*) FROM OPENJSON(JSON_QUERY(@Json, '$.joins'))),
        0
    );

    WHILE @i < @joinCount
    BEGIN
        DECLARE @JoinTable NVARCHAR(128) = JSON_VALUE(@Json, '$.joins[' + CAST(@i AS NVARCHAR) + '].table');
        DECLARE @JoinAlias NVARCHAR(50) = JSON_VALUE(@Json, '$.joins[' + CAST(@i AS NVARCHAR) + '].alias');
        DECLARE @JoinType NVARCHAR(10) = JSON_VALUE(@Json, '$.joins[' + CAST(@i AS NVARCHAR) + '].type');
        DECLARE @JoinOn NVARCHAR(MAX) = JSON_VALUE(@Json, '$.joins[' + CAST(@i AS NVARCHAR) + '].on');

        -- Defaults
        IF @JoinType IS NULL OR @JoinType = '' SET @JoinType = 'INNER';
        IF @JoinAlias IS NULL OR @JoinAlias = '' SET @JoinAlias = @JoinTable;

        -- Replace main table alias in ON clause if JSON uses a different one
        IF CHARINDEX('i.', @JoinOn) > 0
            SET @JoinOn = REPLACE(@JoinOn, 'i.', @MainAlias + '.');

        SET @SQL += ' ' + @JoinType + ' JOIN ' + QUOTENAME(@JoinTable) + ' ' + @JoinAlias +
                    ' ON ' + @JoinOn;

        SET @i += 1;
    END
    
    DECLARE @WhereJson NVARCHAR(MAX) = JSON_QUERY(@Json, '$.where');
    DECLARE @WhereClause NVARCHAR(MAX);
    
    -- Add Where Clause
    EXEC usp_DynamicWhere
        @WhereJson = @WhereJson,
        @WhereClause = @WhereClause OUTPUT;

    SET @SQL += ' ' + @WhereClause;

    -- Add GROUP BY
    DECLARE @GroupBy NVARCHAR(MAX) = JSON_VALUE(@Json, '$.groupBy');
    IF @GroupBy IS NOT NULL AND @GroupBy <> ''
        SET @SQL += ' GROUP BY ' + REPLACE(@GroupBy, 'i.', @MainAlias + '.');

    -- Add ORDER BY
    DECLARE @OrderBy NVARCHAR(MAX) = JSON_VALUE(@Json, '$.orderBy');
    IF @OrderBy IS NOT NULL AND @OrderBy <> ''
        SET @SQL += ' ORDER BY ' + REPLACE(@OrderBy, 'i.', @MainAlias + '.');

    -- Debug
    PRINT @SQL;

    -- Execute
    EXEC sp_executesql @SQL;
END
