<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Component;
use Livewire\WithPagination;

class Questions extends Component
{
    use WithPagination;

    public $answerContent = '';
    public $activeQuestionId = null;

    public function showAnswerForm($questionId)
    {
        $this->activeQuestionId = $questionId;
    }

    public function cancelAnswer()
    {
        $this->activeQuestionId = null;
    }

    public function answerQuestion($questionId)
    {
        $this->validate(['answerContent' => 'required|string']);

        Question::create([
            'product_id' => $questionId,
            'user_id' => auth()->id(),
            'parent_id' => $questionId,
            'content' => $this->answerContent
        ]);

        $this->answerContent = '';
        $this->activeQuestionId = null;
    }

    public function render()
    {
        $questions = Question::with(['product', 'user'])
            ->whereHas('product', fn($q) => $q->where('user_id', auth()->id()))
            ->whereNull('parent_id')
            ->paginate(10);

        return view('livewire.questions', compact('questions'));
    }
}