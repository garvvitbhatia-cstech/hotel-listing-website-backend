<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;



class Products extends Authenticatable{

    use HasApiTokens, HasFactory, Notifiable;



    /**

     * The attributes that are mass assignable.

     *

     * @var array<int, string>

     */

    protected $fillable = [
        "title",
        "slug",
        "sku",
		"category_id",
        "sub_category_id",
		"price",
		"quantity",
        "description",
        "banner",
        "featured",
		"seo_title",
		"seo_description",
		"seo_keyword",
		"robot_tags",
        "status",
		"address",
		"city",
		"state",
		"country",
		"terrain",
		"location",
		"type",
		"hero_section_heading",
		"hero_section_description",
		"hero_section_banner",
		"upper_heading",
		"upper_description",
		"parking_facility",
		"souvenir_shop",
		"laundry_service",
		"conference_hall",
		"travel_desk",
		"wifi",
		"doctor_on_call",
		"is_b2b"
    ];



    protected $UpdatableFields = [
        "title",
        "slug",
        "sku",
		"category_id",
        "sub_category_id",
		"price",
		"quantity",
        "description",
        "banner",
        "featured",
		"seo_title",
		"seo_description",
		"seo_keyword",
		"robot_tags",
        "status",
		"address",
		"city",
		"state",
		"country",
		"terrain",
		"location",
		"type",
		"hero_section_heading",
		"hero_section_description",
		"hero_section_banner",
		"upper_heading",
		"upper_description",
		"parking_facility",
		"souvenir_shop",
		"laundry_service",
		"conference_hall",
		"travel_desk",
		"wifi",
		"doctor_on_call",
		"is_b2b"

    ];



    /**

     * The attributes that should be hidden for serialization.

     *

     * @var array<int, string>

     */

    protected $hidden = [

        

    ];



    /**

     * The attributes that should be cast.

     *

     * @var array<string, string>

     */

	public function GetRecordById($id){

		return $this::where('id', $id)->first();

	}

	public function UpdateRecord($Details){

		$Record = $this::where('id', $Details['id'])->update($Details);

		return true;

	}

	public function CreateRecord($Details){

		$Record = $this::create($Details);

		return $Record;

	}



    public function ExistingRecord($title){

		return $this::where('title',$title)->where('status','!=', 3)->exists();

	}

	public function ExistingRecordUpdate($title, $id){

		return $this::where('title',$title)->where('id','!=', $id)->where('status','!=', 3)->exists();

	}



}