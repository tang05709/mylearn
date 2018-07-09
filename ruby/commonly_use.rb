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
