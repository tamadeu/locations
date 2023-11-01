<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function getStoragePath()
    {
        return storage_path('data/');
    }

    public function get($nome)
    {
        $caminho = $this->getStoragePath() . $nome . '.json';
        if (file_exists($caminho)) {
            return json_decode(file_get_contents($caminho));
        }
        return null;
    }
}
