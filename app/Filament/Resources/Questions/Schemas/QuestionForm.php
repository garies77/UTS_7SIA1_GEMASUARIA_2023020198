<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Models\Subject;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Soal')
                    ->columns(2)
                    ->schema([
                        Select::make('subject_id')
                            ->label('Mata Pelajaran')
                            ->options(Subject::query()->where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Select::make('correct_answer')
                            ->label('Kunci Jawaban Benar')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'C' => 'C',
                                'D' => 'D',
                                'E' => 'E',
                            ])
                            ->required(),

                        Textarea::make('payload')
                            ->label('Pertanyaan / Isi Soal')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Pembahasan / Catatan')
                            ->nullable()
                            ->rows(3)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),

                Section::make('Pilihan Jawaban (A - E)')
                    ->schema([
                        Repeater::make('answers')
                            ->relationship('answers')
                            ->schema([
                                Select::make('letter')
                                    ->label('Huruf Opsi')
                                    ->options([
                                        'A' => 'A',
                                        'B' => 'B',
                                        'C' => 'C',
                                        'D' => 'D',
                                        'E' => 'E',
                                    ])
                                    ->required(),
                                TextInput::make('text')
                                    ->label('Teks Pilihan')
                                    ->required()
                                    ->columnSpan(2),
                            ])
                            ->columns(3)
                            ->defaultItems(5)
                            ->minItems(2)
                            ->maxItems(5),
                    ]),
            ]);
    }
}
