update rides_etl
set start_hour =  extract(hour from start_time)
where start_hour is null 
;


 
update rides_etl
set end_hour =  extract(hour from end_time)
where end_hour is null
;




update rides_etl
set start_time_period = 
case 
when start_hour >= 0 and start_hour <= 5 then 'earlymorning'
when start_hour >= 6 and start_hour <= 10 then 'morning'
when start_hour >= 11 and start_hour <= 3+12 then 'afternoon'
when start_hour >= 4+12 and start_hour <= 8+12 then 'evening'
when start_hour >= 9+12 and start_hour <= 9+12 then 'night'
end
where start_time_period is null
;


update rides_etl
set end_time_period =
case 
when end_hour >= 0 and end_hour <= 5 then 'earlymorning'
when end_hour >= 6 and end_hour <= 10 then 'morning'
when end_hour >= 11 and end_hour <= 3+12 then 'afternoon'
when end_hour >= 4+12 and end_hour <= 8+12 then 'evening'
when end_hour >= 9+12 and end_hour <= 9+12 then 'night'
end
where end_time_period is null
;

