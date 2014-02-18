<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Underscore\Types\Arrays;

class GetMoviesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get-movies';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Grabs some movies from Rotten Tomatoes';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$client = new Guzzle\Http\Client();

		$request = $client->createRequest('GET', 'http://api.rottentomatoes.com/api/public/v1.0/movies.json');

		//$request = new \Guzzle\Http\Message\Request('GET', 'http://api.rottentomatoes.com/api/public/v1.0/movies.json');

		$query = $request->getQuery();
		$query->set('page_limit', 25);
		$query->set('apikey', 'zzvngn8qtwswmwhzq89e3fsf');

		$data = array();

		$words = array('house', 'star', 'night', 'adventure', 'the');


		foreach ($words as $word) {

			$query->set('q', $word);	

			for ($i = 1; $i < 20; $i++) {
				$query->set('page', $i);

	    	$response = $request->send();

	    	$json = json_decode($response->getBody(), true);

	    	if (isset($json['movies'])) {	

		    	foreach ($json['movies'] as $movie) {
		    		$row = array();
		    		$row[] = $movie['id'];
		    		$row[] = $movie['title'];
		    		$row[] = $movie['year'];
		    		$row[] = $movie['mpaa_rating'];
		    		$row[] = $movie['runtime'];
		    		$row[] = $movie['synopsis'];
		    		$cast = array_pad(Arrays::pluck($movie['abridged_cast'], 'name'), 5, '');    		
		    		$data[] = array_merge($row, $cast);
		    	}
		    }
			}

		}

		$csv_filepath = storage_path() . '/movies.csv';

		$fp = fopen($csv_filepath, 'a');

		foreach ($data as $fields) {
    	fputcsv($fp, $fields, ';');
		}

		fclose($fp);
	}

}