CREATE OR ALTER PROCEDURE usp_Delete
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

    -- Start SQL
    SET @SQL = N'DELETE ' + @MainAlias + ' FROM ' + QUOTENAME(@TableName) + ' ' + @MainAlias;

    DECLARE @WhereJson NVARCHAR(MAX) = JSON_QUERY(@Json, '$.where');
    DECLARE @WhereClause NVARCHAR(MAX);
    
    -- Add Where Clause
    EXEC usp_DynamicWhere
        @WhereJson = @WhereJson,
        @WhereClause = @WhereClause OUTPUT;

    SET @SQL += ' ' + @WhereClause;

    -- Add ORDER BY
    DECLARE @OrderBy NVARCHAR(MAX) = JSON_VALUE(@Json, '$.orderBy');
    IF @OrderBy IS NOT NULL AND @OrderBy <> ''
        SET @SQL += ' ORDER BY ' + REPLACE(@OrderBy, 'i.', @MainAlias + '.');

    -- Debug
    PRINT @SQL;

    -- Execute
    EXEC sp_executesql @SQL;
END
