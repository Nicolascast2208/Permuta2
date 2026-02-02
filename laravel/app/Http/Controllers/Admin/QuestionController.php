<?php
// app/Http/Controllers/Admin/QuestionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with(['user', 'product', 'parent'])->whereNull('parent_id');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $questions = $query->latest()->paginate(20);

        return view('admin.questions.index', compact('questions'));
    }

    public function show(Question $question)
    {
        $question->load(['user', 'product', 'answers.user']);
        return view('admin.questions.show', compact('question'));
    }

    public function answer(Request $request, Question $question)
    {
        $validated = $request->validate([
            'answer' => 'required|string|max:1000'
        ]);

        // Crear la respuesta como una nueva pregunta con parent_id
        Question::create([
            'product_id' => $question->product_id,
            'user_id' => auth()->id(),
            'parent_id' => $question->id,
            'content' => $validated['answer']
        ]);

        return redirect()->back()->with('success', 'Respuesta publicada correctamente.');
    }

    public function destroy(Question $question)
    {
        // Eliminar también las respuestas si las hay
        $question->answers()->delete();
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Pregunta eliminada correctamente.');
    }
}