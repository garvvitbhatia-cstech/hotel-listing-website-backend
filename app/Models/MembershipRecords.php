<?php
namespace App\Models; 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MembershipRecords extends Authenticatable{

    use HasApiTokens, HasFactory, Notifiable;
	
	protected $table = 'membership_records';
	
    protected $fillable = [
        "name",
        "flat_no",
		"building",
		"street",
        "area",
		"landmark",
		"city",
		"state",
		"country",
		"postal_code",
		"mobile",
		"alt_mobile",
		"land_line",
		"email",
		"pan_no",
		"address_proof",
		"address_proof_no",
		"c_name",
		"c_building",
		"c_street",
		"c_area",
		"c_landmark",
		"c_city",
		"c_state",
		"c_country",
		"c_postal_code",
		"c_mobile",
		"c_alt_mobile",
		"c_landline",
		"c_email",
		"salutation",
		"b_name",
		"b_gender",
		"b_nationality",
		"b_dob",
		"b_relation",
		"b_pan_no",
		"b_address_proof",
		"b_address_proof_no"
    ];


    protected $UpdatableFields = [

		"name",
        "flat_no",
		"building",
		"street",
        "area",
		"landmark",
		"city",
		"state",
		"country",
		"postal_code",
		"mobile",
		"alt_mobile",
		"land_line",
		"email",
		"pan_no",
		"address_proof",
		"address_proof_no",
		"c_name",
		"c_building",
		"c_street",
		"c_area",
		"c_landmark",
		"c_city",
		"c_state",
		"c_country",
		"c_postal_code",
		"c_mobile",
		"c_alt_mobile",
		"c_landline",
		"c_email",
		"salutation",
		"b_name",
		"b_gender",
		"b_nationality",
		"b_dob",
		"b_relation",
		"b_pan_no",
		"b_address_proof",
		"b_address_proof_no"
    ];


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

}