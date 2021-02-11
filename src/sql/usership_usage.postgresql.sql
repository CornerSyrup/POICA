CREATE OR REPLACE FUNCTION Usership.obtain_pwd(sid CHAR(5))
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

CREATE OR REPLACE FUNCTION Usership.obtain_suica(idm CHAR(16))
    RETURNS CHAR(5)
AS $$
    SELECT
        u.studentid
    FROM
        Usership.Users u
    WHERE
        u.suica = idm
    LIMIT
        1;
$$ LANGUAGE SQL;

CREATE OR REPLACE PROCEDURE Usership.insert_cre(sid CHAR(5), syear CHAR(2), phash CHAR(60), jfn VARCHAR(12), jln VARCHAR(36), jfk VARCHAR(45), jlk VARCHAR(45))
AS $$
    INSERT INTO Usership.Users
        (studentID, studentYear, pwd, jaFName, jaLName, jaFKana, jaLKana)
    VALUES
        (sid, syear, phash, jfn, jln, jfk, jlk);
$$ LANGUAGE SQL;