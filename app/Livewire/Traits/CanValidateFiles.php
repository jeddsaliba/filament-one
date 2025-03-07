<?php

namespace App\Livewire\Traits;

trait CanValidateFiles
{
    protected $validAudioExtensions = [
        'm4a',  // For audio/m4a
        'wav',  // For audio/wav
        'mp3',  // For audio/mpeg (commonly associated with MP3 files)
        'ogg',  // For audio/ogg
        'aac',  // For audio/aac
        'flac', // For audio/flac
        'midi', // For audio/midi (alternative extension: .mid)
    ];

    protected $validDocumentExtensions = [
        'pdf',    // For application/pdf
        'doc',    // For application/msword
        'docx',   // For application/vnd.openxmlformats-officedocument.wordprocessingml.document
        'csv',    // For text/csv
        'txt',    // For text/plain
        'xls',    // For application/vnd.ms-excel
        'xlsx',   // For application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
        'ppt',    // For application/vnd.ms-powerpoint
        'pptx',   // For application/vnd.openxmlformats-officedocument.presentationml.presentation
    ];

    protected $validImageExtensions = [
        'png',  // For image/png
        'jpeg', // For image/jpeg
        'jpg',  // For image/jpg
        'gif',  // For image/gif
    ];

    protected $validVideoExtensions = [
        'mp4',      // For video/mp4
        'avi',      // For video/avi
        'mov',      // For video/quicktime
        'webm',     // For video/webm
        'mkv',      // For video/x-matroska
        'flv',      // For video/x-flv
        'mpeg',     // For video/mpeg
        'mpg',      // For video/mpeg (alternative extension)
    ];

    public function validateAudio(string $imagePath): bool
    {
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

        if (in_array($extension, $this->validAudioExtensions)) {
            return true;
        }

        return false;
    }

    public function validateDocument(string $documentPath): bool
    {
        $extension = strtolower(pathinfo($documentPath, PATHINFO_EXTENSION));

        if (in_array($extension, $this->validDocumentExtensions)) {
            return true;
        }

        return false;
    }

    public function validateImage(string $imagePath): bool
    {
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

        if (in_array($extension, $this->validImageExtensions)) {
            return true;
        }

        return false;
    }

    public function validateVideo(string $imagePath): bool
    {
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

        if (in_array($extension, $this->validVideoExtensions)) {
            return true;
        }

        return false;
    }
}
