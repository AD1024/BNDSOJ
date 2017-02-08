

class oj_configs(object):

    def getojinfo(self, oj_name):
        oj_info = {}
        f = open("config.conf")
        lines = f.readlines()
        for line in lines:
            if line[:1] == '#':
                continue
            if line[0:len(oj_name)+10] == oj_name + "_username:":
                oj_info['username'] = line[len(oj_name)+10:]
                oj_info['username'] = oj_info['username'].strip("\n")
                oj_info['username'] = oj_info['username'].strip("\r")

            if line[0:len(oj_name)+10] == oj_name + "_password:":
                oj_info['password'] = line[len(oj_name)+10:]
                oj_info['password'] = oj_info['password'].strip("\n")
                oj_info['password'] = oj_info['password'].strip("\r")

        f.close()
        
        return oj_info

    def getdbinfo(self):
        db_config = {}
        f = open("config.conf")
        lines = f.readlines()
        for line in lines:
            if line[:1] == '#':
                continue
            if line[0:8] == "db_user:":
                db_config['username'] = line[8:]
                db_config['username'] = db_config['username'].strip("\n")
                db_config['username'] = db_config['username'].strip("\r")

            if line[0:8] == "db_pass:":
                db_config['password'] = line[8:]
                db_config['password'] = db_config['password'].strip("\n")
                db_config['password'] = db_config['password'].strip("\r")

            if line[0:8] == "db_host:":
                db_config['host'] = line[8:]
                db_config['host'] = db_config['host'].strip("\n")
                db_config['host'] = db_config['host'].strip("\r")

            if line[0:10] == "db_dbname:":
                db_config['dbname'] = line[10:]
                db_config['dbname'] = db_config['dbname'].strip("\n")
                db_config['dbname'] = db_config['dbname'].strip("\r")

        f.close()
        
        return db_config
                
    def debug(self):
        flag = False
        f = open("config.conf")
        lines = f.readlines()
        for line in lines:
            if line[:1] == '#':
                continue
            if "Debug true" in line:
                flag = True
                break

        f.close()

        return flag
