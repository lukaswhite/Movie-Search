<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PopulateSearchIndexCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'search:populate';

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
		$client = new \Solarium\Client(
				array(				
					'host' => '127.0.0.1',
            'port' => 8983,
            'path' => '/solr/',
					)
				);	
		

    // add the document and a commit command to the update query
    


		$csv_filepath = storage_path() . '/movies.csv';

		$fp = fopen($csv_filepath, 'r');

		while (($row = fgetcsv($fp, 1000, ";")) !== FALSE) {

			// get an update query instance
    	$update = $client->createUpdate();

			$doc = $update->createDocument();    
    	$doc->id = $row[0];
			$doc->title = $row[1];
			if (strlen($row[2])) {
				$doc->year = $row[2];
			}
			if (strlen($row[3])) {
				$doc->rating = $row[3];
			}
			if (strlen($row[4])) {
				$doc->runtime = $row[4];
			}
			$doc->synopsis = $row[5];

			$cast = array();

			for ($i = 6; $i <= 10; $i++) {
				if ((isset($row[$i])) && (strlen($row[$i]))) {
					$cast[] = $row[$i];
				}
			}

			$doc->cast = $cast;

			/**
			$doc->cast = array();

			for ($i = 6; $i <= 10; $i++) {
				if (strlen($row[$i])) {
					$doc->cast[] = $row[$i];
				}
			}
			**/

			$update->addDocument($doc);
    	$update->addCommit();

	    // this executes the query and returns the result
	    $result = $client->update($update);
	    
		}

		fclose($fp);
	}

}