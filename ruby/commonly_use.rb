# 上一篇 下一篇
category_id = params[:category_id]
id = params[:id]
type = params[:turn_type]
condition = " id > #{id} "
order = " id asc "
if type == 'prev'
  condition = " id < #{id} "
  order = " id desc "
end
article = Article.where(condition).where(category_id: category_id).order(order).first
# 上一篇 下一篇
      
# 电话号码中间使用*号代替
def  replace_phone(phone)
  # 电话号码假定11位
  # 获取电话号码的4-7位
  ready_replace = phone[3, 6]
  phone[ready_replace] = '****'
  phone
end
# 电话号码中间使用*号代替

# 转码
str.encode('utf-8','gbk',{:invalid => :replace, :undef => :replace, :replace => '?'})

#设置自动加载
config.autoload_paths += %W(#{config.root}/lib/jizan) 

#设置时区
config.time_zone = 'Beijing'
#设置语言
config.i18n.enforce_available_locales = false
config.i18n.default_locale = :'zh-CN'
#设置编码
config.encoding = "utf-8"

#随机密码
def self.rand_password
  len = 6
  chars = ("a".."z").to_a + ("A".."Z").to_a + ("0".."9").to_a
  Array.new(len, '').collect{chars[rand(chars.size)]}.join
end

#去除数组中的空值
array.delete_if(&:blank?)
