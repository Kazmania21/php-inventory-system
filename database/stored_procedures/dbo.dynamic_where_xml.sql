SET QUOTED_IDENTIFIER ON;
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
