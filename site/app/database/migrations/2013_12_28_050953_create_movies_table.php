<?php

use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('movies', function($table)
		{
			$table->increments('id')->unsigned();			
			$table->string('title');
			$table->text('synopsis');
			$table->text('cast');
			$table->integer('duration');
			$table->float('price');
			$table->string('format', 32);
      $table->string('rating', 10);
      $table->string('cover_image');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('movies');
	}

}