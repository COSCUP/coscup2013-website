<?php
include_once ("deploy.php");
include_once ("markdown-without-markup.php");

date_default_timezone_set('Asia/Taipei');
setlocale (LC_ALL, "en_US.UTF-8");

function linkify($text){
	$text = preg_replace('/(?<!\[|\<|\]\()(https?:\/\/[a-zA-Z0-9\/\&\$\#\+\;\:\?\@\%\.\-\=\_]+)/', '[$0]($0)', $text);
	// FIXME: find better way to regexp this
	return $text;
}

function get_program_list_from_gdoc() {

	$handle = @fopen('https://spreadsheets.google.com/pub?key=' . PROGRAM_LIST_KEY . '&range=A2%3AL999&output=csv', 'r');

	if (!$handle)
	{
		return FALSE; // failed
	}

	$program_list = array();

	// name, from, to, room, type, speaker, speakerTitle, desc, language, slide, youtube
	while (($program = fgetcsv($handle)) !== FALSE)
	{

		$program_obj = array(
			'name' => $program[0],

			// use strtotime to convert to unix timestamp.
			'from' => strtotime($program[1]),
			'to' => strtotime($program[2]),

			'room' => intval($program[3])
		);

		if (trim($program[0]) === '') continue;

		if (trim($program[4]) !== '')
		{
			$program_obj['type'] = intval($program[4]);
		}

		if (trim($program[5]))
		{
			$program_obj['speaker'] = $program[5];

			if (trim($program[6]))
			{
				$program_obj['speakerTitle'] = $program[6];
			}
			if (trim($program[7]))
			{
				$program_obj['bio'] = Markdown_Without_Markup(linkify($program[7]));
			}
		}

		if (trim($program[8]))
		{
			$program_obj['abstract'] = Markdown_Without_Markup(linkify($program[8]));
		}

		if (trim($program[9]))
		{
			$program_obj['lang'] = $program[9];
		}

		if (trim($program[10]))
		{
			$program_obj['slide'] = trim($program[10]);
		}

		if (trim($program[11]))
		{
			$program_obj['youtube'] = array();
			foreach (explode("\n", trim($program[11])) as $url)
			{
				if (trim($url))
				{
					$program_obj['youtube'][] = preg_replace('/^.+v=([^"&?\/ ]{11}).*$/', '$1', trim($url)); // only get the ID
				}
			}
		}

		if (
			isset($program_obj['type']) && $program_obj['type'] === 0
			&& $program_obj['room'] <= 0
			&& !isset($program_obj['speaker'])
		)
		{
			$program_obj['isBreak'] = true;
		}
		else
		{
			$program_obj['isBreak'] = false;
		}

		$program_list[] = $program_obj;
	}

	fclose($handle);

	return $program_list;
}

function get_program_types_from_gdoc() {

	// TODO: constant gid written in uri
	$handle = @fopen('https://spreadsheets.google.com/pub?key=' . PROGRAM_LIST_KEY . '&gid=3&range=A2%3AB999&output=csv', 'r');

	if (!$handle)
	{
		return FALSE; // failed
	}

	$type_list = array();

	// id, name
	while (($type = fgetcsv($handle)) !== FALSE)
	{
		$type_list[intval($type[0])] = $type[1];
	}

	fclose($handle);

	return $type_list;
}

function get_program_rooms_from_gdoc() {

	// TODO: constant gid written in uri
	$handle = @fopen('https://spreadsheets.google.com/pub?key=' . PROGRAM_LIST_KEY . '&gid=4&range=A2%3AD999&output=csv', 'r');

	if (!$handle)
	{
		return FALSE; // failed
	}

	$room_list = array();

	// id, name, nameEn, nameZhCn
	while (($room = fgetcsv($handle)) !== FALSE)
	{
		$room_list[intval($room[0])] = array(
			'zh-tw' => $room[1],
			'en' => $room[2],
			'zh-cn' => $room[3]
		);
	}

	fclose($handle);

	return $room_list;
}

function get_program_list_html(&$program_list, &$type_list, &$room_list, $lang = 'zh-tw') {

	$l10n = array(
		'en' => array(
			'time' => 'Time',
			'day_1' => 'Day 1',
			'day_2' => 'Day 2'
		),
		'zh-tw' => array(
			'time' => '時間',
			'day_1' => '第一天',
			'day_2' => '第二天'
		),
		'zh-cn' => array(
			'time' => '时间',
			'day_1' => '第一天',
			'day_2' => '第二天'
		)
	);

	$name_replace = array(
		'Check In' => array(
			'zh-tw' => '報到',
			'zh-cn' => '报到'
		),
		'Tea Break' => array(
			'zh-tw' => '休息',
			'zh-cn' => '休息'
		),
		'Lunch' => array(
			'zh-tw' => '午餐',
			'zh-cn' => '午餐'
		)
	);

	// constructing data structures

	$structure = array();
	$time_structure = array();


	foreach ($program_list as $id => &$program)
	{

		$program['id'] = $id;

		if(!isset($structure[$program['from']]))
		{
			$structure[$program['from']] = array();
		}

		$structure[$program['from']][$program['room']] =& $program;
		$time_structure[] = $program['from'];
		$time_structure[] = $program['to'];
	}

	$time_structure = array_unique($time_structure);
	sort($time_structure);










	$html = array();

	$html['program'] = '';
	$html['abstract'] = '';

	$html['program'] .= '<ul class="shortcuts">';

	foreach (array(1, 2) as $day) {
		$html['program'] .= sprintf('<li><a href="#day%d">%s</a></li>'."\n",
				$day,
				$l10n[$lang]["day_$day"]
				);
	}

	$html['program'] .= '</ul>' . "\n\n";

	$html['program'] .= '<ul class="types">';
	foreach($type_list as $type_id => $type_name)
	{
		if ($type_id <= 0)
		{
			continue;
		}
		$html['program'] .= sprintf('<li class="program_type_%d">%s</li>'."\n",
				$type_id,
				htmlspecialchars($type_name)
				);
	}
	$html['program'] .= '</ul>' . "\n\n";








	$last_stamp = 0;
	$day_increment = 0;

	foreach ($time_structure as $time_id => $time_stamp)
	{
		if (!isset($structure[$time_stamp]))
		{
			continue;
		}

		$last_time = getdate($last_stamp);
		$this_time = getdate($time_stamp);
		$this_time_formatted = strftime("%R", $time_stamp);
		$to_time_formatted = strftime("%R", $time_structure[$time_id+1]);

		if ($last_time['yday'] != $this_time['yday'] || $last_time['year'] != $this_time['year'])
		{
			if($day_increment > 0)
			{
				$html['program'] .= '</tbody></table>'."\n";
			}
			$day_increment += 1;
			$html['program'] .= '<h2 id="day' . $day_increment . '">'
				. $l10n[$lang]["day_$day_increment"]
				. ' (' . $this_time['mon'] . '/' . $this_time['mday'] . ')'
				. '</h2>'
				."\n";

			$html['abstract'] .= '<h2 id="day' . $day_increment . '">'
				. $l10n[$lang]["day_$day_increment"]
				. ' (' . $this_time['mon'] . '/' . $this_time['mday'] . ')'
				. '</h2>'
				."\n";

			$html['program'] .= <<<EOT
<table class="program">
<thead>
	<tr><th>{$l10n[$lang]['time']}</th>

EOT;

			foreach($room_list as $k => $v)
			{
				if ($k <= 0)
				{
					continue;
				}

				$html['program'] .= "<th>$v[$lang]</th>";
			}

			$html['program'] .= <<<EOT
	</tr>
</thead>
<tbody>

EOT;

		}



		$html['program'] .= <<<EOT
	<tr>
		<th rel="{$time_stamp}"><span>{$this_time_formatted}</span> — {$to_time_formatted}</th>

EOT;

		ksort($structure[$time_stamp]);
		foreach ($structure[$time_stamp] as &$program)
		{
			// calculate colspan and rowspan
            if ($program['room'] === 0)
            {
			    $colspan = $program['room'] === 0 ? sizeof($room_list)-1 : 1;
            }
            else if ($program['room'] < 0)
            {
			    $colspan = abs($program['room']);
            }
            else
            {
			    $colspan = 1;
            }

			$rowspan = 1;
			while ($time_structure[$time_id + $rowspan] < $program['to'])
			{
				$rowspan += 1;
			}




			// build classlist
			$class_list = array();
			//$class_list[] = "program_content";

			if (isset($program['lang']))
			{
				$class_list[] = "program_lang_{$program['lang']}";
			}

			if (isset($program['type']))
			{
				$class_list[] = "program_type_{$program['type']}";
			}

			if (isset($program['room']))
			{
                if ($program['room'] <= 0)
                {
				    $class_list[] = "program_room_0";
                }
                else
                {
				    $class_list[] = "program_room_{$program['room']}";
                }
			}

			if ($program['isBreak'])
			{
				$class_list[] = "program_break";
			}

			$class_list_string = implode(" ", $class_list);

			$html['program'] .= <<<EOT
		<td data-pid="{$program['id']}" class="{$class_list_string}" colspan="{$colspan}" rowspan="{$rowspan}">
EOT;



			if (
				isset($program['type']) && $program['type'] !== 0
			)
			{
				$html['program'] .= '<p class="name"><a rel="nocache" href="abstract/#'.anchor_name($program['name']).'">'.htmlspecialchars($program['name']).'</a></p>';
			}
			else
			{
				if (
					isset($name_replace[$program['name']]) &&
					isset($name_replace[$program['name']][$lang])
				)
				{
					$html['program'] .= '<p class="name">'.htmlspecialchars($name_replace[$program['name']][$lang]).'</p>';
				}
				else
				{
					$html['program'] .= '<p class="name">'.htmlspecialchars($program['name']).'</p>';
				}
			}



			if (
				isset($program['room'])
				&& !$program['isBreak']
			)
			{
				$html['program'] .= '<p class="room">' . htmlspecialchars($room_list[$program['room']][$lang]) . '</p>';
			}

			if (isset($program['speaker']))
			{
				$html['program'] .= '<p class="speaker">' . htmlspecialchars($program['speaker']) . '</p>';

				if (isset($program['speakerTitle']))
				{
					$html['program'] .= '<p class="speakerTitle">' . htmlspecialchars($program['speakerTitle']) . '</p>';
				}
			}



			$html['program'] .= "</td>\n";




			// No abstract for program type = 0

			if (
				isset($program['type']) && $program['type'] !== 0
			)
			{
				$html['abstract'] .= '<div class="article" id="'.anchor_name($program['name']).'">';
				$html['abstract'] .= '<h3>'.htmlspecialchars($program['name']).'</h3>';

				if (isset($program['slide']))
				{
					$html['abstract'] .= '<p class="slide"><a href="' . htmlspecialchars($program['slide']) . '" title="Slide">Slide</a></p>';
				}

				if (isset($program['youtube']))
				{
					foreach($program['youtube'] as $i => &$youtube_id) {
						$html['abstract'] .= '<p class="youtube"><a href="http://www.youtube.com/watch?v=' . $youtube_id . '" title="Video ' . ($i+1) . '">Video ' . ($i+1) . '</a></p>';
					}
				}

				if (isset($program['speaker']))
				{
					$html['abstract'] .= '<p class="speaker">' . htmlspecialchars($program['speaker']) . '</p>';
				}

				if (isset($program['abstract']))
				{
					$html['abstract'] .= '<div class="abstract">' . $program['abstract'] . '</div>';
				}

				if (isset($program['bio']))
				{
					$html['abstract'] .= '<div class="bio">' . $program['bio'] . '</div>';
				}

				$html['abstract'] .= "</div>\n";
			}

			$html['program'] .= "</td>\n";
		}

		$html['program'] .= "</tr>\n\n";

		$last_stamp = $time_stamp;
	}

	$html['program'] .= '</tbody></table>'."\n";

	return $html;
}

function get_sponsors_list_from_gdoc() {

	$handle = @fopen('https://spreadsheets.google.com/pub?key=' . SPONSOR_LIST_KEY . '&range=A2%3AI999&output=csv', 'r');

	if (!$handle)
	{
		return FALSE; // failed
	}

	$SPONS = array();

	// name, level, url, logoUrl, desc, enName, enDesc, zhCnName, zhCnDesc
	while (($SPON = fgetcsv($handle)) !== FALSE)
	{

		$level = strtolower(trim($SPON[1]));
		if (strlen($level) === 0) continue;

		if (!isset($SPONS[$level]))
		{
			$SPONS[$level] = array();
		}

		$SPON_obj = array(
			'name' => array(
				'zh-tw' => $SPON[0]
			),
			'desc' => array(
				'zh-tw' => Markdown_Without_Markup($SPON[4])
			),
			'url' => $SPON[2],
			'logoUrl' => $SPON[3],
		);

		if (trim($SPON[5]))
		{
			$SPON_obj['name']['en'] = $SPON[5];
		}

		if (trim($SPON[6]))
		{
			$SPON_obj['desc']['en'] = Markdown_Without_Markup($SPON[6]);
		}

		if (trim($SPON[7]))
		{
			$SPON_obj['name']['zh-cn'] = $SPON[7];
		}

		if (trim($SPON[8]))
		{
			$SPON_obj['desc']['zh-cn'] = Markdown_Without_Markup(linkify($SPON[8]));
		}

		array_push ($SPONS[$level], $SPON_obj);
	}

	fclose($handle);

	return $SPONS;
}

function get_sponsor_info_localize($SPON, $type='name', $locale='zh-tw', $fallback='zh-tw')
{
	if ($SPON[$type][$locale])
	{
		return $SPON[$type][$locale];
	}
	return $SPON[$type][$fallback];
}

function get_sponsors_html($SPONS, $type = 'sidebar', $lang = 'zh-tw') {

	$levelTitlesL10n = array(
		'en' => array(
			'diamond' => 'Diamond Level Sponsors',
			'gold' => 'Gold Level Sponsors',
			'silver' => 'Silver Level Sponsors',
			'bronze' => 'Bronze Level Sponsors',
			'media' => 'Media Partners'
		),
		'zh-tw' => array(
			'diamond' => '鑽石級贊助',
			'gold' => '黃金級贊助',
			'silver' => '白銀級贊助',
			'bronze' => '青銅級贊助',
			'media' => '媒體夥伴'
		),
		'zh-cn' => array(
			'diamond' => '钻石级赞助商',
                        'gold' => '黄金级赞助',
                        'silver' => '白银级赞助',
                        'bronze' => '青铜级赞助',
                        'media' => '媒体伙伴'
		)
	);

	// order of levels (fixed)
	$levels = array(
		'diamond',
		'gold',
		'silver',
		'bronze',
		'media'
	);

	$levelTitles = $levelTitlesL10n[$lang];

	$html = '';
	switch ($type)
	{
		case 'sidebar':
		foreach ($levels as &$level)
		{
			if (!$SPONS[$level]) continue;

			$html .= sprintf("<h2>%s</h2>\n", htmlspecialchars($levelTitles[$level]));
			$html .= sprintf('<ul class="%s">'."\n", $level);

			foreach ($SPONS[$level] as $i => &$SPON)
			{
				$html .= sprintf('<li><a href="%s" target="_blank" title="%s">'.
						 '<img src="%s" width="178" height="72" alt="%s"/></a></li>'."\n",
						htmlspecialchars($SPON['url']),
						htmlspecialchars(get_sponsor_info_localize($SPON, 'name', $lang)),
						htmlspecialchars($SPON['logoUrl']),
						htmlspecialchars(get_sponsor_info_localize($SPON, 'name', $lang))
						);
			}

			$html .= "</ul>\n";
		}
		break;

		case 'page':
		$html .= '<div class="sponsors">';
		foreach ($levels as &$level)
		{
			if (!$SPONS[$level]) continue;

			$html .= '<h2>' . htmlspecialchars($levelTitles[$level]) . '</h2>'."\n";

			foreach ($SPONS[$level] as $i => &$SPON)
			{

				/* for sponsors who has another logo space, exclude media partners */
				if ($level !== 'media' && !trim(get_sponsor_info_localize($SPON, 'desc', $lang)))
				{
					continue;
				}

				$html .= sprintf('<h3><a href="%s" target="_blank"><img src="%s" width="178" height="72" alt="%s" />%s</a></h3>'."\n",
						htmlspecialchars($SPON['url']),
						htmlspecialchars($SPON['logoUrl']),
						get_sponsor_info_localize($SPON, 'name', $lang),
						get_sponsor_info_localize($SPON, 'name', $lang)
						);

				if (trim(get_sponsor_info_localize($SPON, 'desc', $lang)))
				{
					$html .= sprintf('<div class="sponsor_content">%s</div>'."\n",
							get_sponsor_info_localize($SPON, 'desc', $lang));
				}

				$html .= "\n";
			}
		}
		$html .= '</div>';
		break;
	}
	return $html;
}


function anchor_name($s)
{
	return str_replace(" ", "-", trim($s));
}


$SPONS = get_sponsors_list_from_gdoc();

if ($SPONS === FALSE)
{
	print "ERROR! Unable to download sponsors list from Google Docs.\n";
}
else
{
	foreach ($sponsors_output as $type => $l10n)
	{
		foreach ($l10n as $lang => $path)
		{
			print "Write sponsors into " . $path . " .\n";
			$fp = fopen($path, "a");
			fwrite($fp, get_sponsors_html($SPONS, $type, $lang));
			fclose($fp);
		}
	}

	print "Write sponsors into " . $json_output["sponsors"] . " .\n";
	$fp = fopen ($json_output["sponsors"], "w");
	fwrite ($fp, json_encode($SPONS));
	fclose ($fp);
}

$program_list = get_program_list_from_gdoc();
$program_types_list = get_program_types_from_gdoc();
$program_rooms_list = get_program_rooms_from_gdoc();

if (
	$program_list === FALSE
	|| $program_types_list === FALSE
	|| $program_rooms_list === FALSE
)
{
	print "ERROR! Unable to download program list from Google Docs.\n";
}
else
{
	foreach ($program_list_output as $lang => $lang_array)
	{
		$program_list_html = get_program_list_html($program_list, $program_types_list, $program_rooms_list, $lang);

		foreach ($lang_array as $type => $path)
		{
			print "Write program into " . $path . " .\n";
			$fp = fopen($path, "a");
			fwrite($fp, $program_list_html[$type]);
			fclose($fp);
		}
	}

	print "Write program into " . $json_output["program"] . " .\n";
	$fp = fopen ($json_output["program"], "w");
	fwrite ($fp, json_encode(
		array(
			'program' => $program_list,
			'type' => $program_types_list,
			'room' => $program_rooms_list
		)));
	fclose ($fp);
}
