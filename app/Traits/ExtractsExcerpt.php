<?php

namespace App\Traits;

trait ExtractsExcerpt
{
    public function excerpt()
    {
        foreach ($this->sections as $section) {
            if ($section['type'] == 'paragraph-section' && $section['description']['text']) {
                return substr($section['description']['text'], 0, 250);
            }
        }
        return null;
    }
}
