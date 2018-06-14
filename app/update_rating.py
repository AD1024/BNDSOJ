import time
import urllib2
def sleeptime(hour,min,sec):
	return hour*3600 + min*60 + sec;
second = sleeptime(0,1,0);
while 1==1:
	time.sleep(second);
	try:
		request=urllib2.Request(url='124.205.120.153/fake-index')
		response = urllib2.urlopen(request)
	except:
		print("error!\n")
	else:
		print("ok!")
	
