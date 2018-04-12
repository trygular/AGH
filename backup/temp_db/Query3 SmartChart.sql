SELECT  * FROM RetentionQuestionaire2
WHERE
	empcode = 1539 AND questiontype != 9 AND questiontype != 10;

select * from RetentionQuestion where questiontype='1';

-- VERIFY DATA
SELECT * FROM RetentionQuestionaire2 
WHERE questiontype = '1' AND question = '1' -- AND remark = 3
AND empcode IN (SELECT 
            empcode
        FROM
            `Lokmanya-Empire`.StaffMaster S,
            `Lokmanya-Empire`.BranchMaster B,
            `Lokmanya-Empire`.DesignationMaster D
        WHERE
            B.branchcode = S.Branchid
                AND S.Designationid = D.designationid
                AND B.regioncode = '13'
                AND B.branchcode = '168');


-- QUERY IS WRONG OF grapH report 3.PHP
SELECT R2.question,
    (CASE
        WHEN remark = '1' THEN remark
    END) AS 'first',
    (CASE
        WHEN remark = '2' THEN remark
    END) AS 'second',
    (CASE
        WHEN remark = '3' THEN remark
    END) AS 'third',
    R2.empcode
FROM
    RetentionQuestionaire2 R2,
    RetentionQuestionaire1 R1
WHERE
    R1.empcode = R2.empcode
        AND R1.wefdate >= '2017-03-16'
        AND R1.wefdate <= '2018-03-16'
        AND R2.questiontype = '1'
        AND R2.question = '1'
        -- AND R2.remark = '2'
        -- AND R2.empcode = '1539'
        AND R2.empcode IN (SELECT 
            empcode
        FROM
            `Lokmanya-Empire`.StaffMaster S,
            `Lokmanya-Empire`.BranchMaster B,
            `Lokmanya-Empire`.DesignationMaster D
        WHERE
            B.branchcode = S.Branchid
                AND S.Designationid = D.designationid
                AND B.regioncode = '13'
                AND B.branchcode = '168');



-- CORRECT QUERY 
SELECT * FROM RetentionQuestionaire2 
WHERE questiontype = '1' AND question = '1' 
AND empcode IN (SELECT 
            empcode
        FROM
            `Lokmanya-Empire`.StaffMaster S,
            `Lokmanya-Empire`.BranchMaster B,
            `Lokmanya-Empire`.DesignationMaster D
        WHERE
            B.branchcode = S.Branchid
                AND S.Designationid = D.designationid
                AND B.regioncode = '13'
                AND B.branchcode = '168');

SELECT * FROM RetentionQuestionaire2;
WHERE id = '23' and id = '35';


-- 2

-- CORRECT QUERY 
SELECT R2.question,
    (CASE WHEN remark = '1' THEN remark END) AS 'first',
    (CASE WHEN remark = '2' THEN remark END) AS 'second',
    (CASE WHEN remark = '3' THEN remark END) AS 'third',
    R2.empcode
FROM
	RetentionQuestionaire2 R2,
    RetentionQuestionaire1 R1
WHERE
	R1.empcode = R2.empcode
	AND R1.wefdate >= '2017-03-16'
	AND R1.wefdate <= '2018-03-16'
    AND questiontype = '1'
    AND question = '1'
AND R2.empcode IN (SELECT 
            empcode
        FROM
            `Lokmanya-Empire`.StaffMaster S,
            `Lokmanya-Empire`.BranchMaster B,
            `Lokmanya-Empire`.DesignationMaster D
        WHERE
            B.branchcode = S.Branchid
                AND S.Designationid = D.designationid
                AND B.regioncode = '13'
                AND B.branchcode = '168');



-- CORRECT QUERY

SELECT R2.question,
    (CASE WHEN remark = '1' THEN remark END) AS 'first',
    (CASE WHEN remark = '2' THEN remark END) AS 'second',
    (CASE WHEN remark = '3' THEN remark END) AS 'third',
    R2.empcode
FROM
	RetentionQuestionaire2 R2,
    RetentionQuestionaire1 R1
WHERE
	R1.empcode = R2.empcode
	AND R1.wefdate >= '2017-03-16'
	AND R1.wefdate <= '2018-03-16'
    AND R2.questiontype = '1'
    AND R2.question = '1'
AND R2.empcode IN (SELECT 
            empcode
        FROM
            `Lokmanya-Empire`.StaffMaster S,
            `Lokmanya-Empire`.BranchMaster B,
            `Lokmanya-Empire`.DesignationMaster D
        WHERE
            B.branchcode = S.Branchid
                AND S.Designationid = D.designationid
                AND B.regioncode = '13'
                AND B.branchcode = '168');
