<?php
namespace App\Helpers;
class SelectUser{
  public static function getSelect($array = [], $selected = [],$options = [])
  {
    $class_form = "";
    	if (!empty($options['class'])) {
       $class_form = $options['class'];
      }
      $name = 'user';
    	if (!empty($options['name'])) {
       $name = $options['name'];
      }

    	$selected = [];
    	if (!empty($options['selected'])) {
    		$selected = is_array($options['selected'])?$options['selected']:[$options['selected']];
    	}
      $multiple = '';
      if(!empty($options['multiple'])){
          $multiple = 'multiple';
      }

      $select = '<select class="'.$class_form.'" name="'.$name.'" '.$multiple.' placeholder="'.$options['placeholder'].'">';
      if(empty($options['multiple'])){
        $select .= "<option></option>";
      }
    	$select .= SelectUser::getMultiLevelOptions($array, $selected);
    	$select .= '</select>';

    	return $select;
  }
  public static function getMultiLevelOptions($array, $selected = []) {
    $menu_html = '';
    foreach ($array as $element) {
        $selected_item = '';
         if (in_array($element['id'], $selected)) {
              $selected_item = 'selected';
          }
          $title = '';
          $title .= "Username: $element->username\n";
          $title .= "Nama: $element->name\n";
          $title .= "Alamat: $element->address\n";
          $menu_html .= '<option title="'.$title.'" value="'.$element->id.'" '.$selected_item.'>';
          $menu_html .= $element->name.'</option>';
		}
    return $menu_html;
    }
}