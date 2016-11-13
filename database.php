<?php
	// fake database
	$tlogin = array(
		array('username' => 'gareth', 'password' => 'nunns', 'name' => 'Gareth Nunns', 'job' => 'Genius'),//0
		array('username' => 'donna', 'password' => 'paulsen', 'name' => 'Donna Paulsen', 'job' => 'Helpdesk Operator'),//1
		array('username' => 'jessica', 'password' => 'pearson', 'name' => 'Jessica Pearson', 'job' => 'Specialist'),//2
		array('username' => 'aloma', 'password' => 'wright', 'name' => 'Aloma Wright', 'job' => 'Helpdesk Operator'),//3
		array('username' => 'mike', 'password' => 'ross', 'name' => 'Mike Ross', 'job' => 'Specialist'),//4
		array('username' => 'clara', 'password' => 'thompson', 'name' => 'Clara Thompson', 'job' => 'Specialist'),//5
		array('username' => 'nick', 'password' => 'gibson', 'name' => 'Nick Gibson', 'job' => 'Specialist'),//6
		array('username' => 'bert', 'password' => 'fletcher', 'name' => 'Bert Fletcher', 'job' => 'Specialist')//7
	);
	
	$tproblems = array(
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
			'messages' => array(),
			'assign' => array(
				array(
					'date' => '2016-10-04 04:25:23',
					'by' => 1,
					'op' => 5
				),
				array(
					'date' => '2016-10-05 09:00:21',
					'by' => 5,
					'op' => 6
				)
			),
			'solution' => array(
				'date' => '2016-10-06 10:15:32',
				'op' => 6,
				'text' => 'A stronger firewall researched and costed and passed to Mr. Trump. Costs are high so probably will never be implemented'
			)
		),
		array(
			'id' => 327,
			'title' => 'Tendency to eject CDs early',
			'calls' => array(
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
			),
			'messages' => array(),
			'assign' => array(
				array(
					'date' => '2016-10-05 09:00:21',
					'by' => 3,
					'op' => 7
				)
			),
			'solution' => array(
				'date' => '2016-10-15 10:15:33',
				'op' => 3,
				'text' => 'New CD ready installed, as it was a new series CD reader, the problem will not occur again.'
			)
		),
		array(
			'id' => 326,
			'title' => 'Email Hacked',
			'calls' => array(
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
			),
			'messages' => array(),
			'assign' => array(
				array(
					'date' => '2016-10-03 09:20:21',
					'by' => 1,
					'op' => 6
				)
			),
			'solution' => array(
				'date' => '2016-10-04 21:23:13',
				'op' => 6,
				'text' => 'There was no problem in the first place, but gave an extensice lecture about using the wrong email server.'
			)
		),
		array(
			'id' => 325,
			'title' => 'Laptop Running Slow',
			'calls' => array(
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
			),
			'messages' => array(),
			'assign' => array(
				array(
					'date' => '2016-10-03 09:20:21',
					'by' => 1,
					'op' => 6
				)
			),
			'solution' => array(
				'date' => '2016-10-05 16:46:22',
				'op' => 6,
				'text' => 'Use Windows CleanUp to tidy up disc space and unused temp files.'
			)
		),
		array(
			'id' => 125,
			'title' => 'Popup Ads Appearing',
			'calls' => array(
				array(
					'date' => '2016-11-07 18:11:59',
					'op' => 3,
					'caller' => 'Jaden Knight',
					'title' => 'Computer is slow',
					'text' => "Jaden said:> I'm finding it very hard to work with all of the pop ups on my screen. They come up every time I do anything"
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
			'id' => 112,
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
		),
		array(
			'id' => 109,
			'title' => 'Unable to Comment',
			'calls' => array(
				array(
					'date' => '2016-11-02 13:22:45',
					'op' => 3,
					'caller' => 'Danny Jaine',
					'title' => 'Cannot find the comment button',
					'text' => 'I need to make some comments on Google Docs so my colleague can know what he needs to do next in our current project, but I cannot find the button.'
				),
				array(
					'date' => '2016-11-04 15:21:11',
					'op' => 3,
					'caller' => 'Mike Ross',
					'title' => 'Called Danny Directly',
					'text' => 'I called him directly to deliver the bad news; none of our specialists can solve this problem, we may have to go to an external company.'
				),
				array(
					'date' => '2016-11-10 05:11:11',
					'op' => 3,
					'caller' => 'Mike Ross',
					'title' => 'Complaint',
					'text' => 'It has been 6 days now since I told Danny the problem was unfixable, and since then I have been getting mysterious calls and texts from unknown numbers asking about how to do it, I know it is Danny and it is starting to worry me, I do not feel safe in my own home anymore.'
				)
			),
			'messages' => array(),
			'assign' => array(
				array(
					'date' => '2016-11-03 12:14:41',
					'by' => 3,
					'op' => 4
				)
			)
		),
		array(
			'id' => 105,
			'title' => 'Computer Overheating',
			'calls' => array(
				array(
					'date' => '2016-11-03 11:11:11',
					'op' => 1,
					'caller' => 'Olaf Snowman',
					'title' => 'Computer getting worringly hot',
					'text' => 'Olaf rang and said his computer was getting hot during long periods of use and was worried it might even start to melt. Decided to refer him to Nick.'
				)
			),
			'messages' => array(),
			'assign' => array(
				array(
					'date' => '2016-11-03 12:12:12',
					'by' => 1,
					'op' => 6
				)
			)
		),
		array(
			'id' => 145,
			'title' => 'Fan rattling againt PC case',
			'calls' => array(
				array(
					'date' => '2016-11-10 16:12:11',
					'op' => 1,
					'caller' => 'Clive Gronk',
					'title' => 'Too much noise!',
					'text' => 'A rather aggrevated Clive rang me and said his "This f***ing fan was rubbing against the side of his PCs case and has been driving him mad all day. Thought I should probably give it to mike."'
				),
				array(
					'date' => '2016-11-11 10:13:01',
					'op' => 1,
					'caller' => 'Mike Ross',
					'title' => 'LOL',
					'text' => 'Lets just leave it and see how long it takes him before he quits. I give him 2 more days'
				)
			),
			'messages' => array(),
			'assign' => array(
				array(
					'date' => '2016-11-11 17:00:12',
					'by' => 1,
					'op' => 4
				)
			)
		)
	);

	//add GMT to dates for internationalisation
	foreach ($tproblems as $pkey => $problem) // look at each problem individually
		foreach ($problem as $rskey => $responses) // look at each element in the array - calls/messages/assigns
			if(is_array($responses))
				if($responses['date'])
					$tproblems[$pkey][$rskey]['date'] .= ' GMT';
				else
					foreach ($responses as $rkey => $response) // loop through calls/messages/assigns
						if($response['date'])
							$tproblems[$pkey][$rskey][$rkey]['date'] .= ' GMT';


	$tHard = array( // put them in any order
		160 => array(
			'name' => 'Mac Pro 6-Core',
			'type' => 'Desktop'
		),
		112 => array(
			'name' => 'Dell Inspiron 15',
			'type' => 'Laptop'
		),
		130 => array(
			'name' => 'iPhone 5',
			'type' => 'Phone'
		)
	);

	$tSoft = array( // replace the existing and then delete these comments
		260 => array(
			'name' => 'Sublime Text',
			'license' => 'license123',
			'os' => 'Ubuntu 16.10'
		),
		212 => array(
			'name' => 'MAMP Pro',
			'license' => '9939-3209-2323',
			'os' => 'Mac OSX'
		),
		230 => array(
			'name' => 'Microsoft Word',
			'license' => 'clippy-rip',
			'os' => 'Windows 10'
		)
	);

	//settings
	$tLanguages = array( // sourced using https://r12a.github.io/apps/subtags/
		"en" => "English",
		"de" => "German",
		"ar" => "Arabic",
		"cmn" => "Chinese"
	);

	$tFormats = array( // find out more: http://php.net/manual/en/function.date.php
		'd/m/yy hh:mm' => 'j/n/y \a\t H:i',
		'hh:mm d mmm yy' => 'H:i \o\n j M y',
		'yyyy-mm-dd hh:mm' => 'Y-m-d H:i'
	);
?>
