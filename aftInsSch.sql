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
IF (new.late_allowed) is null THEN
	new.late_allowed = (select late_allowed from sys_duration,pers_person where sys_duration.auth_dept_id=pers_person.auth_dept_id and pers_person.pin = new.nik);
END IF;
IF (new.out_allowed) is null THEN
	new.out_allowed = (select out_allowed from sys_duration,pers_person where sys_duration.auth_dept_id=pers_person.auth_dept_id and pers_person.pin = new.nik);
END IF;
IF (new.out_allowed_friday) is null THEN
	new.out_allowed_friday = (select out_allowed_friday from sys_duration,pers_person where sys_duration.auth_dept_id=pers_person.auth_dept_id and pers_person.pin = new.nik);
END IF;
IF (new.out_allowed_saturday) is null THEN
	new.out_allowed_saturday = (select out_allowed_saturday from sys_duration,pers_person where sys_duration.auth_dept_id=pers_person.auth_dept_id and pers_person.pin = new.nik);
END IF;
IF (new.sub_masuk) is null THEN
	new.sub_masuk = new.masuk - INTERVAL '60 min';
END IF;
IF (new.sub_pulang) is null THEN
	new.sub_pulang = new.pulang + INTERVAL '240 min';
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
