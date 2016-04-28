# use this to determine the data cleaning you'll need to do. the columns do not always have the same name, and the data may be formatted differently 
# it creates a file for you to look at and determine what needs to be done 

import datetime as dt 
import os.path 

# directories
homedir = '/home/ubuntu/'
sqldir = homedir+'sql_scripts/'
datadir = homedir+'data/'


start_date = 2010.75 # year, quarter of first available data file
today = dt.datetime.now()

today_q =  (today.month-1)//3 
end_date = today.year + (today_q - 1)/4.00

t = start_date

header_file = open(datadir + 'headers.csv','w')
# download all data files and copy to database. if the file has already been loaded it will be skipped 
while t <= end_date:
		Y = int(t//1) # round down 
		
		Q = int( (t - Y)*4 + 1)
		file_name = str(Y)+'-Q'+str(Q)+'-cabi-trip-history-data'
		
		file_path = datadir+file_name+'.csv/'
		if os.path.isdir(file_path): 
		
			f= open(file_path+file_name+'.csv','r')
			header = f.readline()
			first_row = f.readline()
			header_file.write(file_name+','+header)
			header_file.write(file_name+','+first_row)
				
				
		t += 0.25

header_file.close()
