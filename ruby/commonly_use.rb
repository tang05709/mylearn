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
      
