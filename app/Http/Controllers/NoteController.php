<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use PDF;
// use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::check()) return redirect("login");

        $data = Note::latest()->paginate(5);

        return view('notes.index',compact('data'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::check()) return redirect("login");

        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::check()) return redirect("login");
        
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

       Note::create($request->all());

       return redirect()->route('notes.index')
       ->with('success','Операция завершена успешно');
   }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        if(!Auth::check()) return redirect("login");
        
        return view('notes.show',compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        if(!Auth::check()) return redirect("login");
     
        return view('notes.edit',compact('note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        if(!Auth::check()) return redirect("login");
      
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $note->update($request->all());

        return redirect()->route('notes.index')->with('success','Операция завершена успешно');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if(!Auth::check()) return redirect("login");
        
        $note->delete();

        return redirect()->route('notes.index')->with('success','Операция завершена успешно');
    }

    public function dlpdf($id, Note $note)
    {
        if(!Auth::check()) return redirect("login");
        
        $row=$note->findOrFail($id);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('dejavu sans');
        $document = $phpWord->loadTemplate(storage_path().'/test.docx');

        $document->setValue('title', $row->title);
        $document->setValue('desc', $row->description);

        $name = 'download.docx'; // /public folder
        $document->saveAs($name);

        ///////////////// PDF ///////////////////
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
         
        //Load word file
        $Content = \PhpOffice\PhpWord\IOFactory::load($name); 
 
        //Save it into PDF
        $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'PDF');
        $filePdf = 'new-result.pdf';
        $PDFWriter->save($filePdf); 
        ///////////////// PDF ///////////////////

        rename($name, $name); // public folder
        // rename($name, storage_path()."\\".$name);

        // $file= storage_path(). "/word/{$name}";
        // $file= storage_path(). "\{$name}";
        $fileWord= $name;

        ob_end_clean();

        // return response()->download($fileWord, $name);
        return response()->download($filePdf, $name);
    }
}