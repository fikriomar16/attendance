create or replace function set_sch_user()
returns trigger 
as
$$
begin
IF (new.nama) is null THEN
	new.nama = (select name from pers_person where pin=new.nik order by pin asc limit 1);
END IF;
IF (new.tanggal) is null THEN
	new.tanggal = (select CAST(new.masuk as date));
END IF;
IF (new.shift_code) is null THEN
	new.shift_code = new.shift;
END IF;
IF (new.sub_masuk) is null THEN
	new.sub_masuk = new.masuk - INTERVAL '60 min';
END IF;
IF (new.sub_pulang) is null THEN
	new.sub_pulang = new.pulang + INTERVAL '240 min';
END IF;
IF (new.out_allowed) is null THEN
	new.out_allowed = (SELECT AGE(
			(SELECT CONCAT('2012-12-12',' ',(SELECT AGE(new.pulang,new.masuk)))::timestamp),
			(SELECT CONCAT('2012-12-12',' ',new.work_time)::timestamp)
		));
END IF;
return new;
end;
$$
LANGUAGE 'plpgsql';

CREATE OR REPLACE TRIGGER trg_set_sch_user
BEFORE INSERT
ON sys_sch_users
FOR EACH ROW
EXECUTE PROCEDURE set_sch_user();

/*
DROP TRIGGER trg_set_sch_user on sys_sch_users;
DROP FUNCTION set_sch_user();
*/
