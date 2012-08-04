<?php
	include_once('SmartIRC.php');
	include_once('SmartIRC/defines.php');
	include_once('SmartIRC/irccommands.php');
	include_once('SmartIRC/messagehandler.php');

	include_once('Google/googleurlapi.php');
	include_once('dcsmeetings/attendancelist.txt');
	include_once('dcsmeetings/location.txt');
	include_once('dcsmeetings/topic.txt');


  class mybot
{
	static $topic = "";
	static $location = "";	
//	static $target = null;

	//quit function
        function quit(&$irc, &$data)
        {
            if ($data->nick == "ScooterAmerica")
	    {
                $irc->disconnect();
	    }

	   //quit protection

	  	if ($data->nick != "ScooterAmerica")
		{

			 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.': No, i dont think i will.');

		}
	}

		//leave a channel
		function peace($irc, $data)
		{
			if ($data->nick == "ScooterAmerica")
			{
				if(isset($data->messageex[1])) 
				{    
                			$channel = $data->messageex[1];
                			$irc->part($channel);
				}
			}
		}

		//join a channel
		function joinChannel($irc, $data)
		{
			if ($data->nick == "ScooterAmerica")
			{
				if(isset($data->messageex[1]))
				{    
                			$channel = $data->messageex[1];
                			$irc->join($channel);
				}
			}
		}


		//make the bot say something in the channel
		function query(&$irc, &$data)
    		{
			$newmsg = trim(substr($data->message, 5));
 
       			 // text sent to channel
       				$irc->message(SMARTIRC_TYPE_CHANNEL, '#dcs', $newmsg);	
		}


		//auto rejoin
		function kickResponse(&$irc, &$data)
   		 { //when kicked
          		$irc->join(array('#jeff','#dcs'));
	           	return;
	         }


		//join greeting
		 function onjoin_greeting(&$irc, &$data)
   		 {  // don't greet self
			if ($data->nick == $irc->_nick)
        	   	{	
				return;
			}

		 	if ($data->channel == '#dcs')
			{
				if ($data->nick == "neilforobot")
				{
					 $irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'glares at neilforobot');
				}
			}

			elseif ($data->channel == '#jeff')
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.': SHALL WE START?!?! ');
			}
   		}

		//api libraries
		function api($irc, $data)
                {
			$api = trim(substr($data->message, 5));
	                $irc1 = array("java"=>" http://docs.oracle.com/javase/1.5.0/docs/api/", "php"=>" http://php.net/manual/en/book.spl.php", "haskell"=>" http://www.haskell.org/hoogle/", "python"=>" http://docs.python.org/library/", "perl"=>" http://perldoc.perl.org/index-language.html");

			switch($api)
               		{
				case "java":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["java"]);
					break;

				case "php":
	              			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["php"]);
					break;

				case "haskell":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["haskell"]);
					break;

				case "python":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["python"]);
					break;

				case "perl":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$irc1["perl"]);
					break;
			}
		}


	//specific api searches
		function php_search($irc, $data)
		{
                	$term = trim(substr($data->message, 12));
			$terms = str_word_count($term, 1, '_()');
			
			$params = "";
			if(str_word_count($term, 1) > 1)
			{
				foreach ($terms as $values)
				{
					$params .= $values."+";
				}
				
				$fparams = substr($params, 0, -1);

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.php.net/manual-lookup.php?pattern='.$fparams.'&lang=en&scope=quickref');
			}

			else
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.php.net/manual-lookup.php?pattern='.$term.'&lang=en&scope=quickref');
			}

		}

 		function perl_search($irc, $data)
                {
              		$term2 = trim(substr($data->message, 13));
			$terms2 = str_word_count($term2, 1, '_()');

			$params2 = "";
			if(str_word_count($term2, 1) > 1)
			{
				foreach ($terms2 as $values2)
				{
					$params2 .= $values2."+";
				}

				$fparams2 = substr($params2, 0, -1);

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://perldoc.perl.org/search.html?q='.$fparams2);
			}

			else
			{
                       		$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://perldoc.perl.org/search.html?q='.$term2);
			}
		}

		function cpan_search($irc, $data)
		{
                   	$term3 = trim(substr($data->message, 13));
			$terms3 = str_word_count($term3, 1, '_()');

			$params3 = "";
			if(str_word_count($term3, 1) > 1)
			{
				foreach($terms3 as $values3)
				{
					$params3 .= $values3."+";
				}

				$fparams3 = substr($params3, 0, -1);

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://search.cpan.org/search?query='.$fparams3.'&mode=all');
			}
			
			else
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://search.cpan.org/search?query='.$term3.'&mode=all');
			}
		}

		function python_search($irc, $data)
                {
                       	$term4 = trim(substr($data->message, 15));
			$terms4 = str_word_count($term4, 1, '_()');

			$params4 = "";
			if(str_word_count($term4, 1) > 1)
			{
				foreach($terms4 as $values4)
				{
					$params4 .= $values4."+";
				}

				$fparams4 = substr($params4, 0, -1);

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://docs.python.org/search.html?q='.$fparams4.'&check_keywords=yes&area=default');
			}
			
			else
			{

                       		$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://docs.python.org/search.html?q='.$term4.'&check_keywords=yes&area=default');
                	}
		}

		function haskell_search($irc, $data)
		{
                       	$term5 = trim(substr($data->message, 16));
			$terms5 = str_word_count($term5, 1, '_()');

			$params5 = "";
			if(str_word_count($term5, 1) > 1)
			{
				foreach($terms5 as $values5)
				{
					$params5 .= $values5."+";
				}

				$fparams5 = substr($params5, 0, -1);

				 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.haskell.org/hoogle/?hoogle='.$fparams5);
			}

			else
			{
                       		$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' http://www.haskell.org/hoogle/?hoogle='.$term5);		
			}
		}


		//espn scores
		function scoresTemp($irc, $data)
		{
			$team = trim(substr($data->message, 8));
			$score = array("ncaa" => " http://scores.espn.go.com/ncb/scoreboard", "nba" => " http://espn.go.com/nba/scoreboard", "nfl" => " http://scores.espn.go.com/nfl/scoreboard", "nhl" => " http://scores.espn.go.com/nhl/scoreboard", "mlb" => " http://espn.go.com/mlb/scoreboard");

			switch($team)
			{
				case "ncaa":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["ncaa"]);
					break;
			
				case "nba":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["nba"]);
					break;

				case "nfl":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["nfl"]);
					break;

				case "nhl":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["nhl"]);
					break;

				case "mlb":
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.$score["mlb"]);
					break;
			}

		}


	   //triggers (talk)

		function neilforoshan($irc, $data)
		{
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'That man is evil');
		}

	        function doit($irc, $data)
                {
		        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'Do it for you, not for me');
	        }

		function twss($irc, $data)
		{
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'She said that! Yes?');
		}

		function myPing($irc, $data)
		{
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.': ping!');
		}

		function googleIt($irc, $data)
                {
			$google = trim(substr($data->message, 3));

			if($data->message == "!g")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "There's nothing to Google");
			}

			else
			{
				$googles = str_word_count($google, 1);

				$words = "";
				if(str_word_count($google, 0) > 1)
				{
					foreach ($googles as $word)
					{
						$words .= $word."+";
					}

					$fwords = substr($words, 0, -1);

					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your results: https://www.google.com/#q=".$fwords);
				}

				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your results: https://www.google.com/#q=".$google);
				}
			}
		}

		function googleLucky($irc, $data)
		{
			$google = trim(substr($data->message, 7));

			if($data->message == "!lucky" || $data->message == "!lucky ")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "You're not lucky");
			}

			else
			{
                                $googles = str_word_count($google, 1);
	
        	                $words = "";
                	        if(str_word_count($google, 0) > 1)
                       		{
 	                       		foreach ($googles as $word)
                               		{
        	                       		$words .= $word."+";
                               		}

                               		$fwords = substr($words, 0, -1);

                               		$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your result: http://www.google.com/search?btnI=I%27m+Feeling+Lucky&ie=UTF-8&oe=UTF-8&q=".$fwords);
                         	}

                       		else
                        	{
                        		$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your result: http://www.google.com/search?btnI=I%27m+Feeling+Lucky&ie=UTF-8&oe=UTF-8&q=".$google);
                       		}	
			}
		}

//youtube like/dislike checker

		function youTube($irc, $data)
		{
		}

	//keep a note/notes of something..like a reminder function except saved in a file forever
		function note($irc, $data)
		{
			$note = substr($data->message, 6);
			$person = $data->host;

			if ($data->message == "!note")
			{	
				if (file_exists("Notes/".$person.".txt"))
				{
					$currentNotes = fopen("Notes/".$person.".txt", "r");
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Your Notes:");

					while(!feof($currentNotes))
                                        {
                                	        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, fgets($currentNotes));
                                        }

                                        fclose($currentNotes);
				}

				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "You have no notes yet");
				}
			}
		

			elseif ($data->message == "!note clear")
			{
				unlink("Notes/".$person.".txt");
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Your notes are gone. I hope you've got a good memory");
			}

			else
			{
				$makeNote = fopen("Notes/".$person.".txt", "a+");
				fwrite($makeNote, $note."\n");
				fclose($makeNote);

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Note Taken");
			}
		}

		function delNote($irc, $data)
		{
			$writeNotes = "";
			$person = $data->host;
			$notes = file("Notes/".$person.".txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			$i = $data->messageex[1];
	
			if(is_numeric((int)$i) && $i > 0)
			{
				$j = $i-1;
				unset($notes[$j]);

				$newNotes = fopen("Notes/".$person.".txt", "w+");				
				foreach ($notes as $value)
				{
					$writeNotes = $value."\r\n";
					fwrite($newNotes, $writeNotes);
				}
				fclose($newNotes);
				
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Note Deleted");
			}


			else
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Not Valid Entry");
			}
		}


	// bot help manual
		function help($irc, $data)
		{
			$help = trim(substr($data->message, 6));

			if ($data->message == "!help")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Please use "!help <botname>" for a list of features.');
			}

			else
			{
				switch($help)
				{
					case "api":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !api <language>. !search <language><term(s)>");
						break;

					case "meetings":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !dcsmeeting, !topic <topic(s)>, !location <location(s)>.");
						break;

					case "confirm":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !confirm <yes>/<no>/<attendance>/<cleared>");
						break;

					case "burn":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !burn <nick> || !superburn <nick>");
						break;

					case "google":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !g <term(s)> || !lucky <term(s)>");
						break;

					case "straws":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !drawstraws");
						break;

					case "say":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: (PM) !say <message>");
						break;
	
					case "hash":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: (PM) !hash <text>");
						break;
	
					case "insult":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !insult <nick>");
						break;
	
					case "compliment":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !compliment <nick>");
						break;
	
					case "AakashBot":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Help Options: api | meetings | confirm | burn | notes | straws | google | say | hash | insult | compliment | all");
						break;

					case "notes":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Usage: !note <your note>| !note clear | !note (view your notes) | !delnote <line #>");
						break;
	
					case "all":
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "http://forum.deadcodersociety.org/index.php/topic,217.0.html");
						break;			
				}
			}
		}

//DCS Functions

	        //countdown to next meeting
                function countdown($irc, $data)
                {
                	global $attendance;	
			global $location;
			global $topic;
			global $target;
	
                        date_default_timezone_set('America/New_York');

                        $date = "07.27.2012";
                        $day = "Friday";
                        $time = "6:45 pm";

			$target = mktime(18, 45, 0, 7, 27, 2012, 1);
                        $seconds_away = $target-time();
                        
			$days = (int)($seconds_away/86400);
                        $hours = (int)(($seconds_away-($days*86400))/3600);
			$mins = (int)(($seconds_away-($days*86400)-($hours*3600))/60);

                        if ($target != null)
                        {
				if ($days > 0 || $hours > 0 || $mins > 0)
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'dcs: The next meeting is on '.$day.', '.$date.' at '.$time.'. Which is '.$days.' day(s), '.$hours." hour(s), and ".$mins." minute(s) from now.");
				}

				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The meeting has started or is already over');
					return;
				}
				
				$top = fopen("dcsmeetings/topic.txt", "r");
				$currentTop = fgets($top);		

				if(empty($currentTop))
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic undecided");
				}

				else
				{
					$topic = $currentTop;
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic: ".$topic);
				}

				$place = fopen("dcsmeetings/location.txt", "r");
				$currentLoc = fgets($place);

				if(empty($currentLoc))
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location not set");
				}
				else
				{
					$location = $currentLoc;
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location: ".$location);
			
				}

				fclose($list);
				fclose($place);			
			}
                        
			else
			{
                        	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The next DCS meeting has not yet been scheduled');
 			}
		}   	
	
	//topic function to set topic for meetings
	function meeting_Topic($irc, $data)
	{            
		global $topic;
                $newTopic =  trim(substr($data->message, 7));

                 //makes the bot spit out the current topic set
                 if ($data->message == "!topic")
                 {	
			$top = fopen("dcsmeetings/topic.txt", "r");
			$currentTop = fgets($top);

			if(empty($currentTop))
			{				
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Topic undecided");
				fclose($top);
			}

			else
			{
				$topic = $currentTop;
                         	
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "DCS meeting topic: ".$topic);
				fclose($top);
			}
                 }

		elseif ($data->message == "!topic clear")
		{
			$top = fopen("dcsmeetings/topic.txt", "w");
			$topic = "";

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Cleared");

			fwrite($top, $topic);
			fclose($top);

		}

                  else //code to run to change the topic
                 {
			$top = fopen("dcsmeetings/topic.txt", "w");
			
			$topic = $newTopic;
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "New Meeting Topic: ".$topic);
                 
			fwrite($top, $topic);
			fclose($top);
		}
	}


		//set the location of each DCS meeting
                function meeting_Location($irc, $data)
                {
			//stores the meeting location
                        global $location;

                        $newLoc = trim(substr($data->message, 10));
			$changeLoc = str_word_count($newLoc, 1, "0123456789");

			//used to ask for meeting location
                        if($data->message == "!location")
                        {
				$place = fopen("dcsmeetings/location.txt", "r");
				$currentLoc = fgets($place);

				//checks if the file is empty
                                if(empty($currentLoc))
                                {
                                	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Location not set");
                                	fclose($place); 
				}

                                 else
                                 {
					$location = $currentLoc;

                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "DCS meeting location: ".$location);
					fclose($place);
                                 }

                         }

			//clears the file location
                         elseif ($data->message == "!location clear")
                         {
				$place = fopen("dcsmeetings/location.txt", "w");
                                $location = "";

                                $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Cleared");
        	
				fwrite($place, $location);
				fclose($place); 
	                }

			//changes location and writes back to file
                        else
                         {
				$place = fopen("dcsmeetings/location.txt", "w");

                         	$loc = "";
	                        if(str_word_count($newLoc, 0, "0123456789") >= 1)
	                        {
        	                        foreach ($changeLoc as $locs)
                	                {
        	                                $loc .= $locs."+";
                	                }
				}
	                       	
				$shortLoc = substr($loc, 0, -1);
                         	$mapLoc = "https://maps.google.com/maps?q=".$shortLoc;

        	                // Create new API instance
	                        $google = new GoogleURLAPI();

                        	//Shorten URL
	               	        $shortLocation = $google->shorten($mapLoc);

	                        $location = $shortLocation;
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "New Meeting Location: ".$location);

				fwrite($place, $location);
				fclose($place);

                         }
                 }


          //function to keep a list of those who will be and wont be attending meetings
	function meeting_List($irc, $data)
	{
		global $target;
		if ($target == null)
		{
			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Confirmation list unavailable until meeting date is set");
			return;
		}

		elseif($target != null)
		{
		 $response = $data->messageex[1];
                 switch($response)
                 {

                 	case "yes":
                        	$attending = file("dcsmeetings/attendancelist.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                                if(!in_array($data->nick." said yes", $attending) && !in_array($data->nick." said no", $attending))
                                {
                                        $attending = fopen("dcsmeetings/attendancelist.txt", "a+");
                                        $name = $data->nick." said yes";
                                        fwrite($attending, $name."\n");
                                        fclose($attending);
                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Yes Confirmed. See you there!");
                                }
                                elseif (in_array($data->nick." said no", $attending) && !in_array($data->nick." said yes", $attending))
                                {
                                        $name = array_search($data->nick." said no", $attending);
                                        unset($attending[$name]);
                                        $newName = $data->nick." said yes\n";
                                        $newResponse = fopen("dcsmeetings/attendancelist.txt", "w+");
                                        fwrite($newResponse, $newName);
                                        $response = "";
                                        foreach ($attending as $value)
                                        {
                                                $response = $value."\r\n";
                                                fwrite($newResponse, $response);
                                        }
                                        fclose($newResponse);
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Glad to see you wised up. Yes Confirmed");
                                }
				else
                                {
                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You've already said yes.");
                                }
                         break;

                         case "no":
                                $attending = file("dcsmeetings/attendancelist.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                                if(!in_array($data->nick." said no", $attending) && !in_array($data->nick." said yes", $attending))
                                {
                                        $attending = fopen("dcsmeetings/attendancelist.txt", "a+");
                                        $name = $data->nick." said no";
                                        fwrite($attending, $name."\n");
                                        fclose($attending);
                                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "No Confirmed. You won't be missed.");
                                }
                                elseif (in_array($data->nick." said yes", $attending) && !in_array($data->nick." said no", $attending))
                                {
                                       $name = array_search($data->nick." said yes", $attending);
                                       unset($attending[$name]);
                                       $newName = $data->nick." said no\n";
                                       $newResponse = fopen("dcsmeetings/attendancelist.txt", "w+");
                                       fwrite($newResponse, $newName);
                                       $response = "";
                                       foreach ($attending as $value)
                                       {
                                               $response = $value."\r\n";
                                               fwrite($newResponse, $response);
                                       }
                                       fclose($newResponse);
                                       $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "You'll regret this. Change to no confirmed");
                                }
				 else
                                 {
         	                        $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You said no already. We get it.");
                                 }
                         break;

                         case "attendance":

                                 $names = file_get_contents("dcsmeetings/attendancelist.txt");
                                 if(strlen($names) > 1)
                                 {
                                         $attending = fopen("dcsmeetings/attendancelist.txt", "r");
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Responses so Far:");
                                         while(!feof($attending))
                                         {
                                                 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, fgets($attending));
                                         }
                                         fclose($attending);
                                 }
                                 else
                                 {
                                         $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "No one had responsed yet");
                                 }
                         break;

                         case "cleared":
                                 file_put_contents("dcsmeetings/attendancelist.txt", NULL);
                                 $irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Lists cleared");
                         break;
			}	
		}
	}
		//creates a md5 hash of a string
		function hash($irc, $data)
		{
			$hashed = trim(substr($data->message, 6));

			$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, md5($hashed));
		}


		//compliments a user
		function nice($irc, $data)
		{			
			$comp = array(" <--This guy. AWESOME", " You're the best", " Everyone is jealous of you", " You're amazing", " <--Next President");
			$rand_comp = shuffle($comp);
			$name = trim(substr($data->message, 12));

			if ($data->message == "!compliment !roulette" || $data->message == "!compliment !dino !roulette")
                       	{
                               	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Stop trying to break things');
                       	}

			else
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.$comp[$rand_comp]);	
		}

		//insults a user
		function mean($irc, $data)
		{
			$ins = array(" You suck", " It would be better if you left", " You will never amount to anything", " Im just going to pretend like you arent here", " No one likes you");
			$rand_ins = shuffle($ins);
			$name = trim(substr($data->message, 8));

			if ($data->message == "!insult !roulette" || $data->message == "!insult !dino !roulette")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.' Stop trying to break things');
			}

			else

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.$ins[$rand_ins]);
		}

		function drinking_game($irc, $data)
		{
			$nicks = array("ScooterAmerica", "stan_theman", "bro_keefe", "compywiz", "NellyFatFingers", "jico", "prg318", "ericoc", "OpEx");
			$drinker = shuffle($nicks);

			$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $nicks[$drinker]);
		}


		//triggers(actions)
		function burn($irc, $data)
		{
			$short = trim(substr($data->message, 6));

			if ($data->message == "!burn AakashBot")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." You're not good enough to burn me");
			}

			elseif ($data->message == "!burn")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Burn who?");
			}

			else
				$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'gives the burn ointment to '.$short);
		}

		function superBurn($irc, $data)
		{
			$burnee = trim(substr($data->message, 11));

			if ($data->message == "!superburn AakashBot" || $data->message == "!superburn ScooterAmerica")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "HOW DARE YOU!!!");
				$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'ignites '.$data->nick.' with a flamethrower!');
			}

			elseif ($data->message == "!superburn")
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick." Who is that directed towards?");
			}

			else
				$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'sets '.$burnee.' on fire!');
		}

		//gives operator status to a user (bot must have ops already to do this)
		function opMe(&$irc, &$data)
	        {

			$dcs = array("ScooterAmerica", "compywiz", "bro_keefe", "stan_theman", "jico", "NellyFatFingers", "prg318", "ericoc", "OpEx");
			if (in_array($data->nick, $dcs))
			{
				$nickname = $data->nick;
               			$channel = $data->channel;
               			$irc->op($channel, $nickname);
			}	

			else

				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, "Sorry ".$data->nick.".  You're not on the list");
		}

		//makes the decision for us so we dont have to
		function straws($irc, $data)
		{
			$nicks = array(" ScooterAmerica", " stan_theman", " bro_keefe", " compywiz", " NellyFatFingers", " jico", " prg318", " ericoc", " OpEx");
			$rand_nick = shuffle($nicks);

			$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, 'hands out the straws.' .$nicks[$rand_nick]. ' got the short straw.');
		}


}


    $bot = &new mybot();
    $irc = &new Net_SmartIRC();
    $irc->setDebug(SMARTIRC_DEBUG_ALL);
    $irc->setUseSockets(FALSE);


	//bot help manual
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!help\b', $bot, 'help');     

	//api libraries
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!api ([_\w]+)', $bot, 'api');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.php ([_\w]+)', $bot, 'php_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.perl ([_\w]+)', $bot, 'perl_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.cpan ([_\w]+)', $bot, 'cpan_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.python ([_\w]+)', $bot, 'python_search');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!search.haskell ([_\w]+)', $bot, 'haskell_search');


	//espn scores(temp)
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!scores ([_\w]+)', $bot, 'scoresTemp');


	//greet & leave responses
	$irc->registerActionhandler(SMARTIRC_TYPE_KICK, '.*', $bot, 'kickResponse');
        $irc->registerActionhandler(SMARTIRC_TYPE_JOIN, '.*', $bot, 'onjoin_greeting');

	//part and join
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!part', $bot, 'peace');
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!join', $bot, 'joinChannel');


	//dcs meeting functions
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!dcsmeeting', $bot, 'countdown');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!confirm', $bot, 'meeting_List');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!topic\b', $bot, 'meeting_Topic');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL,'^!location\b', $bot, 'meeting_Location');

	//bot talk
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '4chan', $bot, 'neilforoshan');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^doit', $bot, 'doit');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, 'twss', $bot, 'twss');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!me', $bot, 'myPing');	
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!say', $bot, 'query');
	$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^!hash', $bot, 'hash');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!compliment ([_\w]+)', $bot, 'nice');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!insult ([_\w]+)', $bot, 'mean');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!drink', $bot, 'drinking_game');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!g\b ([_\w]+)', $bot, 'googleIt');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!lucky ([_\w]+)', $bot, 'googleLucky');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!note', $bot, 'note');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!delnote', $bot, 'delNote');

	//bot actions
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!quit', $bot, 'quit');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!burn ([_\w]+)', $bot, 'burn');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!superburn ([_\w]+)', $bot, 'superBurn');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^\b!op\b', $bot, 'opMe');
	$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^!drawstraws', $bot, 'straws');

	//DCS connect/login
	$irc->connect('ssl://irc.deadcodersociety.org', '6697');
	$irc->login('AakashBot', 'Net_SmartIRC Client '.SMARTIRC_VERSION.'(aakashBot.php)', '0');

/*
	//freenode connect/login
	$irc->connect('chat.freenode.net', '6667');
	$irc->login('AakashBot', 'Net_SmartIRC Client '.SMARTIRC_VERSION.'(aakashBot.php)', '0');
*/

	//channel join
//	$irc->join(array('#jeff'));
	$irc->join(array('#jeff', '#dcs', '#finance'));


	$irc->listen();
        $irc->disconnect();
?>
