<?php

namespace App\Http\Controllers\Collaboration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->query('q', ''));
        $channelId = (int) $request->query('channel_id');

        $messages = collect();
        $files = collect();

        if ($query !== '' && $channelId) {
            $messages = DB::table('messages')
                ->where('channel_id', $channelId)
                ->where('content', 'ILIKE', "%{$query}%")
                ->orderByDesc('id')
                ->limit(100)
                ->get();

            $files = DB::table('document_versions')
                ->where('linked_type', 'message')
                ->whereIn('linked_id', function ($sub) use ($channelId, $query) {
                    $sub->select('id')
                        ->from('messages')
                        ->where('channel_id', $channelId)
                        ->where('content', 'ILIKE', "%{$query}%");
                })
                ->orderByDesc('uploaded_at')
                ->limit(100)
                ->get();
        }

        return response()->json([
            'messages' => $messages,
            'files' => $files,
            'query' => $query,
            'channelId' => $channelId,
        ]);
    }
}
