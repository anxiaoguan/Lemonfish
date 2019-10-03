# VS Code
# Python3.7.4
import sys
import os
from time import sleep
import keyboard  # 监控键盘
from PIL import Image, ImageGrab #Pillow
from aip import AipOcr


print('本程序使用方法：\n 1. 将您的 QQ或微信 的【截图热键】设置为"F1" \n 2. 截图完毕后按"F2"运行文字识别 \n 3. 如不想修改热键，则完成截图后，按顺序敲一下"F1"和"F2"即可 \n 4. 然后稍等片刻即可......')

""" 你的 APPID AK SK """
APP_ID = ''
API_KEY = ''
SECRET_KEY = ''

client = AipOcr(APP_ID, API_KEY, SECRET_KEY)

def screenShot():
    
    '''用于获取剪切板图片信息并保存到本地'''

    if keyboard.wait(hotkey='f1') == None:

        if keyboard.wait(hotkey='f2') == None:
            sleep(1)
            # 获取剪切板的图像内容
            image = ImageGrab.grabclipboard()
            image.save('imageOcr.png')
            sleep(1)

            """ 读取图片 """
            def get_file_content(filePath):
                with open(filePath, 'rb') as fp:
                    return fp.read()
                    
            images = get_file_content('imageOcr.png')

            """ 调用通用文字识别, 图片参数为本地图片 """
            text = client.basicAccurate(images)

            ''' 读取当前目录路径 '''
            pwd = os.path.dirname(os.path.realpath(__file__))

            ''' 判断初始化文件是否存在 '''
            if os.path.isfile(pwd + '\\ocr.txt'):
                ''' 初始化删除文件 '''
                os.remove(pwd + "\\ocr.txt")
              
            ''' 打印识别结果 '''
            for key in text['words_result']:
                    print (key['words'])                    

                    ''' 保存为txt文件 '''                    
                    fo = open(pwd + "\\ocr.txt", "a+") 
                    fo.write(key['words'] + '\n')                    

                    fo.readlines() #打开文件，读入每一行
                                       
                    

            ''' 打开txt文件 '''
            os.startfile(pwd + '\\ocr.txt')

if __name__ == '__main__':
    ''' 循环截图 '''
    for _ in range(sys.maxsize):
        screenShot()
