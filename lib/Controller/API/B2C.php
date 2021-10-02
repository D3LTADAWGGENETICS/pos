<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: GPL-3.0-only
 *
 */

namespace App\Controller\API;

class B2C extends \App\Controller\API\Base
{
	function __invoke($REQ, $RES, $ARG)
	{
		$this->auth_parse();

		$source_data = $this->parseJSON();
		if (empty($source_data['license']['id'])) {
			return $this->failure($RES, 'Invalid License ID [A7B-016]', 400);
		}

		$dbc = _dbc($this->Company['dsn']);

		$b2c = new \App\B2C\Sale($dbc);
		$b2c['license_id'] = $source_data['license']['id'];
		$b2c->save();

		// Format Output
		$ret = $b2c->toArray();
		$ret['license'] = [
			'id' => $ret['license_id']
		];
		unset($ret['license_id']);

		__exit_json([
			'data' => $ret
			, 'meta' => []
		]);

	}
}
