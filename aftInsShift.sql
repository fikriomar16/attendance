create or replace function set_shift_user()
returns trigger 
as
$$
begin
IF (new.out_allowed) is null THEN
	IF (new.work_start) > (new.work_end) THEN
		new.out_allowed = (SELECT AGE((SELECT CONCAT('2022-12-12',' ',(SELECT AGE((SELECT CONCAT('2022-12-13',' ',new.work_end)::timestamp),(SELECT CONCAT('2022-12-12',' ',new.work_start)::timestamp))))::timestamp),(SELECT CONCAT('2022-12-12',' ',new.work_time)::timestamp))+INTERVAL '20 min');
	ELSE
		new.out_allowed = (SELECT AGE((SELECT CONCAT('2022-12-12',' ',(SELECT AGE((SELECT CONCAT('2022-12-12',' ',new.work_end)::timestamp),(SELECT CONCAT('2022-12-12',' ',new.work_start)::timestamp))))::timestamp),(SELECT CONCAT('2022-12-12',' ',new.work_time)::timestamp))+INTERVAL '20 min');
	END IF;
END IF;
return new;
end;
$$
LANGUAGE 'plpgsql';

CREATE OR REPLACE TRIGGER trg_set_shift_user
BEFORE INSERT
ON sys_shift
FOR EACH ROW
EXECUTE PROCEDURE set_shift_user();

/*
DROP TRIGGER trg_set_shift_user on sys_shift;
DROP FUNCTION set_shift_user();
*/
