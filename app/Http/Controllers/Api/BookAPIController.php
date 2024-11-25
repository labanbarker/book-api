<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;


class BookAPIController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function processQuery(Request $request)
    {
        $data = null;
        $isTesting = $request->test ? true : false;
        $offset = (int) $request->offset;

        // only get 20 results at a time, 0-20, 21-40, etc
        if ($offset % 20 != 0) {
            $offset = 0; 
        }

        // query parameters
        $author = (string) $request->author;
        $isbn = (string) $request->isbn;
        $title = (string) $request->title;

        // build query
        $query = '';
        if ($offset)
            $query .= '&offset=' . $offset;
        if ($author)
            $query .= '&author=' . $author;
        if ($title)
            $query .= '&title=' . $title;
        if ($isbn)
            $query .= '&isbn=' . $isbn;
 
        // using real data
        if (!$isTesting) {

            $apiKey = env("BOOKS_API_KEY");
            $url = 'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json' . '?api-key=' . $apiKey . $query;
            $curl = curl_init();
            $headers = ['Content-Type: application/json'];

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($curl);

            if (curl_errno($curl)){
                Log::error('BookAPIController curl error: ' . curl_error($curl));       
            } else {
                try {
                    $data = json_decode($response, true);
                } catch (Exception $e) {
                    Log::error('BookAPIController data format error: ' . $e->getMessage() . ' - trace: ' . $e->getTraceAsString());
                }
            }

            curl_close($curl);
        
        // using test data, so no query params since they are filtered on NYT's side
        } else {
            $data = json_decode(Storage::disk('local')->get('public/testbookdata.txt'), true);
        }

        $json_output = json_encode($data, JSON_PRETTY_PRINT);     
        echo '<pre>' . $json_output . '</pre>';   
    }
}
