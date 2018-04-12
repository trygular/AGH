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
        AND R2.empcode = '1539';