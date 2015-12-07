<?php
	function wc_ms_states_identity($msstates) {
		$states = array();
		if ($msstates)
			foreach($msstates as $ms) {
				$states[(string)$ms['name']] = (string)$ms->uuid;
			}
			//echo "<pre>"; print_r($states); echo "</pre>";
		$wcstates = wc_get_order_statuses();
		foreach ($wcstates as $key=>$value) {
			$opt_name = "woosklad_states_".('wc-'===substr($key,0,3) ? substr($key,3) : $key)."_uuid";
			if (array_key_exists($value, $states)) {
				//echo "$opt_name <br />";
			}
			else  {
				$states[$value] = '';
			}
			if (get_option($opt_name) != $states[$value])
				update_option($opt_name, $states[$value]);
		}
		return $states;
	}
	
	function wc_ms_attribute_identity($msattr) {
		$attributes = array();
		if ($msattr) {
			foreach ($msattr as $ms) {
				$attributes[strtoupper((string)$ms['name'])] = (string)$ms->uuid;
			}
		}
		$wcattr = wc_get_attribute_taxonomies();
		foreach ($wcattr as $wc) {
			if (!array_key_exists(strtoupper($wc->attribute_label), $attributes)){
				$attributes[strtoupper($wc->attribute_label)] = '';
			}
			$opt_name = 'woosklad_attribute_'.$wc->attribute_name;
			update_option($opt_name, $attributes[strtoupper($wc->attribute_label)]);
		}
		return $attributes;
		//echo "<pre>"; print_r($attributes); echo "</pre>";
	}
	
	function get_id_by_uuid($uuid) {
		global $wpdb;
		$id = $wpdb->get_col($wpdb->prepare("
			SELECT post_id FROM $wpdb->postmeta 
			WHERE meta_key = '_woosklad_consignment_uuid' AND meta_value='%s'", $uuid));
		return $id ? $id[0] : 0;
	}
		
	/*function get_uuid_by_name($name) {
		get_stock($info, $result, $name);
		if ($info['http_code'] == 200) {
			$result = json_decode($result);
			update_stock($result, 0, false);
		}
	}*/
	
	function object_to_uuid_name($objects) {
		$result = array();
		if ($objects)
			foreach ($objects as $obj) {
				$result[(string)$obj->uuid] = (string)$obj['name'];
			}
		return $result;
	}

	function get_priority_stores() {
		$save_stores = get_option('woosklad_save_stores');
		$priority = get_option('woosklad_priority');
		$stores = get_option('woosklad_stores');
		$priority_stores = array();
		
		$i = 0;
		foreach($save_stores as $key=>$value) {
			if (in_array($key, $stores)) {
				$priority_stores[$priority[$i]] = $key;
			}
			$i++;
		}
		ksort($priority_stores);
		return $priority_stores;
	}
?>