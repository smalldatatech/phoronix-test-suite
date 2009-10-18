<?php

/*
	Phoronix Test Suite
	URLs: http://www.phoronix.com, http://www.phoronix-test-suite.com/
	Copyright (C) 2008 - 2009, Phoronix Media
	Copyright (C) 2008 - 2009, Michael Larabel

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

class pts_test_suite_details
{
	var $identifier;
	var $identifier_show_prefix;
	var $name;
	var $maintainer;
	var $description;
	var $version;
	var $type;
	var $test_type;
	var $unique_tests;
	var $only_partially_supported = false;
	var $not_supported = false;

	public function __construct($identifier)
	{
		$xml_parser = new pts_suite_tandem_XmlReader($identifier);
		$this->identifier = $identifier;
		$this->name = $xml_parser->getXMLValue(P_SUITE_TITLE);
		$this->test_type = $xml_parser->getXMLValue(P_SUITE_TYPE);
		$this->version = $xml_parser->getXMLValue(P_SUITE_VERSION);
		$this->type = $xml_parser->getXMLValue(P_SUITE_TYPE);
		$this->maintainer = $xml_parser->getXMLValue(P_SUITE_MAINTAINER);
		$this->description = $xml_parser->getXMLValue(P_SUITE_DESCRIPTION);
		$this->unique_tests = count(pts_contained_tests($identifier));

		$suite_support_code = pts_suite_supported($identifier);

		$this->identifier_show_prefix = " ";

		if($suite_support_code == 0)
		{
			$this->not_supported = true;
		}
		else if($suite_support_code == 1)
		{
			$this->identifier_show_prefix = "*";
			$this->only_partially_supported = true;
		}
	}
	public function partially_supported()
	{
		return $this->only_partially_supported;
	}
	public function not_supported()
	{
		return $this->not_supported;
	}
	public function get_description()
	{
		return $this->description;
	}
	public function get_maintainer()
	{
		$suite_maintainer = array_map("trim", explode("|", $this->maintainer));

		if(count($suite_maintainer) == 2)
		{
			$suite_maintainer = $suite_maintainer[0] . " <" . $suite_maintainer[1] . ">";
		}
		else
		{
			$suite_maintainer = $suite_maintainer[0];
		}

		return $suite_maintainer;
	}
	public function get_suite_type()
	{
		return $this->test_type;
	}
	public function info_string()
	{
		$str = "\n";

		$str .= "Suite Version: " . $this->version . "\n";
		$str .= "Maintainer: " . $this->get_maintainer() . "\n";
		$str .= "Suite Type: " . $this->get_suite_type() . "\n";
		$str .= "Unique Tests: " . $this->unique_tests . "\n";
		$str .= "Suite Description: " . $this->get_description() . "\n";
		$str .= "\n";

		$this->pts_print_format_tests($this->identifier, $str);

		return $str;
	}
	public function __toString()
	{
		$str = "";

		if(getenv("PTS_DEBUG"))
		{
			$str = sprintf("%-26ls - %-32ls %-4ls  %-12ls\n", $this->identifier_show_prefix . " " . $this->identifier, $this->name, $this->version, $this->type);
		}
		else if(!empty($this->name))
		{
			$str = sprintf("%-24ls - %-32ls [Type: %s]\n", $this->identifier_show_prefix . " " . $this->identifier, $this->name, $this->test_type);
		}

		return $str;
	}
	public function pts_print_format_tests($object, &$write_buffer, $steps = -1)
	{
		// Print out a text tree that shows the suites and tests within an object
		$steps++;
		if(pts_is_suite($object))
		{
			$xml_parser = new pts_suite_tandem_XmlReader($object);
			$tests_in_suite = array_unique($xml_parser->getXMLArrayValues(P_SUITE_TEST_NAME));

			if($steps > 0)
			{
				asort($tests_in_suite);
			}

			if($steps == 0)
			{
				$write_buffer .= $object . "\n";
			}
			else
			{
				$write_buffer .= str_repeat("  ", $steps) . "+ " . $object . "\n";
			}

			foreach($tests_in_suite as $test)
			{
				$write_buffer .= $this->pts_print_format_tests($test, $write_buffer, $steps);
			}
		}
		else
		{
			$write_buffer .= str_repeat("  ", $steps) . "* " . $object . "\n";
		}
	}
}

?>
