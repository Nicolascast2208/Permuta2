<?php

namespace App\Livewire\Dashboard;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;

class Trades extends Component
{
    use WithPagination;

    public function render()
    {
        $trades = Offer::with([
                'productRequested', 
                'productOffered', 
                'fromUser', 
                'toUser',
                'chat'
            ])
            ->where('status', 'accepted')
            ->where(function($query) {
                $query->where('from_user_id', Auth::id())
                    ->orWhere('to_user_id', Auth::id());
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.dashboard.trades', compact('trades'));
    }
}