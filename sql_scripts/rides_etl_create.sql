drop table if exists rides_etl
;

create table rides_etl
as
select * 
from rides 
limit 0
;



create index idx_rides_etl_start_name on rides_etl(lower(start_station_name))
;
create index idx_rides_etl_end_name on rides_etl(lower(end_station_name))
;

create index idx_rides_etl_start_hour on rides_etl(start_hour)
;
create index idx_etl_rides_end_hour on rides_etl(end_hour)
;  