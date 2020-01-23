import re,os,shutil,json,time,subprocess
		
for root, dirs, files in os.walk("search_list/"):
	for filex in files:
		file = root+"/"+filex
		if os.path.isfile(str(file)):
			sleep = subprocess.Popen(['node', 'try.js',str(file),'--max-old-space-size=4096'])
			sleep.wait()
			shutil.move(file, "uploaded/"+str(filex))
			print ("uploaded",filex)