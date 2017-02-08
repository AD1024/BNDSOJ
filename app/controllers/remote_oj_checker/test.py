from modules.hdu import *

check_config = {}

check_config['language'] = "C++"
check_config['problem_id'] = "1000"
check_config['code'] = """
#include <iostream>

using namespace std;

int main(){
    int a, b;
    while(cin >> a >> b){
        cout << a + b << endl;
    }
    return 0;
}

"""


print hdu().main(check_config)
