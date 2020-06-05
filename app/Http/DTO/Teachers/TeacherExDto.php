<?php


namespace App\Http\DTO\Teachers;


class TeacherExDto extends TeacherDto
{
    public function getAbout()
    {
        return $this->teacher->about;
    }

    public function getLinks()
    {
        return [
            'yt' => $this->teacher->link_yt,
            'web' => $this->teacher->link_web,
            'vk' => $this->teacher->link_vk,
            'fb' => $this->teacher->link_fb,
            'linkedIn' => $this->teacher->link_linked_in,
            'twitter' => $this->teacher->link_twitter,
        ];
    }
}
