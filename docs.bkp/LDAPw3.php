<?php
/* Core */
function employee_get_title (&$employee) {
	$employee['title'] = $employee['ismanager'] == 'Y' ? 'Manager' : 'Employee';
	$employee['manager']['title'] = $employee['manager']['ismanager'] == 'Y' ? 'Manager' : 'Employee';

	$manager = $employee['dn'];
	$ismanager = $employee['ismanager'];
	$employee['line'] = 0;
	while ($ismanager == 'Y') {
		foreach(bluepages_search('(manager='.$manager.')') as $data) {
			if ($ismanager = $data['ismanager'] == 'Y') {
				$manager = $data['dn'];
				break;
			}
		}
		$employee['line']++;
	}
	$employee['manager']['line'] = $employee['line'] + 1;
	
	$regexp = '/^.*?(([^\s]+ )?Manager|Director|Executive).*?$/';
	
	if ($employee['line'] && preg_match($regexp, $employee['jobresponsibilities']))
		$employee['title'] = preg_replace($regexp, '$1', $employee['jobresponsibilities']);
	#unset($employee['jobresponsibilities']);
	
	if ($employee['manager']['ismanager'] == 'Y' && $employee['manager']['line'] >= 3)
		$employee['manager']['title'] = 'Executive';
	/*
	if ($employee['manager']['ismanager'] == 'Y') {
	 	if ($employee['manager']['line'] >= 3)
			$employee['manager']['title'] = 'Executive';
		else {
			$suffix = array(1 => 'st', 2 => 'nd', 3 => 'rd');
			$employee['manager']['title'] = $employee['manager']['line'].$suffix[$employee['manager']['line']].' Line Manager';
		}
	}
	*/
	
return($employee);
}

function ldap_result_format ($info) {
	$employee = array(
		'dn' => $info['dn'],
		'uid' => str_replace('uid=', '', strtok($info['uid'][0], ',')),
		'cn' => $info['cn'][0],
		'mail' => $info['mail'][0],
		'phone' => $info['telephonenumber'][0],
		'ibmserialnumber' => $info['ibmserialnumber'][0],
		'employeecountrycode' => $info['employeecountrycode'][0],
		'country_code' => strtolower($info['hrcountrycode'][0]),
		'notesid' => $info['notesid'][0],
		'manager' => user_info(str_replace('uid=', '', strtok($info['manager'][0], ',')), 'uid'),
		'jobresponsibilities' => $info['jobresponsibilities'][0],
		'ismanager' => $info['ismanager'][0],
		'dept' => $info['dept'][0],
	);
return(employee_get_title($employee));
}

# bool return = employee_in_group ( string group, string employee, [string depth] )
# returns true if $employee is in the bluegroup $group. $depth defaults to 2
# set $depth to 0 to not check sub groups. $employee can be an DN (faster) or an
# email address.
function bluegroups_subgroups ($group) {
	# the group filter
	if ( ! is_array($group) )
		$group = array($group);

	$filter = '';
	foreach ($group as $cn)
		$filter .= '(cn='.$cn.')';

	if (sizeof($group) > 1)
		$filter = '(|'.$filter.')';

	# setup the connection resource
	if (!$ds = _ldap_connect())
		return(false);

	$filter = "(&(objectclass=groupofuniquenames)(uniquegroup=*)$filter)";
	$basedn = 'ou=memberlist,ou=ibmgroups,o=ibm.com';

	# connect, bind, and search
	if (!$sr = @ldap_search($ds, $basedn, $filter, array('uniquegroup')))
		return(false);

	# check if sub groups are present
	if(@ldap_count_entries($ds, $sr) == 0)
		return(false);

	# build a new filter from the sub-groups found
	$subgroup = array();
	for ($entry = ldap_first_entry($ds, $sr); $entry != false; $entry = ldap_next_entry($ds, $entry)) {
		$val = ldap_get_values($ds, $entry, 'uniquegroup');
		for ($i = 0; $i < $val['count']; $i++) {
			list($cn,) = ldap_explode_dn($val[$i], 1);
			$subgroup[] = stripslashes($cn);
		}
	}
return(array_unique($subgroup));
}

# bool result = employee_in_gorup ( mixed group, string employee [, int depth] )
# returns true or false if $employee is one of the groups in $group.
# $group can be an array of groups or a string. $employee can be a DN or
# an email address.
function employee_in_group ($group, $employee, $depth = 2) {
	if (!is_array($group))
		$group = array($group);
	if (strpos($employee, '@') == true) {
		# lookup the DN from an email address
		if (! $record = bluepages_search("(mail=$employee)") )
			return(false);
		$user_dn = key($record);
	} elseif (strpos($employee, '=') == true) {
		# use the DN given
		$user_dn = $employee;
	} else {
		# passed something we don't know how to handle
		return(false);
	}

	# setup ldap connection resource
	$basedn = 'ou=memberlist,ou=ibmgroups,o=ibm.com';
	if ( ! $ds = _ldap_connect() )
		return(false);

	$result = false;
	while ($depth >= 0) {

		# filter to look for $dn in $group list
		$filter = '';
		foreach ($group as $cn)
			$filter .= '(cn='.$cn.')';

		if (sizeof($group) > 1)
			$filter = '(|'.$filter.')';
		$filter = "(&(objectclass=groupofuniquenames)(uniquemember=$user_dn)$filter)";

		# connect, bind and search for $dn in $group
		if (!$sr = @ldap_search($ds, $basedn, $filter, array('cn')))
			break;

		# bail out if $dn is found in this $group list
		if (@ldap_count_entries($ds, $sr) > 0) {
			$result = true;
			break;
		}

		# bail out if there are no sub-groups
		if (!$group = bluegroups_subgroups($group))
			break;
		$depth--;
	}
return($result);
}

# specialized ldap search for returning records from a dn
# array result = employees_by_dn ( mixed dn, [array attr] )
# $dn can be a single DN or an array of DNs. $attr is
# an array of attributes from bluepages to return for each
# employee.
# note: seems slower than processing by UID filters
function employee_by_dn ($dn, $attr = null) {
	if ( ! is_array($dn) )
		$dn = array($dn);
	$attr = $attr ? $attr : array('cn', 'mail', 'uid');
	$filter = '(objectclass=*)';

	# setup ldap connection
	if ( ! $ds = _ldap_connect() )
		return(false);

	# connect, bind and do a parallel search
	$conn = array_fill(0, sizeof($dn), $ds);
	$search_result = ldap_read($conn, $dn, $filter, $attr);

	# process each of the search results
	$result = array();
	foreach ($search_result as $sr) {
		# check to see if we have hits for this result
		if (@ldap_count_entries($ds, $sr) == 0)
			continue;

		# get the values of each entry found
		for ($entry = @ldap_first_entry($ds, $sr); $entry != false; $entry = @ldap_next_entry($ds, $entry)) {
			# results are a dn keyed hash
			$dn = @ldap_get_dn($ds, $entry);
			$result[$dn]['dn'] = $dn;

			# get each attr, missing attrs are stored as null values
			foreach ($attr as $a) {
				$val = @ldap_get_values($ds, $entry, $a);
				$result[$dn][$a] = $val ? $val[0] : null;
			}
		}
	}
return($result);
}

# return metadata about a bluegroup
# array result = bluegroup_metadata ( string group, array attr )
# $group is the name of the group to find and $attr is an array
# of bluepages attributes for the group owner and/or group admin
function bluegroup_metadata ($group, $attr = null) {
	# build the search filter, basedn, and attr list
	$filter = "(cn=$group)";
	$basedn = 'ou=metadata,ou=ibmgroups,o=ibm.com';
	$bg_attr = array('cn', 'owner', 'admin', 'expirationdate', 'description', 'viewaccess');

	# setup ldap connection
	if ( ! is_resource($ds) ) {
		if ( ! $ds = _ldap_connect() )
			return(false);
	}

	# connect, bind, and search for groups
	if (!$sr = @ldap_search($ds, $basedn, $filter, $bg_attr))
		return(false);

	# make sure we got one group back
	if ( @ldap_count_entries($ds, $sr) == 0 )
		return(false);

	# extract the meta data
	if ( ! $entry = @ldap_first_entry($ds, $sr) )
		return(false);

	$result = array();
	foreach ($bg_attr as $a) {
		$val = @ldap_get_values($ds, $entry, $a);
		if ($a == 'owner' || $a == 'admin') {
			$result[$a] = $val ? $val : array();
			unset($result[$a]['count']);
		} else {
			$result[$a] = $val ? $val[0] : null;
		}
	}

	# get employee info for admin and owner
	foreach (array('admin', 'owner') as $a) {
		if ( ! $result[$a] )
			continue;
		$filter = array_map('_uid_filter', $result[$a]);
		if ($employees = bluepages_search($filter, $attr))
			$result[$a] = $employees;
	}

	# change expirationdate from YYYYMMDD to a timestamp
	if ($result['expirationdate'])
		$result['expirationdate'] = strtotime($result['expirationdate']);
return($result);
}

# return an array of exmployees for the group and sub-groups
# array result = bluegroup_employees ( string group, [array attr, [int maxdepth]] )
# $group is the group to get the members list for. if $maxdepth is
# set to 0 then no sub-groups are checked. $attr is an array of bluepages
# attributes to return for each employee.

function bluegroup_employees ($group, $attr = null, $maxdepth = 2) {
	# get the group members
	if ( ! $members = bluegroup_members($group, $maxdepth) )
		return(false);

	# build the search filter
	$members = array_map('_uid_filter', $members);
	$filters = array_chunk($members, "200");
	for ($i = 0; $i < sizeof($filters); $i++)
			$filters[$i] = '(|'.implode('', $filters[$i]).')';
return(bluepages_search($filters, $attr));
}

# return an array of DNs for the group and sub-groups
# array result = bluegroup_members ( string group, [int maxdepth] )
# $group is the group to get the members list for. if $maxdepth is
# set to 0 then no sub-groups are checked
function bluegroup_members ($group, $maxdepth = 2, $depth = 0, $ds = false) {
	# setup basedn and attr array
	$basedn = 'ou=memberlist,ou=ibmgroups,o=ibm.com';
	$attr = array('uniquemember', 'uniquegroup');

	# build a search filter
	if ( ! is_array($group) )
		$group = array($group);

	$filter = '(|';
	foreach ($group as $cn)
		$filter .= '(cn='.$cn.')';
	$filter .= ')';

	# setup ldap connection
	if ( ! is_resource($ds) ) {
		if ( ! $ds = _ldap_connect() )
			return(false);
	}

	# connect, bind, and search for groups
	if (!$sr = @ldap_search($ds, $basedn, $filter, $attr))
		return(false);

	# make sure we got one group back
	if ( @ldap_count_entries($ds, $sr) == 0 )
		return(false);

	# walk the entries (groups) found
	$members = array();
	for ($entry = @ldap_first_entry($ds, $sr); $entry != false; $entry = @ldap_next_entry($ds, $entry)) {

		# get the members of this group
		if ($uniquemember = @ldap_get_values($ds, $entry, 'uniquemember')) {
			unset($uniquemember['count']);
			$members = array_merge($uniquemember, $members);
		}

		# check sub groups for additional members
		if ( ($depth + 1 < $maxdepth)  && ($uniquegroup = @ldap_get_values($ds, $entry, 'uniquegroup')) ) {
			# build a filter of sub-groups
			$sub_cn = array();
			for ($i = 0; $i < $uniquegroup['count']; $i++) {
				list($cn,) = @ldap_explode_dn($uniquegroup[$i], 1);
				$sub_cn[] = stripslashes($cn);
			}
			# get the members of sub groups
			if ($result = bluegroup_members($sub_cn, $maxdepth, $depth + 1, $ds))
				$members = array_merge($members, $result);
		}
	}
return($depth == 0 ? array_unique($members) : $members);
}

# return an array of groups $employee is in.  $employee can be a DN or
# an email address.
function employee_bluegroups ($employee) {
	if (strpos($employee, '@') == true) {
		# lookup the DN from an email address
		if (! $record = bluepages_search("(mail=$employee)") )
			return(false);
		$user_dn = key($record);
	} elseif (strpos($employee, '=') == true) {
		# use the DN given
		$user_dn = $employee;
	} else {
		# passed something we don't know how to handle
		return(false);
	}

	# setup ldap connection
	if ( ! $ds = _ldap_connect() ) return(false);
	$filter = "(uniquemember=$user_dn)";
	$basedn = 'ou=ibmgroups,o=ibm.com';

	# connect, bind, and search
	if (!$sr = @ldap_search($ds, $basedn, $filter, array('cn')))
		return(false);

	# bail out if there aren't any groups (unlikely)
	if (@ldap_count_entries($ds, $sr) == 0)
		return(false);

	# build an array of groups found
	$groups = array();
	for ($entry = ldap_first_entry($ds, $sr); $entry != false; $entry = ldap_next_entry($ds, $entry)) {
		$val = ldap_get_values($ds, $entry, 'cn');
		array_push($groups, $val[0]);
	}
return($groups);
}

# search bluepages using an ldap filter
# array bluepages_search ( mixed filter, array attr)
# $filter can be a string or an array of strings to search on
# $attr is an array of ldap attributes to return for each record
# returns false or an array of results keyed by DN
# WARNING: only the first value of an attribute is returned
function bluepages_search ($filter, $attr = null, $key_attr = 'dn') {
	# setup filter array, attr list, and base dn
	if ( ! is_array($filter) )
		$filter = array($filter);
	$basedn = 'ou=bluepages,o=ibm.com';
	$attr = $attr ? $attr : array('uid', 'cn', 'mail', 'ibmserialnumber', 'employeecountrycode', 'notesid', 'ismanager', 'manager');
	# make sure $key is in attr array or we cannot use it
	if ( $key_attr != 'dn' && ! in_array($key_attr, $attr) )
		$attr[] = $key_attr;

	# setup ldap connection
	if ( ! $ds = _ldap_connect() )
		return(false);

	# connect, bind and do a parallel search
	$conn = array_fill(0, sizeof($filter), $ds);
	$search_result = ldap_search($conn, $basedn, $filter, $attr);

	# process each of the search results
	$result = array();
	foreach ($search_result as $sr) {

		# check to see if we have hits for this result
		if (@ldap_count_entries($ds, $sr) == 0) continue;

		# get the values of each entry found
		for ($entry = @ldap_first_entry($ds, $sr); $entry != false; $entry = @ldap_next_entry($ds, $entry)) {

			# get the dn of this entry, always
			$dn = @ldap_get_dn($ds, $entry);

			# get the hash key
			if ($key_attr && $key_attr != 'dn') {
				$key_val = @ldap_get_values($ds, $entry, $key_attr);
				$key_val = $key_val ? $key_val[0] : $dn;
			} else {
				$key_val = $dn;

			}

			# put the dn into the result hash
			$result[$key_val]['dn'] = $dn;

			# get each attr, missing attrs are stored as null values
			foreach ($attr as $a) {
				$val = @ldap_get_values($ds, $entry, $a);
				$result[$key_val][$a] = $val ? $val[0] : null;
			}
		}
	}
return($result);
}

# array ldap_key_by ( array i_array, string key )
# $i_array is an array formated results from bluepages_search()
# $key is a string of the new key to use
# WARNING: any record that does not contain $key is silently dropped
# WARNING: records with duplicate $keys are replaced by the last occrance of $key
function ldap_key_by ($i_array, $key) {
	$result = array();
	foreach ($i_array as $record) {
		if (!isset($record[$key]))
			continue;
		$result[$record[$key]] = $record;
	}
return($result);
}

# void ldap_sort_by ( array results, string attribute )
# given an attr restore the results returned from
# bluepages_search() or bluegroup_employees()
# this operates directly on the results $array passed

function ldap_sort_by (&$array, $attr) {
	$sort_func = create_function('$a,$b', 'return strcasecmp($a["'.$attr.'"], $b["'.$attr.'"]);' );
	uasort($array, $sort_func);
}

# string dn2uid ( string dn )
# $dn should be an ldap DN from bluepages or bluegroups
# the value of the uid of the DN is returned
function ibm_uid ( $dn ) { return dn2uid($dn); }

function dn2uid ( $dn ) {
	list($uid,) = ldap_explode_dn($dn, 1);
return(stripslashes($uid));
}

# emulate old user_info() function for compatablity
function user_info ($value, $key = "mail") {
	# search for the employee using site wide attribs
	if ( ! $result = bluepages_search("($key=$value)") )
		return(false);
# return just the first entry like old user_info() did
return($result[key($result)]);
}

# internal funciton used to connect to ED server
function _ldap_connect ($host = "ldap://bluepages.ibm.com/") {
	# use a previously opened connection
	if (isset($GLOBALS['ibm_ldap_ds']) && is_resource($GLOBALS['ibm_ldap_ds']))
		return($GLOBALS['ibm_ldap_ds']);

	# setup the ldap resource
	if (!$ds = @ldap_connect($host)) {
		$GLOBALS['ibm_ldap_errno'] = ldap_errno($ds);
	return(false);
	}

	# use ldap protocol v3
	if (!@ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3)) {
		$GLOBALS['ibm_ldap_errno'] = ldap_errno($ds);
	return(false);
	}

	$GLOBALS['ibm_ldap_ds'] = $ds;
return $ds;
}

# returns an array of filters as (|($attr=$val)($attr=$val))
# $list = an array of values
# $attr = the ldap attribute to map to
# $size = the number of items in each filter
function make_ldap_filter($list, $attr, $size = '200') {
	$map_func = create_function('$val', 'return "(' . $attr . '=$val)";');
	$list = array_map($map_func, $list);
	$filter = array_chunk($list, $size);
	for ($i = 0; $i < sizeof($filter); $i++)
		$filter[$i] = '(|'.implode('', $filter[$i]).')';
return($filter);
}

# internal function used to for constructing filters
function _uid_filter ($dn) { return('(uid='.dn2uid($dn).')'); }

/* Application */
?>
