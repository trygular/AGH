

UPDATE nmsusers.users SET password=password('AGH') where username='ABHI' AND active=1;

UPDATE nmsusers.users SET username='ABHI' where username='' AND active=1;
UPDATE nmsusers.UserGroupLink SET username='ABHI' where username='';

Select * from nmsusers.users where active=1 AND username='ABHI';
SELECT * from nmsusers.UserGroupLink where username='ABHI';