<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class DocumentController extends Controller
{
    public function show(Document $document)
    {
        if (file_exists($document->path)) {
            header('Content-Disposition: filename="' . $document->name . '"');
            return response()->file($document->path);
        }
        
        abort(404);
    }

    public function store(Request $request, Contract $contract)
    {
        $file = $request->file()['document'];
        $internalName = date('Y-m-d-H-i-s') . '.' . $file->extension();
        $document = Document::create([
            'name' => $file->getClientOriginalName(),
            'internal_name' => $internalName,
            'size' => $file->getSize(),
            'extension' => $file->extension() ?? '',
            'contract_id' => $contract->id,
        ]);
        $file->move(public_path("documents/contracts/{$contract->id}/"), $internalName);

        return [
            'id' => $document->id,
            'name' => $document->name,
            'size' => $document->size,
            'extension' => $document->extension,
            'link' => $document->link,
            'created_at' => $document->created_at,
        ];
    }

    public function destroy(Document $document)
    {
        if (file_exists($document->path)) {
            unlink($document->path);
        }
        $document->delete();
        session()->flash('flash.banner', 'Dokument gelöscht.');
        return Redirect::back();
    }
}
