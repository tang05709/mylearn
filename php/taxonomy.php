<?php
  // 重组分类列表
  // @taxonomies 分类数据
  // @parent_id 父级id
  public static function recombine_taxonomies($taxonomies, $parent_id = 0) {
      $tree = [];
      foreach($taxonomies as $key => $taxon) 
      {
          if($taxon['parent_id'] == $parent_id) 
          {
              $tree[] = $taxon;
              $tree = array_merge($tree, self::recombine_taxonomies($taxonomies, $taxon['id']));
          }
      }
      return $tree;
  }

?>
