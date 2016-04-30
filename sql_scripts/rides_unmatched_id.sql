drop table if exists start_id_missing
;

create table start_id_missing
as
select start_station_name,count(*)
from rides
where start_terminal_id is null 
group by 1
;

select sum(count) from 
(
select * , (regexp_matches(start_station_name , '\d\d\d\d\d'))[1]
from start_id_missing 
--where start_station_name = 'Pentagon City Metro / 12th & Hayes St'
) as a
where N = 1

update