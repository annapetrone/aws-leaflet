
update rides_etl
set duration_ms = 
1000* (
  extract(epoch from end_time) - extract(epoch from start_time) -- returns seconds 
 )::bigint
where duration_ms is null
;