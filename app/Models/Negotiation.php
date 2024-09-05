<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    use HasFactory;

    protected $table = 'negotiations';
    
    protected $fillable = ['listingID', 'senderID', 'receiverID', 'offerAmount', 'negoStatus'];
    protected $primaryKey = 'negotiationID';

    public function listing() {
        return $this->belongsTo(Listing::class, 'listingID');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'senderID');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiverID');
    }

    public function replies() {
        return $this->hasMany(Reply::class, 'negotiationID', 'negotiationID');
    }
    public function rentalAgreement()
{
    return $this->hasOne(RentalAgreement::class, 'listingID', 'listingID');
}
}