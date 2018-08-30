import requests

r = requests.get(image_url)
with open('./img/imgage1.png', 'wb') as f:
  f.write(r.content)
  
  
下载大文件
r = requests.get(image_url, stream = True)
with open('./img/image1.png', 'wb') as f:
  for chunk in r.iter_content(chunk_size=32):
    f.write(chunk)
