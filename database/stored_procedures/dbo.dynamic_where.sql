CREATE OR ALTER PROCEDURE usp_DynamicWhere
    @WhereJson NVARCHAR(MAX),
    @WhereClause NVARCHAR(MAX) OUTPUT
AS
BEGIN
    DECLARE @WhereSQL NVARCHAR(MAX) = 'WHERE 1=1';

    SELECT @WhereSQL = @WhereSQL +
        ' AND ' + j.[column] + ' ' + j.[op] + ' ' +
        CASE
            WHEN ISNUMERIC(j.[value]) = 1 THEN j.[value]
            ELSE '''' + REPLACE(j.[value], '''', '''''') + ''''
        END
    FROM OPENJSON(@WhereJson)
    WITH (
        [column] NVARCHAR(128) '$.column',
        [op] NVARCHAR(10) '$.op',
        [value] NVARCHAR(MAX) '$.value'
    ) j;

    SET @WhereClause = @WhereSQL;
END
