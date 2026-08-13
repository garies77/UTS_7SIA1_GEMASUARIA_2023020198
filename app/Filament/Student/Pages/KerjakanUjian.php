<?php

namespace App\Filament\Student\Pages;

use App\Models\ExamResult;
use App\Models\Question;
use App\Models\Subject;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class KerjakanUjian extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'kerjakan/{subject}';

    protected string $view = 'filament.student.pages.kerjakan-ujian';

    public Subject $subject;

    public array $questions = [];

    public int $currentIndex = 0;

    public array $selectedAnswers = [];

    public array $doubtfulAnswers = [];

    public int $remainingSeconds = 7200; // 120 menit

    public bool $isCompleted = false;

    public ?array $finalResult = null;

    public function mount(Subject $subject): void
    {
        $this->subject = $subject;

        $dbQuestions = Question::where('subject_id', $subject->id)
            ->where('is_active', true)
            ->with(['answers' => function ($query) {
                $query->where('is_active', true)->orderBy('letter');
            }])
            ->get();

        foreach ($dbQuestions as $index => $q) {
            $this->questions[] = [
                'id'             => $q->id,
                'number'         => $index + 1,
                'payload'        => $q->payload,
                'correct_answer' => $q->correct_answer,
                'description'    => $q->description,
                'options'        => $q->answers->pluck('text', 'letter')->toArray(),
            ];
        }
    }

    public function selectOption(string $letter): void
    {
        if ($this->isCompleted || ! isset($this->questions[$this->currentIndex])) {
            return;
        }

        $questionId = $this->questions[$this->currentIndex]['id'];
        $this->selectedAnswers[$questionId] = $letter;
    }

    public function toggleDoubtful(): void
    {
        if ($this->isCompleted || ! isset($this->questions[$this->currentIndex])) {
            return;
        }

        $questionId = $this->questions[$this->currentIndex]['id'];
        $current = $this->doubtfulAnswers[$questionId] ?? false;
        $this->doubtfulAnswers[$questionId] = ! $current;
    }

    public function goToNext(): void
    {
        if ($this->currentIndex < count($this->questions) - 1) {
            $this->currentIndex++;
        }
    }

    public function goToPrev(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentIndex = $index;
        }
    }

    public function submitExam(): void
    {
        if ($this->isCompleted) {
            return;
        }

        $total = count($this->questions);
        $correct = 0;
        $wrong = 0;

        foreach ($this->questions as $q) {
            $qId = $q['id'];
            $chosen = $this->selectedAnswers[$qId] ?? null;

            if ($chosen && strtoupper($chosen) === strtoupper($q['correct_answer'])) {
                $correct++;
            } else {
                $wrong++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100, 1) : 0;

        $examResult = ExamResult::create([
            'user_id'         => Auth::id(),
            'subject_id'      => $this->subject->id,
            'total_questions' => $total,
            'correct_count'   => $correct,
            'wrong_count'     => $wrong,
            'score'           => $score,
            'answers_json'    => $this->selectedAnswers,
            'completed_at'    => now(),
        ]);

        $this->isCompleted = true;
        $this->finalResult = [
            'total'   => $total,
            'correct' => $correct,
            'wrong'   => $wrong,
            'score'   => $score,
        ];
    }
}
