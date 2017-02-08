import re
import urllib2
import time
import socket
import os
import sys
import MySQLdb
import base64

from modules.poj import *
from modules.hdu import *
from modules.config import *


class oj_checker(object):

    def main(self, db_config, check_config):

        oj_name = check_config['oj_name']

        if oj_name == "poj":
            result = poj().main(check_config)
        elif oj_name == "hdu":
            result = hdu().main(check_config)
        else:
            return

        if result['success'] == True:
            db = MySQLdb.connect(db_config['host'], db_config['username'], db_config['password'], db_config['dbname'])
            cursor = db.cursor()
            sql = ("update remote_oj_submissions set result = '" + result['result'] + "', " +
                   "status = '" + result['result'] + "', " +
                   "submission_id = '" + result['id'] + "', " +
                   "used_time = '" + result['time'] + "', " +
                   "used_memory = '" + result['memory'] + "', " +
                   "tot_size = '" + result['size'] +
                   "' where id =" + check_config['id'] + ";")
            try:
                cursor.execute(sql)
                db.commit()
            except:
                 db.rollback()
        else:
            db = MySQLdb.connect(db_config['host'], db_config['username'], db_config['password'], db_config['dbname'])
            cursor = db.cursor()
            sql = ("update remote_oj_submissions set status = 'failed', result = 'Judgement Error' where id =" + check_config['id'] + ";")
            try:
                cursor.execute(sql)
                db.commit()
            except:
                 db.rollback()
            
            db.close()



if __name__ == '__main__':

    db_config = {}
    db_config = oj_configs().getdbinfo()
    
    flag = False

    while True:
        if oj_configs().debug() == True:
            print "Waiting"
        time.sleep(1)

        db = MySQLdb.connect(db_config['host'], db_config['username'], db_config['password'], db_config['dbname'])
        cursor = db.cursor()
        sql = "select * from remote_oj_submissions where status = 'w';"
        try:
            cursor.execute(sql)
            results = cursor.fetchall()

            if results:
                flag = True
            
            for row in results:
                check_config = {}
                check_config['id'] = row[0]
                check_config['oj_name'] = row[2]
                check_config['language'] = row[4]
                check_config['code'] = row[5]
                check_config['problem_id'] = row[14]
                check_config['code'] = base64.b64decode(check_config['code'])
                check_config['id'] = str(check_config['id'])
                
                checker = oj_checker()
                checker.main(db_config, check_config)
      
        except:
            flag = False
        
        db.close()

