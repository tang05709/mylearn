<?php
class BaseFormGroup {
    
    /**
     * 普通输入框
     */
    public function input_field($name, $value,  $placeholder = '', $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        return '<input type="text" id="'. $name .'" name="'. $name .'" value="'. $value .'" class="form-control" '. $data_html_str .' placeholder="'. $placeholder.'">';
    }
    /**
     * 数字输入框
     */
    public function number_field($name, $value,  $placeholder = '', $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        return '<input type="text" id="'. $name .'" name="'. $name .'" value="'. $value .'" class="form-control" '. $data_html_str .' placeholder="'. $placeholder.'" style="width: 150px">';
    }
    /**
     * 单文件上传框
     */
    public function file_field($name, $value,  $placeholder = '', $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        return '<input type="file" id="'. $name .'" name="'. $name .'" value="'. $value .'" class="form-control" '. $data_html_str .' placeholder="'. $placeholder.'">';
    }
    /**
     * 多文件上传框
     */
    public function multiple_file_field($name, $value,  $placeholder = '', $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        return '<input type="file" id="'. $name .'" name="'. $name .'" value="'. $value .'" class="form-control" '. $data_html_str .' placeholder="'. $placeholder.'" multiple>';
    }
     /**
     * textarea
     */
    public function textarea_field($name, $value,  $placeholder = '', $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        return '<textarea id="'. $name .'" name="'. $name .'" class="form-control" '. $data_html_str .'>'. $value .'</textarea>';
    }
     /**
     * wangeditor
     */
    public function wangeditor_field($name, $value,  $placeholder = '', $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        $html = '<div id="'. $name .'" class="wangeditor-control" '. $data_html_str .'>'. $value .'</div>';
        $html .= '<input type="hidden" name="'. $name .'" class="wangeditor_value" />';
        return $html;
    }
    /**
     * 下拉框
     * data格式： [key => value]
     */
    public function select_field($name, $value, $data, $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        $html = '<select id="'. $name .'" name="'.$name.'" class="form-control" '. $data_html_str .'>';
        $html .= '<option value="">请选择</option>';
        foreach($data as $key => $val) {
            if($key == $value) {
                $html .= '<option value="'. $key .'" selected="selected">'. $val .'</option>';
            } else {
                $html .= '<option value="'. $key .'">'. $val .'</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    /**
     * radio
     * data格式： [key => value]
     */
    public function radio_field($name, $value, $data, $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        $html = '<div '. $data_html_str.'>';
        foreach($data as $key => $val) {
            if($key == $value) {
                $html .= '<label class="radio-inline"><input type="radio" id="'. $name . '_' . $key.'" name="'. $name .'"  value="'. $key .'" checked="checked">'. $val .'</option></label>';
            } else {
                $html .= '<label class="radio-inline"><input type="radio" id="'. $name . '_' . $key.'" name="'. $name .'"  value="'. $key .'">'. $val .'</option></label>';
            }
        }
        $html .= '</div>';
        return $html;
    }
    /**
     * checkbox
     * data格式： [key => value]
     */
    public function checkbox_field($name, $value, $data, $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        $html = '<div '. $data_html_str.'>';
        foreach($data as $key => $val) {
            if($key == $value) {
                $html .= '<label class="checkbox-inline"><input type="checkbox" id="'. $name . '_' . $key.'" name="'. $name .'"  value="'. $key .'" checked="checked">'. $val .'</option></label>';
            } else {
                $html .= '<label class="checkbox-inline"><input type="checkbox" id="'. $name . '_' . $key.'" name="'. $name .'"  value="'. $key .'">'. $val .'</option></label>';
            }
        }
        $html .= '</div>';
        return $html;
    }
    /**
     * 创建表单快
     */
    public function create_form_group($label_content, $field_content) {
        return '<div class="form-group">'. $label_content . $field_content .'</div>';
    }
    /**
     * label标签
     */
    public function label_html($label, $required = false, $data_html = []) {
        $data_html_str = $this->exec_data_html($data_html);
        if($required) {
            $label .= ' <span>*</span>';
        }
        return '<label class="col-sm-2 control-label" '. $data_html_str .'>'. $label .'</label>';
    }
    /**
     * 表单域
     */
    public function field_html($name, $field_html, $icon = '', $errors = '', $helper = '') {
        $icon_html = '';
        $helper_html = '';
        $error_html = '';
        if(!empty($icon)) {
            $icon_html = $this->icon_html($icon);
        }
        if(!empty($helper)) {
            $helper_html = $this->helper_html($helper);
        }
        if(!empty($errors)) {
            $error_html = $this->error_html($name, $errors);
        }
        $html = '<div class="col-sm-8">'. $error_html .'<div class="input-group">';
        $html .= $icon_html . $field_html . '</div>'. $helper_html .'</div>';
        return $html;
    }
    /**
     * 输入框图标
     */
    public function icon_html($icon) {
        return '<span class="input-group-addon"><i class="fa fa-'. $icon .' fa-fw"></i></span>';
    }
    /**
     * 表单错误提示
     */
    public function error_html($key, $errors) {
        $html = '';
        if(!empty($errors)) {
            if($errors->has($key)) {
                $html .= '<label class="control-label error-label">';
                foreach ($errors->get($key) as $message) {
                    $html .= $message .'&nbsp;&nbsp;';
                }
                $html .= '</label>';
            }
        }
        return $html;
    }
    /**
     * 表单帮助提示
     */
    public function helper_html($helper) {
        return '<p class="help-block">'. $helper .'</p>';
    }
    /**
     * 媒体显示
     * medias格式： [path => src]
     * type: image video
     */
    public function media_list($medias, $type = 'image') {
        $html = '<div class="row media-list"><div class="col-sm-2"> &nbsp;</div> <div class="col-sm-8"><ul class="clearfix">';
        foreach($medias as $data) {
            $html .= '<li><div class="img-info">';
            if($type == 'video') {
                $html .= '<video src="'. env('APP_URL') . $data .'"></video';
            } else {
                $html .= '<img src="'. env('APP_URL') . $data .'" />';
            }
            $html .= '<p>'. $data .'</p>';
            $html .= ' </div></li>';
        }
        $html .= '</ul></div></div>';
        return $html;
    }
    /**
     * data_html 主装为 data- 
     * 格式: ['title' => title, 'data-id' => id]
     */
    protected function exec_data_html ($data_html = []) {
        $html = '';
        if (!empty($data_html)) {
            foreach($data_html as $key => $val) {
                $html .= 'data-' . $key . '=' . $val;
            }
        }
        return $html;
    }
}


class MyForm extends BaseFormGroup {
    
    private $model;
    /**
     * 创建表单
     */
    public function open_form($model, $url, $method='POST', $data_html = []) {
        $this->model = $model;
        $data_html_str = $this->exec_data_html($data_html);
        return '<form class="form-horizontal" name="theForm" action="'. $url .'" method="'. $method .'" role="form" '. $data_html_str .'>';
    }
    /**
     * 表单结束
     */
    public function close_form() {
        return '</form>';
    }
    /**
     * 提交表单
     */
    public function submit_form() {
        $label_content = '<label class="col-sm-2 control-label">&nbsp;</label>';
        $field_content = '<div class="col-sm-8">';
        $field_content .= '<div class="pull-left reset-btn"><input type="reset" class="btn btn-default" value="重 置" /></div>';
        $field_content .= '<div class="pull-right submit-btn"><input type="submit" class="btn btn-info" value="提 交" /></div>';
        $field_content .= '</div>';
        $form_group = $this->create_form_group($label_content, $field_content);
        return $form_group;
    }
    /**
     * 普通文本表单
     */
    public function normal_text_input($name, $errors = '', $label = '', $placeholder = '', $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $placeholder = empty($placeholder) ? __('placeholder.'.$name) : $placeholder;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->input_field($name, $value, $placeholder, $field_data);
        $field_content = $this->field_html($name, $field_html, 'pencil', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * 数字表单
     */
    public function number_text_input($name, $errors = '', $label = '', $placeholder = '', $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $placeholder = empty($placeholder) ? __('placeholder.'.$name) : $placeholder;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->number_field($name, $value, $placeholder, $field_data);
        $field_content = $this->field_html($name, $field_html, 'pencil', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * 邮箱表单
     */
    public function email_text_input($name, $errors = '', $label = '', $placeholder = '', $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $placeholder = empty($placeholder) ? __('placeholder.'.$name) : $placeholder;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->input_field($name, $value, $placeholder, $field_data);
        $field_content = $this->field_html($name, $field_html, 'envelope-o', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * 电话表单
     */
    public function phone_text_input($name, $errors = '', $label = '', $placeholder = '', $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $placeholder = empty($placeholder) ? __('placeholder.'.$name) : $placeholder;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->input_field($name, $value, $placeholder, $field_data);
        $field_content = $this->field_html($name, $field_html, 'mobile-phone', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * 连接表单
     */
    public function url_text_input($name, $errors = '', $label = '', $placeholder = '', $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $placeholder = empty($placeholder) ? __('placeholder.'.$name) : $placeholder;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->input_field($name, $value, $placeholder, $field_data);
        $field_content = $this->field_html($name, $field_html, 'internet-explorer', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
     /**
     * textarea表单
     */
    public function textarea_input($name, $errors = '', $label = '', $placeholder = '', $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $placeholder = empty($placeholder) ? __('placeholder.'.$name) : $placeholder;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->textarea_field($name, $value, $placeholder, $field_data);
        $field_content = $this->field_html($name, $field_html, '', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * 单图表单
     */
    public function image_input($name, $errors = '', $label = '', $placeholder = '', $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $placeholder = empty($placeholder) ? __('placeholder.'.$name) : $placeholder;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $media_html = '';
        if(!empty($value)) {
            $medias = [$value];
            $media_html = $this->media_list($medias);
        }
        $label_content = $media_html . $label_content;
        $field_html = $this->file_field($name, $value, $placeholder, $field_data);
        $field_content = $this->field_html($name, $field_html, 'file-image-o', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * 视频表单
     */
    public function video_input($name, $errors = '', $label = '', $placeholder = '', $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $placeholder = empty($placeholder) ? __('placeholder.'.$name) : $placeholder;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $media_html = '';
        if(!empty($value)) {
            $medias = [$value];
            $media_html = $this->media_list($medias, 'video');
        }
        $label_content = $media_html . $label_content;
        $field_html = $this->file_field($name, $value, $placeholder, $field_data);
        $field_content = $this->field_html($name, $field_html, 'video-camera', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * 视频表单
     */
    public function content_input($name, $errors = '', $label = '', $placeholder = '', $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $placeholder = empty($placeholder) ? __('placeholder.'.$name) : $placeholder;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->wangeditor_field($name, $value, $placeholder, $field_data);
        $field_content = $this->field_html($name, $field_html, '', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * 下拉表单
     */
    public function select_input($name, $data, $errors = '', $label = '',  $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->select_field($name, $value, $data, $field_data);
        $field_content = $this->field_html($name, $field_html, '', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * radio
     */
    public function radio_input($name, $data, $errors = '', $label = '',  $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->radio_field($name, $value, $data, $field_data);
        $field_content = $this->field_html($name, $field_html, '', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }
    /**
     * check_box
     */
    public function checkbox_input($name, $data, $errors = '', $label = '',  $helper = '', $label_data = [], $field_data = []) {
        $label = empty($label) ? __('label.'.$name) : $label;
        $required = array_key_exists($name, $this->model::$rules) ? true : false;

        $label_content = $this->label_html($label, $required, $label_data);
        $value = $this->model->$name;
        $field_html = $this->checkbox_field($name, $value, $data, $field_data);
        $field_content = $this->field_html($name, $field_html, '', $errors, $helper);

        $html = $this->create_form_group($label_content, $field_content);
        return $html;
    }

}
?>
