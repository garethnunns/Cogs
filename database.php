<?php
	// fake database
	$tlogin = array(
		array('username' => 'gareth', 'password' => 'nunns', 'name' => 'Gareth Nunns', 'job' => 'Genius - he likes to think so.'),
		array('username' => 'donna', 'password' => 'paulsen', 'name' => 'Donna Paulsen', 'job' => 'Helpdesk Operator'),
		array('username' => 'jessica', 'password' => 'pearson', 'name' => 'Jessica Pearson', 'job' => 'Specialist'),
		array('username' => 'aloma', 'password' => 'wright', 'name' => 'Aloma Wright', 'job' => 'Helpdesk Operator'),
		array('username' => 'mike', 'password' => 'ross', 'name' => 'Mike Ross', 'job' => 'Specialist'),
		array('username' => 'clara', 'password' => 'thompson', 'name' => 'Clara Thompson', 'job' => 'Specialist'),
		array('username' => 'nick', 'password' => 'gibson', 'name' => 'Nick Gibson', 'job' => 'Specialist'),
		array('username' => 'bert', 'password' => 'fletcher', 'name' => 'Bert Fletcher', 'job' => 'Specialist')
	);
	$tproblems = array( // put in all of the problems, with the highest ID at the top
		array(
			'id' => 925,
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
			'id' => 912,
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
		array(
			'id' => 328,
			'title' => 'Issue with Firewall',
			'calls' => array(
					array(
					'date' => '2016-10-05 09:15:23',
					'op' => 1,
					'caller' => 'Clara Thompson',
					'title' => 'Change of Specialist',
					'text' => 'Clara rang to inform me of specialist change'
				),
				array(
					'date' => '2016-10-04 04:15:55',
					'op' => 1,
					'caller' => 'Donald Trump',
					'title' => 'Firewall not strong enough',
					'text' => 'Concerned about the strength of the firewall. In particular wants to keep out illegal messages from Mexico.'
				),
			),
			'assign' => array(
				array(
					'date' => '2016-10-04 04:25:23',
					'by' => 1,
					'op' => 5
				)

			),
			'assign' => array(
				array(
					'date' => '2016-10-05 09:00:21'
					'by' => 5,
					'op' => 6
				)
			),
			'solution' => array(
				'date' => '2016-10-06 10:15:32',
				'op' => 6,
				'text' => 'A stronger firewall researched and costed and passed to Mr. Trump. Costs are high so probably will never be implemented'
			)
		)
		array(
			'id' => 327,
			'title' => 'Tendency to eject CDs early',
			'calls' => array(
				array(
					array(
						'date' => '2016-10-04 09:20:03',
						'op' => 3,
						'caller' => 'Ed Balls',
						'title' => 'Disk drive keeps ejecting',
						'text' => 'Ed said the CDs would keep ejecting randomly'
					),
					array(
						'date' => '2016-10-05 16:56:33',
						'op' => 3,
						'caller' => 'Bert Fletcher',
						'title' => 'Loading Funny Joke...',
						'text' => 'Bert called to add that though the user said the CD was not strictly necessary he was concerned it could lead him on a right “song and dance” if he was ejected too soon. Therefore new CD reader ordered'
					),
					array(
						'date' => '2016-10-15 10:15:33',
						'op' => 3,
						'caller' => 'Bert Fletcher',
						'title' => 'Notice of fix',
						'text' => 'Bert called to inform me that the problem has been solved'
					)
				)
			),
			'assign' => array(
				array(
					'date' => '2016-10-05 09:00:21'
					'by' => 3,
					'op' => 7
				)
			),
			'solution' => array(
				'date' => '2016-10-15 10:15:33',
				'op' => 3,
				'text' => 'New CD ready installed, as it was a new series CD reader, the problem will not occur again.'
			)
		)
		array(
			'id' => 326,
			'title' => 'Email Hacked',
			'calls' => array(
				array(
					array(
						'date' => '2016-10-03 09:15:03',
						'op' => 1,
						'caller' => 'Hilary Clinton',
						'title' => 'Email broken into',
						'text' => 'Told me that her email had been hacked into whilst using an external server'
					),
					array(
						'date' => '2016-10-03 09:15:03',
						'op' => 1,
						'caller' => 'Clara Thompson',
						'title' => 'Checked Anti-Virus',
						'text' => 'Clara called. Anti-virus software checks carried out, etc. Computer appears to be uninfected, but can’t tell if any data has been stolen. User given an extensive lecture on the dangers of using the wrong email server.'
					)
				)
			)
			'assign' => array(
				array(
					'date' => '2016-10-03 09:20:21'
					'by' => 1,
					'op' => 6
				)
			)
			'solution' => array(
				'date' => '2016-10-04 21:23:13',
				'op' => 6,
				'text' => 'There was no problem in the first place, but gave an extensice lecture about using the wrong email server.'
			)
		)
		array(
			'id' => 325,
			'title' => 'Laptop Running Slow',
			'calls' => array(
				array(
					array(
						'date' => '2016-10-03 09:00:23',
						'op' => 1,
						'caller' => 'George Formby',
						'title' => 'Laptop not running effiecently',
						'text' => 'My laptop is running very slowly, my Youtube videos are not loading fast enough. I suggested a reboot'
					),
					array(
						'date' => '2016-10-03 09:30:23',
						'op' => 1,
						'caller' => 'George Formby',
						'title' => 'Tried rebooting',
						'text' => 'Tried rebooting but still no improvement in performance.'
					),
					array(
						'date' => '2016-10-05 04:00:23',
						'op' => 1,
						'caller' => 'Clara Thompson',
						'title' => 'Windows Cleanup',
						'text' => 'Clara called to say Windows CleanUp used to tidy up disc space and delete unused temporary files. User can access Windows more easily and speedily.'
					)
				)
			)
			'assign' => array(
				array(
					'date' => '2016-10-03 09:20:21'
					'by' => 1,
					'op' => 6
				)
			)
			'solution' => array(
				'date' => '2016-10-05 16:46:22',
				'op' => 6,
				'text' => 'Use Windows CleanUp to tidy up disc space and unused temp files.'
			)
		)
	);
?>
