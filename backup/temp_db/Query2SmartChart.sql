-- TOTAL QUESTION  27
select * from RetentionQuestion where questiontype!='9' && questiontype!='10';


select * from RetentionQuestionaire2 GROUP BY question;
select * from RetentionQuestionaire2;

select * from RetentionQuestionaire1;

SELECT 
    question,
    (CASE
        WHEN remark = '1' THEN remark
    END) AS 'first',
    (CASE
        WHEN remark = '2' THEN remark
    END) AS 'second',
    (CASE
        WHEN remark = '3' THEN remark
    END) AS 'third',
    R1.empcode
FROM
    RetentionQuestionaire2 R2,
    RetentionQuestionaire1 R1
WHERE
    R1.empcode = R2.empcode
        AND R1.wefdate >= '2017-03-16'
        AND R1.wefdate <= '2018-03-16'
        AND R2.questiontype != 9
        AND R2.questiontype != 10
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
                AND B.branchcode = '168')