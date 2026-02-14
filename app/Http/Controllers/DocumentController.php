<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    private const ALLOWED_TYPES = ['contracts', 'cars', 'contacts'];

    public function show(Document $document)
    {
        if (Storage::disk('documents')->exists($document->storage_path)) {
            return response()->file(
                Storage::disk('documents')->path($document->storage_path),
                ['Content-Disposition' => 'filename="'.$document->name.'"']
            );
        }

        abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'document' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx,webp'],
            'documentable_type' => ['required', 'string', Rule::in(self::ALLOWED_TYPES)],
            'documentable_id' => ['required', 'integer'],
        ]);

        $class = $request->get('documentable_type');
        $id = $request->get('documentable_id');

        $file = $request->file('document');
        $internalName = date('Y-m-d-H-i-s').'.'.$file->extension();
        $storagePath = "{$class}/{$id}";

        $file->storeAs($storagePath, $internalName, 'documents');

        $document = Document::create([
            'name' => $file->getClientOriginalName(),
            'internal_name' => $internalName,
            'size' => $file->getSize(),
            'extension' => $file->extension() ?? '',
            'documentable_type' => $class,
            'documentable_id' => $id,
        ]);

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
        Storage::disk('documents')->delete($document->storage_path);

        $document->delete();
        session()->flash('flash.banner', 'Dokument gelöscht.');

        return Redirect::back();
    }
}
