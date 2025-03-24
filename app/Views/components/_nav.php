<?php
/**
 * Mảng định nghĩa menu
 */
include_once __DIR__ . '/_nav_config.php';

// Gọi hàm nav_config() từ namespace App\Views\Components
$menuItems = \App\Views\Components\nav_config();

/**
 * Render menu from array
 */
function renderMenu($items) {
	$html = '<ul class="metismenu" id="menu">';
	
	foreach ($items as $item) {
		if (isset($item['type']) && $item['type'] === 'label') {
			$html .= '<li class="menu-label">' . $item['title'] . '</li>';
		} else {
			$hasSubmenu = isset($item['submenu']) && is_array($item['submenu']) && count($item['submenu']) > 0;
			$target = isset($item['target']) ? ' target="' . $item['target'] . '"' : '';
			
			$html .= '<li>';
			
			if ($hasSubmenu) {
				$html .= '<a href="javascript:;" class="has-arrow">';
			} else {
				$html .= '<a href="' . $item['url'] . '"' . $target . '>';
			}
			
			$html .= '<div class="parent-icon"><i class="' . $item['icon'] . '"></i></div>';
			$html .= '<div class="menu-title">' . $item['title'] . '</div>';
			$html .= '</a>';
			
			if ($hasSubmenu) {
				$html .= '<ul>';
				foreach ($item['submenu'] as $subitem) {
					$html .= '<li>';
					$html .= '<a href="' . $subitem['url'] . '">';
					$html .= '<i class="bx bx-right-arrow-alt"></i>' . $subitem['title'];
					$html .= '</a>';
					$html .= '</li>';
				}
				$html .= '</ul>';
			}
			
			$html .= '</li>';
		}
	}
	
	$html .= '</ul>';
	return $html;
}

// Render menu
echo renderMenu($menuItems);
?>
