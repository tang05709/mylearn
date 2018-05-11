  ####### 计算剩余报价天数 ####### 
  def get_last_validity(created_at, last_validity)
    res = '不限'
    if last_validity.blank?
      return res
    end
    # 已发布天数
    time_diff = (Time.now.to_i - created_at.to_i) / (24 * 60 * 60)
    # 剩余天数
    left_day = last_validity.to_i - time_diff
    if left_day > 0
      res = "#{left_day}后过期"
    else
      res = "已过期"
    end
    return res
  end
  ####### 计算剩余报价天数 #######

  #####通过时间函数获取1个月的数据信息#####
  Article.where(created_at: (Time.now - 30.day)..Time.now)
  #####通过时间函数获取1个月的数据信息#####
