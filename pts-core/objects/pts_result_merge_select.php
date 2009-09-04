<?php

/*
	Phoronix Test Suite
	URLs: http://www.phoronix.com, http://www.phoronix-test-suite.com/
	Copyright (C) 2009, Phoronix Media
	Copyright (C) 2009, Michael Larabel

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

class pts_result_merge_select
{
	var $result_file;
	var $selected_identifiers;
	var $rename_identifier;

	public function __construct($result_file, $selected_identifiers = null)
	{
		$this->result_file = $result_file;
		$this->selected_identifiers = ($selected_identifiers != null ? pts_to_array($selected_identifiers) : null);
		$this->rename_identifier = null;
	}
	public function get_result_file()
	{
		return $this->result_file;
	}
	public function get_selected_identifiers()
	{
		return $this->selected_identifiers;
	}
	public function __toString()
	{
		return $this->get_result_file() . ":" . $this->get_selected_identifiers();
	}
	public function rename_identifier($new_name)
	{
		$this->rename_identifier = (count($this->selected_identifiers) == 1 ? $new_name : null);
	}
	public function get_rename_identifier()
	{
		return $this->rename_identifier;
	}
}

?>
