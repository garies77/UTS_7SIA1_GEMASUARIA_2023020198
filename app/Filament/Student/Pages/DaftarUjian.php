<?php

namespace App\Filament\Student\Pages;

use App\Models\ExamResult;
use App\Models\Subject;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class DaftarUjian extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Daftar Ujian (CBT)';

    protected static ?string $title = 'Daftar Ujian & Mata Pelajaran';

    protected static ?string $slug = 'ujian';

    protected string $view = 'filament.student.pages.daftar-ujian';

    public function getViewData(): array
    {
        $subjects = Subject::where('is_active', true)
            ->withCount('questions')
            ->get();

        $userResults = ExamResult::where('user_id', Auth::id())
            ->get()
            ->keyBy('subject_id');

        return [
            'subjects'    => $subjects,
            'userResults' => $userResults,
        ];
    }
}
