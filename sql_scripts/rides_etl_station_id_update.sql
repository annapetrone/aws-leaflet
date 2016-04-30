update rides_etl
set start_terminal_id = b.start_terminal_id
from 
(select start_station_name ,(regexp_matches(start_station_name , '\d\d\d\d\d'))[1]::int as start_terminal_id
from
	(select start_station_name
	from rides_etl
	group by 1
	) as b1
) as b 
where lower(b.start_station_name) = lower(rides_etl.start_station_name)
;

update rides_etl
set end_terminal_id = b.end_terminal_id
from 
(select end_station_name ,(regexp_matches(end_station_name , '\d\d\d\d\d'))[1]::int as end_terminal_id
from
	(select end_station_name
	from rides_etl
	group by 1
	) as b1
) as b 
where lower(b.end_station_name) = lower(rides_etl.end_station_name)
;


insert into rides
select * from rides_etl
where (start_terminal_id is not null or start_station_name is null)
and (end_terminal_id is not null or end_station_name is null)
;

delete from rides_etl
where (start_terminal_id is not null or start_station_name is null)
and (end_terminal_id is not null or end_station_name is null)
;


alter table rides_etl drop column start_terminal_id
;
alter table rides_etl drop column end_terminal_id
; 

drop table if exists rides_etl_2
;
create table rides_etl_2
as
select a.*, b.terminal_id as start_terminal_id, c.terminal_id as end_terminal_id
from rides_etl as a

left join stations as b 
on lower(a.start_station_name) =  lower(b.station_name)

left join stations as c
on lower(a.end_station_name) =  lower(c.station_name)

;
