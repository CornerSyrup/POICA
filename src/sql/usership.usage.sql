CREATE OR REPLACE FUNCTION Usership.obtain_pwd(sid char(5))
    RETURNS char(5)
AS $$
    SELECT
        u.pwd
    FROM
        Usership.Users u
    WHERE
        u.studentID = sid
    LIMIT 1;
$$ LANGUAGE SQL;