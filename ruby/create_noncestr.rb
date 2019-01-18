#随机数生成算法
    def create_noncestr
      len = 30
      chars = ("a".."z").to_a + ("A".."Z").to_a + ("0".."9").to_a
      newpass = ""
      1.upto(len) { |i| newpass << chars[rand(chars.size-1)] }
      newpass
    end

    #生成签名[排序，url参数格式，拼接key，机密]
    def sign_param(param)
      param = param.sort{ |a,b| a.to_s <=> b.to_s }.to_h

      format = []
      param.each_with_index do |value|
        format << "#{value[0]}=#{value[1]}"
      end

      Digest::SHA1.hexdigest("#{format.join('&')}").upcase
    end
