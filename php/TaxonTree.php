<?php
namespace taxon;

class TaxonTree 
{
  private $data;
  public function __construct($data) {
    $this->data = $data;
  }

  public function buildTree($parent_id = 0) {
    $list = [];
    foreach ($this->data as $key => $val) {
      if ( $val['parent_id'] == $parent_id ) {
        $v['id'] = $val['id'];
        $v['name'] = $val['name'];
        $v['module'] = $val['module'];
        $v['filename'] = $val['filename'];
        $v['icon'] = $val['icon'];
        $v['is_show'] = $val['is_show'];
        $v['sort'] = $val['sort'];
        $v['children'] = $this->buildList($val['id']);
        $list[] = $v;
      }
    }
    return $list;
  }

  public function buildOptions($parent_id = 0, $level = 1, $style = '-') {
    $options = '';
    foreach ($this->data as $key => $val) {
      if ($val['parent_id'] == $parent_id) {
        $style = str_repeat($style, $level);
        $options .= "<option value='".$val['id']."'>".$style . $val['name']."</option>";
        $options .= $this->buildOptions($val['id'], $level + 1, $style);
      }
    }
    return $options;
  }

  public function buildList($parent_id = 0, $level = 1, $style = '-') {
    $list = [];
    foreach ( $this->data as $key => $val ) {
      if ( $val['parent_id'] == $parent_id ) {
        if (!empty($style)) {
          $style = str_repeat($style, $level);
        }
        $v['id'] = $val['id'];
        $v['name'] = $val['name'];
        $v['module'] = $val['module'];
        $v['filename'] = $val['filename'];
        $v['icon'] = $val['icon'];
        $v['is_show'] = $val['is_show'];
        $v['sort'] = $val['sort'];
        $v['level'] = $level;
        $v['style'] = $style;
        $list[] = $v;
        $children = $this->buildList($v['id'], $level + 1, $style);
        $list = array_merge($list, $children);
      }
    }
    return $list;
  }
}

?>
