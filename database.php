<?php
	// fake database

	$tlogin = array(
		array('username' => 'gareth', 'password' => 'nunns', 'name' => 'Gareth Nunns', 'job' => 'Genius'),
		array('username' => 'donna', 'password' => 'paulsen', 'name' => 'Donna Paulsen', 'job' => 'Helpdesk Operator'),
		array('username' => 'jessica', 'password' => 'pearson', 'name' => 'Jessica Pearson', 'job' => 'Specialist'),
		array('username' => 'aloma', 'password' => 'wright', 'name' => 'Aloma Wright', 'job' => 'Helpdesk Operator'),
		array('username' => 'mike', 'password' => 'ross', 'name' => 'Mike Ross', 'job' => 'Specialist'),
	);

	$tproblems = array(
		array(
			'title' => 'Popup Ads Appearing',
			'calls' => array(
				array(
					'date' => '2016-11-07 18:11:59',
					'op' => 3,
					'caller' => 'Jaden Knight',
					'title' => 'Computer is slow',
					'text' => "Jaden said:

> I'm finding it very hard to work with all of the pop ups on my screen. They come up every time I do anything"
				)
			),
			'messages' => array (
				array(
					'date' => '2016-11-08 13:02:11',
					'op' => 4,
					'title' => 'Popup scan failed',
					'text' => 'Performed an anti pop up scan, nothing was found so did a system reinstall'
				)
			),
			'assign' => array(
				array(
					'date' => '2016-11-07 18:13:00',
					'by' => 3,
					'op' => 4
				)
			),
			'solution' => array(
				'date' => '2016-11-08 13:04:31',
				'op' => 4,
				'text' => 'System reinstall.'
			)
		),
		array(
			'title' => 'Computer is slow due to lack of space',
			'calls' => array(
				array(
					'date' => '2016-11-09 10:59:55',
					'op' => 3,
					'caller' => 'Sean Ling',
					'title' => 'Slow computer',
					'text' => 'Gave Sean the same method as the solution, which successfully resolved the problem.'
				),
				array(
					'date' => '2016-11-06 12:44:55',
					'op' => 2,
					'caller' => 'Davina Paterson',
					'title' => 'Required more free space',
					'text' => 'Checked whether she had plenty of free space on the hard drive holding her operating system. The hard drive was maxed out, performance suffers. Helped remove a few files and performance improved.'
				),
				array(
					'date' => '2016-11-04 12:34:22',
					'op' => 1,
					'caller' => 'Davina Paterson',
					'title' => 'Computer is slow',
					'text' => 'Davina said that her computer was taking ages and thought there was a hardware problem.'
				)
			),
			'messages' => array(),
			'assign' => array(
				array (
					'date' => '2016-11-04 12:35:22',
					'by' => 1,
					'op' => 2
				)
			),
			'solution' => array(
				'date' => '2016-11-06 12:46:12',
				'op' => 2,
				'text' => 'Remove files to improve performance'
			)
		)
	);
?>