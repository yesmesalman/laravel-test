<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /** ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different showrooms

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained();
            $table->dateTime('start_time');
            $table->unsignedSmallInteger('duration')->comment('in minutes');
            $table->unsignedSmallInteger('capacity');
            $table->timestamps();
        });


        Schema::create('showrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('capacity');
            $table->timestamps();
        });

        Schema::create('shows_showrooms', function (Blueprint $table) {
            $table->foreignId('show_id')->constrained();
            $table->foreignId('showroom_id')->constrained();
            $table->primary(['show_id', 'showroom_id']);
            $table->timestamps();
        });

        Schema::create('pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained();
            $table->unsignedDecimal('base_price', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('premium_percentage')->default(0);
            $table->timestamps();
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showroom_id')->constrained();
            $table->unsignedSmallInteger('row');
            $table->unsignedSmallInteger('number');
            $table->unsignedTinyInteger('seat_type_id')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained();
            $table->foreignId('seat_id')->constrained();
            $table->string('customer_name');
            $table->timestamps();
        });

        Schema::table('shows', function (Blueprint $table) {
            $table->foreign('showroom_id')->references('id')->on('showrooms');
        });

        Schema::table('shows_showrooms', function (Blueprint $table) {
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
            $table->foreign('showroom_id')->references('id')->on('showrooms')->onDelete('cascade');
        });

        Schema::table('pricing', function (Blueprint $table) {
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
