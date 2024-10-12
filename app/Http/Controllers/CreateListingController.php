<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\ListingImages;
use App\Models\Notification;
use App\Models\User;
use App\Http\Controllers\Controller;

class CreateListingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Create the listing
        $listing = new Listing([
            'title' => $request->title,
            'location' => $request->location,
            'description' => $request->description,
            'dateCreated' => now(),
            'ownerID' => Auth::id(),
            'status' => 'Pending',
            'approvedBy_userID' => null, // It wont store any data since it was not approved. it will update later if approved
        ]);
        $listing->save();

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '-' . $image->getClientOriginalName();
                $image->storeAs('public/images', $filename);

                ListingImages::create([
                    'imageID' => $listing->listingID,
                    'image_path' => $filename,  
                ]);
            }
        }

        // Notify the admin about the new listing
        $this->notifyAdmin($listing);  // New method to notify admin
        return redirect()->route('space.dashboard')->with('success', 'Listing created successfully.');
    }

    /**
     * Notify the admin that a new listing is created and requires approval.
     */
    protected function notifyAdmin(Listing $listing)
    {
        // Get all admins
        $admins = User::where('role', 'admin')->get();

        // Loop through each admin and send a notification
        foreach ($admins as $admin) {
            Notification::create([
                'n_userID' => $admin->userID,
                'data' => $listing->title,
                'type' => 'listing_approval',
            ]);
        }
    }
}
