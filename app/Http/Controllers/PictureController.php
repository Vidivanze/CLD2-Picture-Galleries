<?php

namespace App\Http\Controllers;

use App\Picture;
use Illuminate\Http\Request;
use App\Http\Requests\PictureRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\S3\PostObjectV4;

class PictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pictures = Picture::all();
        return view('pictures.index', compact('pictures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $awsClient = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
          ]);

          $bucket = env('AWS_BUCKET');

          $key = "pictures/" . Str::random(40);

          $formInputs = ['acl' => 'private', 'key' => $key];

          $options = [
              ['acl' => 'private'],
              ['bucket' => $bucket],
              ['eq', '$key', $key],
          ];

          $postObject = new PostObjectV4(
            $awsClient, $bucket, $formInputs, $options, "+1 hours"
          );

          return view('pictures.create', [
            's3attributes' => $postObject->getFormAttributes(),
            's3inputs' => $postObject->getFormInputs(),
          ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PictureRequest $request)
    {
        $picture = new Picture;
        $picture->fill($request->all());
        $picture->save();

        return redirect()->route('pictures.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Picture $picture)
    {
        if(Str::startsWith($request->header('Accept'), 'image')){
            return redirect(Storage::disk('s3')->temporaryUrl($picture->storage_path, now()->addMinutes(5)));
        }
        return view('pictures.show', compact('picture'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function edit(Picture $picture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Picture $picture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Picture  $picture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Picture $picture)
    {
        //Delete from s3
        Storage::disk('s3')->delete($picture->storage_path);
        //Delete from db
        $picture->delete();

        return redirect()->route('pictures.index');
    }
}
