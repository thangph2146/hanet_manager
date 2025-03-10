<?php
/**
 * 9/17/2022
 * AUTHOR:PDV-PC
 */

namespace App\Libraries;

class MyButton {

	public function buttonEditDelete($url, $class, $style, $js, $title, $icon, $label)
	{
		return view('MyButton/buttonEditDelete', [
			'url' => $url,
			'class' => $class,
			'style' => $style,
			'js' => $js,
			'title' => $title,
			'icon' => $icon,
			'label' => $label
		]);
	}

	public function iconChecked($label)
	{
		return view('MyButton/iconChecked', ['label' => $label]);
	}

	public function iconRestored($url)
	{
		return view('MyButton/iconRestored', [
			'url' => $url
		]);
	}

	public function inputCheck($class, $name, $id, $array, $label)
	{
		return view('MyButton/inputCheck', [
			'class' => $class,
			'name' => $name,
			'id' => $id,
			'array' => $array,
            'label' => $label
		]);
	}

	public function selectMulti($label, $type, $name, $select, $arraySelected)
	{
		return view('MyButton/selectMulti', [
			'label' => $label,
			'type' => $type,
			'name' => $name,
			'select' => $select,
			'arraySelected' => $arraySelected
		]);
	}
}

