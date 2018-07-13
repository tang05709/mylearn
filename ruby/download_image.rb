require 'open-uri'

module Utils
  class Download
    def self.download_image(url, dir)
      save_path = Rails.root.join('public', dir)
      unless Dir.exists? save_path 
        Dir.mkdir save_path
      end
      #获取图片名称
      last_index = url.rindex('/') + 1 
      image_name = url[last_index..-1]  
      save_name = "https://" + ENV.fetch('DEFAULT_HOST') + '/' + dir + image_name
      path_name = Rails.root.join('public', dir, image_name)
      
      open(url) do |u|
        File.open(path_name, 'wb') { |f| f.write(u.read) }
      end
      save_name
    end
  end
end
