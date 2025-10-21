<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $r)
    {
        $q = trim($r->query('q', ''));
        $channelId = (int) $r->query('channel_id');

        $messages = collect();
        $files = collect();

        if ($q !== '' && $channelId) {
            $messages = DB::table('messages')
                ->where('channel_id', $channelId)
                ->where('content', 'ILIKE', "%$q%")
                ->orderByDesc('id')->limit(100)->get();

            $files = DB::table('document_versions')
                ->where('linked_type', 'message')
                ->whereIn('linked_id', function ($sub) use ($channelId, $q) {
                    $sub->select('id')->from('messages')
                        ->where('channel_id', $channelId)
                        ->where('content', 'ILIKE', "%$q%");
                })->orderByDesc('uploaded_at')->limit(100)->get();
        }

        return view('collab.search-results', compact('messages', 'files', 'q', 'channelId'));
    }
}
