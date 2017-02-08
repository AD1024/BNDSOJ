import re
import urllib2
import time
import socket
import os
import sys
import base64
import cookielib

from urllib import urlencode

from modules.config import *


class hdu(object):

    def login(self):
        oj_config = oj_configs().getojinfo("hdu")
        
        try:
            url = "http://acm.hdu.edu.cn/userloginex.php?action=login&cid=0&notice=0"
            data = "username=" + oj_config['username'] + "&userpass=" + oj_config['password'] + "&login=Sign+In"
            
            cj = cookielib.CookieJar()  
            opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(cj))
            urllib2.install_opener(opener)
            
            request = urllib2.Request(url)
            request.add_header('Host', 'acm.hdu.edu.cn')
            request.add_header('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0')
            
            response = urllib2.urlopen(request, data=data)

            cookie = opener

        except:
            return False

        if cj:
            return opener
        else:
            return False

    def submit(self, opener, check_config):
        try:
            url = 'http://acm.hdu.edu.cn/submit.php?action=submit'

            urllib2.install_opener(opener)
            
            request = urllib2.Request(url)

            if check_config['language'] == 'C++':
                check_config['language'] = '0'
            elif check_config['language'] == 'C':
                check_config['language'] = '1'
            elif check_config['language'] == 'Java':
                check_config['language'] = '5'
            elif check_config['language'] == 'Pascal':
                check_config['language'] = '4'
            
            data = 'check=0&problemid=' + check_config['problem_id'] + '&language=' + check_config['language'] + '&' + check_config['code']

            #request.add_header('Cookie', cookie)
            request.add_header('Host', 'acm.hdu.edu.cn')
            request.add_header('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0')
        
            response = urllib2.urlopen(request, data=data)
        
            content = response.read()

            #oj_config = oj_configs().getojinfo("hdu")
        
            #pattern = re.compile(r'.*?<td height=22px>(?P<id>[^<>].*?)</td>.*?<a.*?>.*?<td>.*?</td>.*?<td>.*?</td>.*?<td>(?P<size>[^<>].*?)</td><td>.*?</td><td class=fixedsize><a.*?>' + oj_config['username'] + '</a></td>.*?',re.I|re.S)
            #match = pattern.match(content)
            #result = {}
            #if match:
                #result['id'] = match.group('id')
                #result['size'] = match.group('size')
                #result['size'] = result['size'].strip()
            #else:
                #return False
            result = True
            
        except:
            return False

        return result


    def status(self, opener, status_config):
        try:
            oj_config = oj_configs().getojinfo("hdu")
            url = 'http://acm.hdu.edu.cn/status.php?first=&pid=&user=' + oj_config['username'] +'&lang=0&status=0'

            urllib2.install_opener(opener)
            
            request = urllib2.Request(url)
            
            #request.add_header('Cookie', cookie)
            request.add_header('Host', 'acm.hdu.edu.cn')
            request.add_header('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0')
        
            response = urllib2.urlopen(request)
        
            content = response.read()
        
            pattern = re.compile(r'.*?<td height=22px>(?P<id>[^<>].*?)</td>.*?<td>.*?</td>.*?<td><font.*?>(?P<result>[^<>].*?)</font></td>' +
                                 '.*?<td><a.*?>.*?</a></td>.*?<td>(?P<time>[^<>].*?)</td>.*?<td>(?P<memory>[^<>].*?)</td>' +
                                 '.*?<td><a.*?>(?P<size>[^<>].*?)</td>.*?',re.I|re.S)
            match = pattern.match(content)
            result = status_config

            if match:
                result['id'] = match.group('id')
                result['id'] = result['id'].strip()
                
                result['memory'] = match.group('memory')
                result['memory'] = result['memory'].strip()
                
                result['time'] = match.group('time')
                result['time'] = result['time'].strip()

                result['size'] = match.group('size')
                result['size'] = result['size'].strip()

                result['result'] = match.group('result')
                result['result'] = result['result'].lstrip()
                result['result'] = result['result'].rstrip()

            else:
                result['result'] = "waiting"

        except:
            return False

        return result

    def checkce(self, opener, status_config):
        try:
            oj_config = oj_configs().getojinfo("hdu")
            url = 'http://acm.hdu.edu.cn/viewcode.php?rid=' + status_config['id']

            urllib2.install_opener(opener)
            
            request = urllib2.Request(url)
            
            #request.add_header('Cookie', cookie)
            request.add_header('Host', 'acm.hdu.edu.cn')
            request.add_header('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0')
        
            response = urllib2.urlopen(request)
        
            content = response.read()

            result = status_config

            if "Compilation Error" in content:
                result['memory'] = "/"
                result['time'] = "/"
                result['result'] = "Compilation Error"
        except:
            return False

        return result
    

    def main(self, check_config):
        for i in range(1,3):
            opener = self.login()
            if opener:
                break
        
        if not opener:
            return {"success":False}
        
        check_config['code'] = urlencode({'usercode':check_config['code']})

        for i in range(1,3):
            result = self.submit(opener, check_config)
            if result:
                break
        
        if not result:
            return {"success":False}

        time.sleep(1)
        result = self.status(opener, {})
        
        if not result:
            return {"success":False}

        cnt = 0

        while("ing" in result['result']):
            result = self.status(opener, result)
            cnt = cnt + 1
            if cnt > 60:
                return {"success":False}
                break
            time.sleep(1)

        for i in range(1,3):
            cce = self.checkce(opener, result)
            if cce:
                break

        if cce['result'] == "Compilation Error":
            result = cce
        
        result['success'] = True
        return result

