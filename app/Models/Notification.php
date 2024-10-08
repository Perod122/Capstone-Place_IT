<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $primaryKey = 'notificationID';

    protected $fillable = ['n_userID','read_at','data', 'type'];

    public $timestamps = true;

    public function user() {
        return $this->belongsTo(User::class, 'userID');
    }
    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listingID');
    }

}
