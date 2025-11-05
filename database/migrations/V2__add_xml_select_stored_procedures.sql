SET QUOTED_IDENTIFIER ON;
GO

CREATE OR ALTER PROCEDURE usp_SelectDynamicXml
    @TableName NVARCHAR(128),
    @Xml XML
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @SQL NVARCHAR(MAX);

    -- Get main table alias from XML, or default to 't'
    DECLARE @MainAlias NVARCHAR(50) = @Xml.value('(/Xml/@mainAlias)[1]', 'NVARCHAR(50)');
    IF @MainAlias IS NULL OR @MainAlias = ''
        SET @MainAlias = 't';

    -- Get columns
    DECLARE @Columns NVARCHAR(MAX) = @Xml.value('(/Xml/@columns)[1]', 'NVARCHAR(MAX)');
    IF @Columns IS NULL OR @Columns = ''
        SET @Columns = @MainAlias + '.*';

    -- Start SQL
    SET @SQL = N'SELECT ' + @Columns + ' FROM ' + QUOTENAME(@TableName) + ' ' + @MainAlias + ' ';

    -- Handle joins
    DECLARE @i INT = 0;
    DECLARE @joinCount INT = ISNULL((
        SELECT COUNT(*)
        FROM @Xml.nodes('/Xml/Joins/Join') AS X(j)
    ), 0);

    WHILE @i < @joinCount
    BEGIN
        DECLARE @JoinTable NVARCHAR(128);
        DECLARE @JoinAlias NVARCHAR(50);
        DECLARE @JoinType NVARCHAR(20);
        DECLARE @JoinOn NVARCHAR(200);
        SELECT 
            @JoinTable = j.value('@table', 'NVARCHAR(128)'),
            @JoinAlias = j.value('@alias', 'NVARCHAR(50)'),
            @JoinType = j.value('@type', 'NVARCHAR(20)'),
            @JoinOn = j.value('@on', 'NVARCHAR(200)')
        FROM @Xml.nodes('/Xml/Joins/Join[position() = sql:variable("@i") + 1]') AS X(j);

        -- Defaults
        IF @JoinType IS NULL OR @JoinType = '' SET @JoinType = 'INNER';
        IF @JoinAlias IS NULL OR @JoinAlias = '' SET @JoinAlias = @JoinTable;

        -- Replace main table alias in ON clause if XML uses a different one
        IF CHARINDEX('i.', @JoinOn) > 0
            SET @JoinOn = REPLACE(@JoinOn, 'i.', @MainAlias + '.');

        SET @SQL += ' ' + @JoinType + ' JOIN ' + QUOTENAME(@JoinTable) + ' ' + @JoinAlias +
                    ' ON ' + @JoinOn;

        SET @i += 1;
    END
    
    DECLARE @WhereXml XML = @Xml.query('/Xml/Where');
    DECLARE @WhereClause NVARCHAR(MAX);
    
    -- Add Where Clause
    EXEC usp_DynamicWhereXml
        @WhereXml = @WhereXml,
        @WhereClause = @WhereClause OUTPUT;

    SET @SQL += ' ' + @WhereClause;

    -- Add GROUP BY
    DECLARE @GroupBy NVARCHAR(MAX) = @Xml.value('(/Xml/@groupBy)[1]', 'NVARCHAR(MAX)');
    IF @GroupBy IS NOT NULL AND @GroupBy <> ''
        SET @SQL += ' GROUP BY ' + REPLACE(@GroupBy, 'i.', @MainAlias + '.');

    -- Add ORDER BY
    DECLARE @OrderBy NVARCHAR(MAX) = @Xml.value('(/Xml/@orderBy)[1]', 'NVARCHAR(MAX)');
    IF @OrderBy IS NOT NULL AND @OrderBy <> ''
        SET @SQL += ' ORDER BY ' + REPLACE(@OrderBy, 'i.', @MainAlias + '.');

    SET @SQL += ' FOR XML AUTO, ROOT(''' + @tableName + ''')';

    -- Debug
    PRINT @SQL;

    -- Execute
    EXEC sp_executesql @SQL;
END
GO

CREATE OR ALTER PROCEDURE usp_DynamicWhereXml
    @WhereXml XML,
    @WhereClause NVARCHAR(MAX) OUTPUT
AS
BEGIN
    DECLARE @WhereSQL NVARCHAR(MAX) = 'WHERE 1=1';

    SELECT @WhereSQL = @WhereSQL +
        ' AND ' + j.value('@column', 'NVARCHAR(128)') + ' ' + j.value('@op', 'NVARCHAR(10)') + ' ' +
        CASE
            WHEN ISNUMERIC(j.value('@value', 'NVARCHAR(MAX)')) = 1 THEN j.value('@value', 'NVARCHAR(MAX)')
            ELSE '''' + REPLACE(j.value('@value', 'NVARCHAR(MAX)'), '''', '''''') + ''''
        END
    FROM @WhereXml.nodes('/Xml/Where/Condition') as X(j);

    SET @WhereClause = @WhereSQL;
END
GO
