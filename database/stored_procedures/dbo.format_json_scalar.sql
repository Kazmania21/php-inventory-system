CREATE OR ALTER PROCEDURE usp_FormatJsonScalar
(
    @RawValue NVARCHAR(MAX),      -- original JSON value (string form)
    @RawType INT,                 -- OPENJSON type code
    @Formatted NVARCHAR(MAX) OUTPUT
)
AS
BEGIN
    SET NOCOUNT ON;

    -- NULL
    IF @RawType = 0
    BEGIN
        SET @Formatted = 'NULL';
        RETURN;
    END

    -- STRING
    IF @RawType = 1
    BEGIN
        SET @Formatted = '''' + REPLACE(@RawValue, '''', '''''') + '''';
        RETURN;
    END

    -- NUMBER
    IF @RawType = 2
    BEGIN
        SET @Formatted = @RawValue;
        RETURN;
    END

    -- BOOLEAN
    IF @RawType = 3 
    BEGIN
        SET @Formatted = CASE WHEN LOWER(@RawValue) = 'true' THEN '1' ELSE '0' END;
        RETURN;
    END

    -- ARRAY or OBJECT
    -- treat as string literal containing JSON
    SET @Formatted = '''' + REPLACE(@RawValue, '''', '''''') + '''';
END
