import re
import urllib2
import time
import socket
import os
import sys
import base64

from urllib import urlencode

from modules.config import *


class poj(object):

    def login(self):
        oj_config = oj_configs().getojinfo("poj")
        try:
            url = "http://poj.org/login"
            data = "user_id1=" + oj_config['username'] + "&password1=" +  oj_config['password']
            request = urllib2.Request(url)
            response = urllib2.urlopen(request, data=data, timeout=10)
            cookie =  response.info().getheader("Set-Cookie")
            cookie = cookie[0:45]
        except:
            return False

        if cookie:
            return cookie
        else:
            return False

    def submit(self, cookie, check_config):
        try:
            url = 'http://poj.org/submit'
            request = urllib2.Request(url)

            if check_config['language'] == 'C++':
                check_config['language'] = '0'
            elif check_config['language'] == 'C':
                check_config['language'] = '1'
            elif check_config['language'] == 'Java':
                check_config['language'] = '2'
            elif check_config['language'] == 'Pascal':
                check_config['language'] = '3'
            
            data = 'problem_id=' + check_config['problem_id'] + '&language=' + check_config['language'] + '&' + check_config['code'] + '&submit=Submit&encoded=1'
        
            request.add_header('Cookie', cookie)
        
            response = urllib2.urlopen(request, data=data, timeout=10)
        
            content = response.read()

            oj_config = oj_configs().getojinfo("poj")
        
            pattern = re.compile(r'.*?<tr align=center><td>(?P<id>[^<>].*?)</td><td><a.*?>' + oj_config['username'] + '</a></td>.*?',re.I|re.S)
            match = pattern.match(content)
            result = {}
            if match:
                result['id'] = match.group('id')
                pattern = re.compile(r'.*?<a.*?' + result['id'] + '.*?>.*?</a></td><td>(?P<size>[^<>].*?)</td><td>.*?',re.I|re.S)
                match = pattern.match(content)
                result['size'] = match.group('size')
                result['size'] = result['size'].strip()
            else:
                return False
            
        except:
            return False

        return result


    def status(self, cookie, status_config):
        try:
            url = 'http://poj.org/showsource?solution_id=' + status_config['id']
            request = urllib2.Request(url)
            
            request.add_header('Cookie', cookie)
        
            response = urllib2.urlopen(request, timeout=10)
        
            content = response.read()
        
            pattern = re.compile(r'.*?<td><b>Memory:</b>(?P<memory>[^<>].*?)</td><td width=10px>.*?',re.I|re.S)
            match = pattern.match(content)
            result = status_config

            if match:
                result['memory'] = match.group('memory')
                result['memory'] = result['memory'].strip()
                
                pattern = re.compile(r'.*?<td><b>Time:</b>(?P<time>[^<>].*?)</td>.*?',re.I|re.S)
                match = pattern.match(content)
                result['time'] = match.group('time')
                result['time'] = result['time'].strip()

                pattern = re.compile(r'.*?<b>Result:.*?<font.*?>(?P<result>[^<>].*?)</font>.*?',re.I|re.S)
                match = pattern.match(content)
                result['result'] = match.group('result')
                result['result'] = result['result'].lstrip()
                result['result'] = result['result'].rstrip()
            
        except:
            return False

        return result

    

    def main(self, check_config):
        for i in range(1,3):
            cookie = self.login()
            if cookie:
                break

        if not cookie:
            return {"success":False}

        check_config['code'] = base64.b64encode(check_config['code'])
        check_config['code'] = urlencode({'source':check_config['code']})

        for i in range(1,3):
            result = self.submit(cookie, check_config)
            if result:
                break

        if not result:
            return {"success":False}

        for i in range(1,3):
            result = self.status(cookie, result)
            if result:
                break

        cnt = 0

        if not result:
            return {"success":False}

        while((not "Error" in result['result']) and (not "Exceeded" in result['result']) and (not "Answer" in result['result']) and (not "Accepted" in result['result'])):
            result = self.status(cookie, result)
            cnt = cnt + 1
            if cnt > 60:
                return {"success":False}
                break
            time.sleep(1)

        result['success'] = True
        return result

