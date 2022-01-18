<?php
class Admin {
    function input($name, $label, $class, $value, $is_required = false, $readonly=FALSE, $style="", $hint="", $note = '', $limit=0,$placeholder='',$max_length=false,$input_type='text') {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = isset($value->$name)?($value->$name):'';
		$data['style'] = $style;
		$data['hint'] = $hint;
		$data['readonly'] = $readonly;
        $data['note'] = $note;
        $data['is_required'] = $is_required;
        $data['limit'] = $limit;
        $data['placeholder'] = $placeholder;
        $data['max_length'] = $max_length;
        $data['input_type'] = $input_type;
		
		$result = $CI->load->view('admin/lib/input', $data, true);
		echo $result;
    }
	
	/* 
		COLS es la cantidad de inputs uno al lado del otro a mostrar 
		NAMES y VALUES son arrays segun la cantidad de columnas
	*/
    function input_cols($name, $label, $class, $value, $is_required = false, $cols = 2, $readonly=FALSE, $style="", $hint="", $note = '') {
		$CI =& get_instance();
		
		$data['names'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['values'] = $value;
		$data['style'] = $style;
		$data['hint'] = $hint;
		$data['readonly'] = $readonly;
        $data['note'] = $note;
        $data['is_required'] = $is_required;
        $data['cols'] = $cols;
		
		$result = $CI->load->view('admin/lib/input_cols', $data, true);
		echo $result;
    }
	
	function input_ext($name, $label, $class, $value, $name2, $label2, $value2, $name3, $label3, $value3, $name4, $label4, $value4, $attributes="",$style="") {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = utf8_decode($value);
		$data['name2'] = $name2;
		$data['label2'] = $label2;
		$data['value2'] = utf8_decode($value2);
		$data['name3'] = $name3;
		$data['label3'] = $label3;
		$data['value3'] = utf8_decode($value3);
		$data['name4'] = $name4;
		$data['label4'] = $label4;
		$data['value4'] = utf8_decode($value4);
		$data['attributes'] = $attributes;
		$data['style'] = $style;
		
		$result = $CI->load->view('admin/lib/input_ext', $data, true);
		echo $result;
    }

    function password($name, $label, $class, $value,$size='',$is_required=false) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = $value;
        $data['size'] = $size;
        $data['is_required'] = $is_required;
		
		$result = $CI->load->view('admin/lib/password', $data, true);
		echo $result;
    }
	
    function textarea($name, $label, $class, $value, $rows = '7', $cols = '50', $twolines=false, $chars_limit='', $hint="") {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = isset($value->$name)?($value->$name):'';
        $data['rows'] = $rows;
        $data['cols'] = $cols;
		$data['twolines'] = $twolines;
		$data['hint'] = $hint;
		$data['chars_limit'] = $chars_limit;
		
		$result = $CI->load->view('admin/lib/textarea', $data, true);
		echo $result;
    }	

    function htmlarea($name, $label, $class, $value) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = $value;
		
		$result = $CI->load->view('admin/lib/htmlarea', $data, true);
		echo $result;
    }	

    function combo($name, $label, $class, $value, $combodata, $valueField, $captionField, $is_required=false, $is_multiple=false, $hint="", $readonly=false) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = isset($value->$name)?($value->$name):'';
		$data['data'] = $combodata;
		$data['valueField'] = $valueField;
		$data['captionField'] = $captionField;
		$data['is_multiple'] = $is_multiple;
		$data['hint'] = $hint;
		$data['is_required'] = $is_required;
		$data['readonly'] = $readonly;
		
		$result = $CI->load->view('admin/lib/combo', $data, true);
		echo $result;
    }	
	
    function checkbox($name, $label, $class, $value, $mode='', $hint="", $attr=array()) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = isset($value->$name)?utf8_decode($value->$name):'';
		$data['hint'] = $hint;
		$data['mode'] = $mode;
		$data['attr'] = $attr;
		
		$result = $CI->load->view('admin/lib/checkbox', $data, true);
		echo $result;
    }	
	
    function radio($name, $label, $class, $value, $radiodata, $valueField, $captionField, $mode='') {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = $value;
		$data['data'] = $radiodata;
		$data['valueField'] = $valueField;
		$data['captionField'] = $captionField;
		$data['mode'] = $mode;
		
		$result = $CI->load->view('admin/lib/radio', $data, true);
		echo $result;
    }	

    function inputlang($name, $label, $class, $value) {
		$CI =& get_instance();
		
		$CI->config->load('general');
		$languages = $CI->config->item('languages');
				
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		
		$values = array();
		foreach ($languages as $lang) {
			$v = $name.'_'.$lang;
			if ($value != '')
				$langValue = $value->{$v};
			else
				$langValue = "";
			$values[] = array('lang'=>$lang, 'value'=>$langValue);
		}
		$data['languages'] = $values;
		
		$result = $CI->load->view('admin/lib/input_lang', $data, true);
		echo $result;
    }		
	
    function textarealang($name, $label, $class, $value) {
		$CI =& get_instance();
		
		$CI->config->load('general');
		$languages = $CI->config->item('languages');
				
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		
		$values = array();
		foreach ($languages as $lang) {
			$v = $name.'_'.$lang;
			if ($value != '')
				$langValue = $value->{$v};
			else
				$langValue = "";
			$values[] = array('lang'=>$lang, 'value'=>$langValue);
		}
		$data['languages'] = $values;
		
		$result = $CI->load->view('admin/lib/textarea_lang', $data, true);
		echo $result;
    }	

    function htmlarealang($name, $label, $class, $value) {
		$CI =& get_instance();
		
		$CI->config->load('general');
		$languages = $CI->config->item('languages');
				
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		
		$values = array();
		foreach ($languages as $lang) {
			$v = $name.'_'.$lang;
			if ($value != '')
				$langValue = $value->{$v};
			else
				$langValue = "";
			$values[] = array('lang'=>$lang, 'value'=>$langValue);
		}
		$data['languages'] = $values;
		
		$result = $CI->load->view('admin/lib/htmlarea_lang', $data, true);
		echo $result;
    }	

    /*function file($name, $label, $class, $value) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = $value;
		
		$result = $CI->load->view('admin/lib/file', $data, true);
		echo $result;
    }*/
	
	//esta es del admin anterior
	function file($name, $label, $class, $value, $folder, $type = 'view',$attributes="",$accept = "*") {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = $value;
		$data['folder'] = $folder;
		$data['type'] = $type;
        $data['attributes'] = $attributes;
		$data['accept'] = $accept;
        
		$result = $CI->load->view('admin/lib/file', $data, true);
		echo $result;
    }
	
	function img_gallery($name, $value, $folder,$id) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['value'] = $value;
		$data['folder'] = $folder;
		$data['id'] = $id;
		
		$result = $CI->load->view('admin/lib/img_gallery', $data, true);
		echo $result;
    }
	
    function autosuggest($name, $label, $class, $value, $url) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = $value;
		$data['url'] = $url;
		
		$result = $CI->load->view('admin/lib/autosuggest', $data, true);
		echo $result;
    }
	
    function hidden($name, $value) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['value'] = $value;
		
		$result = $CI->load->view('admin/lib/hidden', $data, true);
		echo $result;
    }
	
    function datepicker($name, $label, $class, $value,$is_required=false,$readonly=false,$fullscreen=true) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['is_required'] = $is_required;
		$data['readonly'] = $readonly;
		$data['fullscreen'] = $fullscreen;
		$data['value'] = isset($value->$name)?($value->$name):'';
		
		$result = $CI->load->view('admin/lib/datepicker', $data, true);
		echo $result;
    }
	
    function timepicker($name, $label, $class, $value,$is_required=false,$readonly=false) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['is_required'] = $is_required;
		$data['readonly'] = $readonly;
		$data['value'] = isset($value->$name)?($value->$name):'';
		
		$result = $CI->load->view('admin/lib/timepicker', $data, true);
		echo $result;
    }
	
    function date($name, $label, $class, $value, $is_required=false, $format_date="99/99/9999", $readonly=false) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['is_required'] = $is_required;
		$data['value'] = isset($value->$name)?($value->$name):'';
		$data['format_date'] = $format_date;
		
		$result = $CI->load->view('admin/lib/date', $data, true);
		echo $result;
    }
	
    function autocomplete($name, $label, $class, $value, $is_required=false, $url, $matches=false) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['value'] = $value;
		$data['url'] = $url;
		$data['matches'] = $matches;
		$data['is_required'] = $is_required;
		
		$result = $CI->load->view('admin/lib/autocomplete', $data, true);
		echo $result;
    }

    function comboAgregable($name, $label, $class, $values, $combodata, $valueField, $captionField,$fk_field) {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['class'] = $class;
		$data['values'] = $values;
		$data['data'] = $combodata;
		$data['valueField'] = $valueField;
		$data['captionField'] = $captionField;
		$data['fk_field'] = $fk_field;
		
		$result = $CI->load->view('admin/lib/comboAgregable', $data, true);
		echo $result;
    }	

	function th($name, $label, $sort=false, $attributes="", $hide='') {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['sort'] = $sort;
		$data['attributes'] = $attributes;
		$data['hide'] = $hide;
		
		$result = $CI->load->view('admin/lib/th', $data, true);
		echo $result;		
	}

	function td($value, $style='') {
		$CI =& get_instance();
		
		$data['value'] = utf8_encode($value);
		$data['style'] = $style;
		
		$result = $CI->load->view('admin/lib/td', $data, true);
		echo $result;		
	}
	
	function td_img($value, $width=90, $attributes="") {
		$CI =& get_instance();
		
		$data['value'] = utf8_encode($value);
		$data['width'] = $width;
		$data['attributes'] = $attributes;
		
		$result = $CI->load->view('admin/lib/td_img', $data, true);
		echo $result;		
	}
	
	function showErrors() {
		$CI =& get_instance();
		if (isset($CI->data['errorsFound'])):
			echo '<div id="divErrors" class="alert alert-danger fade in">';
			echo '<i class="icon-remove close" data-dismiss="alert"></i>';
			echo '<strong>Error</strong>';
			echo $CI->data['errorsText'];
			echo validation_errors();
			echo '</div>';
		endif;
	}	

	function tdtoggle($value, $url, $id) {
		$CI =& get_instance();
		$data['status'] = $value ? 'yes' : 'no';
		$data['action'] = $value ? '0' : '1';
		$data['url'] = $url.'/'.$id.'/';
		$result = $CI->load->view('admin/lib/toggle', $data, true);
		echo $result;
	}
	
	function th_orden($name, $label, $sort=false, $url, $css="", $formId = 'formList') {
		$CI =& get_instance();
		
		$data['name'] = $name;
		$data['label'] = $label;
		$data['url'] = $url;
		$data['css'] = $css;
		$data['formId'] = $formId;
		
		$result = $CI->load->view('admin/lib/th_orden', $data, true);
		echo $result;		
	}
	
	function td_input($name, $value, $css) {
		$CI =& get_instance();
		$data['value'] = $value;
		$data['name'] = $name;
		$data['css'] = $css;
		$result = $CI->load->view('admin/lib/td_input', $data, true);
		echo $result;
	}
}
?>