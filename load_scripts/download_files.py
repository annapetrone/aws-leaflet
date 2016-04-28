import datetime as dt 
import urllib.request
import os
import zipfile 

# directories
homedir = '/home/ubuntu/'
sqldir = homedir+'sql_scripts/'
datadir = homedir+'data/'


# remove previously downloaded data files 
files = os.listdir(datadir)
for file in files:
	os.remove(datadir+file)

# location of data files 
all_data_url = 'https://s3.amazonaws.com/capitalbikeshare-data/'

start_date = 2010.75 # year, quarter of first available data file. Example 2010.0, 2010.25, 2010.5, 2010.75 are the 4 quarters of 2010 
today = dt.datetime.now()

today_q =  (today.month-1)//3 
end_date = today.year + (today_q - 1)/4.00

t = start_date

# download all data files and store to the data/ directory. if the file has already been downloaded it will be skipped 
while t <= end_date:
		Y = int(t//1) # round down 
		
		Q = int( (t - Y)*4 + 1)
		file_name = str(Y)+'-Q'+str(Q)+'-cabi-trip-history-data'
		
		if not os.path.isdir(datadir+file_name+'.csv'):  # below extracts to a folder with the same name as the file (eg the folder is called file_name.csv/ )
			print('Downloading '+file_name)
			try:
				f= open(datadir+file_name,'w')
				file_url = all_data_url + file_name+'.zip'
				
				urllib.request.urlretrieve(file_url, datadir +file_name+'.zip')		
				
				zip_folder = zipfile.ZipFile(datadir+file_name+'.zip')
				
				csv_name = zip_folder.namelist() # the name of the csv inside the zip file might not match the name of the zip file, so get the csv file's name 
				
				if(len(csv_name)>1):
					print("Warning! multiple files found in "+file_name+'.zip: '+', '.join(namelist)+' Will just use the first')
				
				csv_name = csv_name[0]
				
				zip_folder.extract(csv_name,datadir+file_name+'.csv') # extracts to a folder with the same name as the file (?)
				file_path = datadir+file_name+'.csv/'
				os.rename(file_path+csv_name,file_path+file_name+'.csv') # in the event that the csv inside has a different name than the folder, rename it 
				
			except Exception as error_msg:
				#print("warning "+fileName+" not found on website. Skipping..")
				print(error_msg)
		t += 0.25

		