<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\File;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function index()
    {   
        $documents = Document::with('file')->get();
        
        foreach($documents as $document) {
            if($document->file)
                $document->file->path = '/storage/' . $document->file->path;
        }

        return Inertia::render('Documents/Index',[
        	"documents" => $documents,
        ]);
    }

    public function create(){
    	return Inertia::render('Documents/Create');
    }   

    public function store(Request $request){

        $file_name = Auth::user()->id. '_' . time() . '.' . $request->file->getClientOriginalExtension();

        $request->file->storeAs('public/documents', $file_name);

        $file = File::create([
            'name' => $request->name,
            'path' => 'documents/'. $file_name,
            'type' => $request->file->getClientOriginalExtension(),
            'user_id' => Auth::user()->id,
        ]);

        if($file) {
            Document::create([
                'name' => $request->name,
                'file_id' => $file->id,
                'date' => date('Y-m-d'),
            ]);
        }
        
        return redirect('documents')->with([
            'success' => 'Документ создан'
        ]);
    }

}
