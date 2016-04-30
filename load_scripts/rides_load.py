import psycopg2 as pg
import datetime as dt 



# directories
homedir = '/home/ubuntu/'
sqldir = homedir+'sql_scripts/'
datadir = homedir+'data/'

# connect to the database
dbpass = open(homedir+'dbpass.txt','r').read()
host = open(homedir+'host.txt','r').read()
conn = pg.connect('dbname=cabi user=postgres password = '+dbpass+' host='+host)
# code based on: http://initd.org/psycopg/docs/usage.html

# open a cursor to perform database operations
cur = conn.cursor()

cur.execute("set time zone 'America/New_York';") # set the time zone so the time fields will be imported correctly 
# the set command will last for the entire session (ie the cur and conn variables) 



recreate_table= True

if recreate_table:
	# drop the table and recreate - only if you're reloading all files from the beginning. otherwise will just load the new files 
	create_sql = open(sqldir+'rides_create.sql','r').read().split(';')
	[cur.execute(sql) for sql in create_sql if len(sql)>5]
	conn.commit()

	# drop the table and recreate
	create_sql = open(sqldir+'rides_file_record_create.sql','r').read().split(';')
	[cur.execute(sql) for sql in create_sql if len(sql)>5]
	conn.commit()

# create etl table for loading files
create_sql = open(sqldir+'rides_etl_create.sql','r').read().split(';')
[cur.execute(sql) for sql in create_sql if len(sql)>5]
conn.commit()
	
# the names are not always the same in all the files 
# make the dictionary keys the true column names, and provide all the alternate ways of writing them as the values 
alternate_names = [
	['duration_str', ['duration' ]],
	['duration_ms', ['duration (ms)','total duration (ms)']],
	['start_time', ['start date','start time']],
	['end_time', ['end date']],
	['start_terminal_id', ['start station number']],
	['start_station_name', ['start station']],
	['end_terminal_id', ['end station number']],
	['end_station_name', ['end station']],
	['bike_id', ['bike number','bike #','bike#']],
	['member_type', ['member type','type','bike key','subscriber type','subscription type']]
]

start_date = 2010.75 # year, quarter of first available data file
today = dt.datetime.now()

today_q =  (today.month-1)//3 
end_date = today.year + (today_q - 1)/4.00

t = start_date

while t <= end_date:
	Y = int(t//1) # round down 
		
	Q = int( (t - Y)*4 + 1)
	file_name = str(Y)+'-Q'+str(Q)+'-cabi-trip-history-data'
	etl_table = 'rides_etl'
		
	# check if the file has already been loaded
	cur.execute("select count(*) from rides_file_record where file_name='"+file_name+".csv';")
	is_loaded = cur.fetchone()[0]
	
	if is_loaded==0:
		# create etl table for loading files
		create_sql = open(sqldir+'rides_etl_create.sql','r').read().split(';')
		[cur.execute(sql) for sql in create_sql if len(sql)>5]
		conn.commit()

		print('Loading '+file_name)
		#cur.execute('select count(*) from rides') # count number of rows in the file by counting the number of rows in the table before and after loading the file
		#nrows_prev = cur.fetchone()[0]

		# read first line of file to determine the columns it has
		file_path = datadir + file_name + '.csv/'
		f = open(file_path + file_name+'.csv','r')
		header = f.readline().strip('\n').split(',') 
		
		print(header)
		has_columns = [  [a[0] for a in alternate_names if h.lower() in a[1]][0] for h in header ]
		print(has_columns)
		cur.copy_from(f,'rides_etl',columns=has_columns,sep=',')
		conn.commit()
		f.close()
		
		#cur.execute('select count(*) from rides')
		#nrows_now = cur.fetchone()[0]
		n_new_rows = -1 #nrows_now - nrows_prev

		cur.execute("INSERT into rides_file_record (file_name, record_count) VALUES('"+file_name+".csv', "+str(n_new_rows)+")")
		conn.commit() 

		"""
		print('updating hour and time period fields')
		update_sql = open(sqldir+'rides_etl_time_period_update.sql','r').read().split(';')
		[cur.execute(sql) for sql in update_sql if len(sql)>5]
		conn.commit()

		# update duration field when not provided 
		if 'duration_str' in has_columns:
			print('updating duration field')
			update_sql = open(sqldir+'rides_etl_duration_update.sql','r').read().split(';')
			[cur.execute(sql) for sql in update_sql if len(sql)>5]
			conn.commit()
		"""		

		# look up terminal id's of start and end station 
		if 'start_terminal_id' not in has_columns:
			print('updating terminal id fields')
			update_sql = open(sqldir+'rides_etl_station_id_update.sql','r').read().split(';')
			[cur.execute(sql) for sql in update_sql if len(sql)>5]
			conn.commit()
			
			has_columns.append('start_terminal_id')
			has_columns.append('end_terminal_id')

			etl_table = 'rides_etl_2'
			print('terminal id fields updated')


		insert_query = 'insert into rides ('+','.join(has_columns)+') select '+','.join(has_columns)+' from '+etl_table+';'
		#print(insert_query)
		cur.execute(insert_query)
		conn.commit()



	t += 0.25 
	
# close cursor and database connection
cur.close()
conn.close() 
