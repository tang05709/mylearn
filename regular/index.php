# 验证带中文的用户名
LOGIN_FORMAT = 'a-zA-Z\d\u4e00-\u9fa5'
ALLOW_LOGIN_FORMAT_REGEXP = /\A[#{LOGIN_FORMAT}]+\z/

# 验证英文和下划线的用户名
LOGIN_FORMAT = 'A-Za-z0-9\-\_\.'
ALLOW_LOGIN_FORMAT_REGEXP = /\A[#{LOGIN_FORMAT}]+\z/
