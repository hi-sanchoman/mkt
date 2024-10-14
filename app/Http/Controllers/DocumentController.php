<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;

class DocumentController extends Controller
{
    public function inertia()
    {   
        return Inertia::render('Documents/Index');
    }

    public function index()
    {   
        return [
            'documents' => Document::orderBy('name', 'asc')->get()
        ];
    }


    public function upload(Request $request)
    {           
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048', // Adjust as needed
        ]);

        // Handle the file upload
        if ($request->hasFile('file')) {
            // Get the uploaded file
            $file = $request->file('file');
            
            // Define the file path
            $filePath = $file->store('documents', 'public'); // Store the file in the 'documents' directory in 'storage/app/public'

            // Create a new Document record in the database
            $document = new Document();
            $document->name = $request->input('name');
            $document->desc = $request->input('desc');
            $document->user_id = auth()->id(); // Assuming the user is authenticated
            $document->src = 'storage/' . $filePath; // Store the file path
            $document->save();

            return [
                "message" => "Документ загружен!"
            ];
        }

        throw new HttpException(500, 'Документ не загружен!');
    }

    public function update(Request $request)
    {       
        $document = Document::find($request->id);
        if($request->name) $document->name = $request->name;
        if($request->desc) $document->desc = $request->desc;
        $document->save();

        return [
            "message" => "Документ обновлен!"
        ];
    }

    public function delete(Request $request)
    {   
        Document::where('id', $request->id)->delete();

        return [
            "message" => "Документ удален!"
        ];
    }
}
