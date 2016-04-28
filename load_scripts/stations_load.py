import psycopg2 as pg # python library for postgres

# directory where sql scripts are stored
homedir = '/home/ubuntu/'
sqldir = homedir + 'sql_scripts/'
datadir = homedir + 'data/'

# connect to the database
dbpass = open(homedir+"dbpass.txt").read()
host = open(homedir+"host.txt").read()
conn = pg.connect("dbname=cabi user=postgres password = "+dbpass+" host="+host)
# code based on: http://initd.org/psycopg/docs/usage.html

# open a cursor to perform database operations
cur = conn.cursor()
 
cur.execute("set time zone 'America/New_York'")
 
# drop the table and recreate
create_sql = open(sqldir+'stations_create.sql','r').read().split(';')
[cur.execute(sql) for sql in create_sql if len(sql)>5]
conn.commit()


f = open(datadir + 'bikeStations.csv', 'r')
header = f.readline()
cur.copy_from(f,"stations",sep=",",columns=('station_id','station_name','terminal_id','lon','lat'))
conn.commit()

print("Loaded")
	

update_sql = open(sqldir+'stations_update.sql','r').read().split(';')
[cur.execute(sql) for sql in update_sql if len(sql)>5]
conn.commit()
 

#### finish
# close cursor and connection
cur.close()
conn.close()
