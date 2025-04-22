<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    private function token() 
    {
        $client_id = \Config('services.google.client_id');
        $client_secret = \Config('services.google.client_secret');
        $refresh_token = \Config('services.google.refresh_token');

        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
        ]);

        $accessToken = json_decode((string)$response->getBody(), true);

        return $accessToken['access_token'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('file')->get();

        return view('welcome', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $accessToken = $this->token();

        $file = $request->file;
        $fileName = $file->getClientOriginalName();
        $fileData = file_get_contents($file->getRealPath());
        $mimeType = $file->getClientMimeType();
        $folderId = $request->input('folder_id', config('services.google.folder_id'));

        $boundary = uniqid();
        $delimiter = '----' . $boundary;

        $metadata = json_encode([
            'name' => $fileName,
            'parents' => [$folderId]
        ]);

        $body = "--$delimiter\r\n";
        $body .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
        $body .= $metadata . "\r\n";

        $body .= "--$delimiter\r\n";
        $body .= "Content-Type: $mimeType\r\n\r\n";
        $body .= $fileData . "\r\n";
        $body .= "--$delimiter--";

        // Use withBody() for sending raw body
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => "multipart/related; boundary=$delimiter"
        ])->withBody($body, "multipart/related; boundary=$delimiter")
        ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart');

        if ($response->successful()) {
            $file_id = json_decode($response->body())->id;

            DB::table('file')->insert([
                'nama_file' => $fileName,
                'file_id' => $file_id,
            ]);

            return response('File uploaded to Google Drive in the specified folder.');
        } else {
            return response('Failed to upload to Google Drive.', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = DB::table('file')->where('file_id', $id)->first();

        $ext = pathinfo($file->nama_file, PATHINFO_EXTENSION);
        $accessToken = $this->token();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$accessToken,
        ])->get('https://www.googleapis.com/drive/v3/files/'.$file->file_id.'?alt=media');

        if($response->successful()) {
            $filePath = '/downloads/'.$file->nama_file;

            Storage::put($filePath, $response->body());

            return Storage::download($filePath);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
