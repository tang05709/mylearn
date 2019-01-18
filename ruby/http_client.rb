
require 'net/http'
require 'uri'
class HttpClient
  attr_reader :base, :ssl_context, :httprb
  def initialize(base = nil , timeout = nil, skip_verify_ssl = false)
    @base = base
    @httprb = HTTP.timeout(:global, write: timeout, connect: timeout, read: timeout)
    @ssl_context = OpenSSL::SSL::SSLContext.new
    @ssl_context.ssl_version = :TLSv1_client
    @ssl_context.verify_mode = OpenSSL::SSL::VERIFY_NONE if skip_verify_ssl
  end

  def get(path, get_header = {})
    request(path, get_header) do |url, header|
      params = header.delete(:params)
      httprb.headers(header).get(url, params: params, ssl_context: ssl_context)
    end
  end

  def post(path, payload, post_header = {})
    request(path, post_header) do |url, header|
      params = header.delete(:params)
      httprb.headers(header).post(url, params: params, body: payload, ssl_context: ssl_context)
    end
  end

  def post_file(path, file, post_header = {})
    request(path, post_header) do |url, header|
      params = header.delete(:params)
      form_file = file.is_a?(HTTP::FormData::File) ? file : HTTP::FormData::File.new(file)
      httprb.headers(header)
        .post(url, params: params,
                   form: { media: form_file,
                           hack: 'X' }, # Existing here for http-form_data 1.0.1 handle single param improperly
                   ssl_context: ssl_context)
    end
  end

  def get_json(file)
    io = File.open(file, "a+")
    data = io.read
    io.close
    if !data.blank?
      data = JSON.parse(data)
    end
  end

  def set_json(file, content)
    io = File.open(file, "w")
    io.puts content
    io.close
  end

  def self.is_mobile?(request)
    client = ['nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile']
    Regexp.new(client.join('|')) =~ request.env['HTTP_USER_AGENT'].downcase
  end

  def postXmlHttp(uri, xml, use_ssl= true)
    uri = URI.parse(uri)
      Net::HTTP.start(uri.host,uri.port,use_ssl:use_ssl) do |http|
          request  = Net::HTTP::Post.new(uri.path)
          request.body = xml
          response = http.request(request)
          response.body
    end 
  end

  def postXmlHttpCert(uri, xml, api_client_cert, api_client_key)
    apiclient_cert = cert_pem_to_x509(api_client_cert)
    apiclient_key = key_pem_to_rsa(api_client_key)
    conn = Faraday.new(ssl: {client_cert: apiclient_cert, client_key: apiclient_key}, headers: {'Content-Type' => 'application/xml'}) do |faraday| faraday.adapter Faraday.default_adapter end
    res = conn.post(uri, xml, {'Content-Type' => 'application/xml'})
    res.body
  end

  def cert_pem_to_x509(cert_pem) 
    cert_x509 = OpenSSL::X509::Certificate.new cert_pem 
  end

  def key_pem_to_rsa(key_pem) 
    key_rsa = OpenSSL::PKey::RSA.new key_pem 
  end

  #data的格式 {"a"=>1,"b"=>3}
  def postHttp(url, data)
    uri = URI(url)
    res = Net::HTTP.post_form(uri, data)
    res.body
  end 

  private

  def request(path, header = {}, &_block)
    url_base = header.delete(:base) || base
    as = header.delete(:as)
    header['Accept'] ||= 'application/json'
    response = yield("#{url_base}#{path}", header)
    raise "Request not OK, response status #{response.status}" if response.status != 200

    parse_response(response, as || :json) do |parse_as, data|
      break data unless parse_as == :json && data['errcode'].present?
      case data['errcode']
      when 0 # for request didn't expect results
        data
      # 42001: access_token timeout
      # 40014: invalid access_token
      # 40001, invalid credential, access_token is invalid or not latest hint
      # 48001, api unauthorized hint, for qrcode creation # 71
      when 42001, 40014, 40001, 48001
        data['errmsg']
      else
        raise data['errmsg']
      end
    end
  end

  def parse_response(response, as)
    content_type = response.headers[:content_type]
    parse_as = {
      %r{^application\/json} => :json,
      %r{^image\/.*}         => :file,
      %r{^audio\/.*}         => :file,
      %r{^voice\/.*}         => :file,
      %r{^text\/html}        => :xml,
      %r{^text\/plain}       => :probably_json
    }.each_with_object([]) { |match, memo| memo << match[1] if content_type =~ match[0] }.first || as || :text


    # try to parse response as json, fallback to user-specified format or text if failed
    if parse_as == :probably_json
      data = JSON.parse response.body.to_s.gsub(/[\u0000-\u001f]+/, '') rescue nil
      if data
        return yield(:json, data)
      else
        parse_as = as || :text
      end
    end

    case parse_as
    when :file
      file = Tempfile.new('tmp')
      file.binmode
      file.write(response.body)
      # update chown
      file.chmod(0777)
      file.close
      data = file

    when :json
      data = JSON.parse response.body.to_s.gsub(/[\u0000-\u001f]+/, '')
    when :xml
      data = Hash.from_xml(response.body.to_s)
    else
      data = response.body
    end

    yield(parse_as, data)
  end


  
end
