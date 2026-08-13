<?php

namespace App\Filament\Resources\Subjects\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama mata pelajaran'),

                IconEntry::make('is_active')
                    ->label('Status aktif')
                    ->boolean(),

                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('questions_count')
                    ->label('Jumlah soal')
                    ->state(fn (object $record) => $record->questions()->count() . ' soal'),

                TextEntry::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime('d F Y, H:i:s')
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Terakhir diubah')
                    ->dateTime('d F Y, H:i:s')
                    ->placeholder('-'),
            ]);
    }
}
