<?php

namespace App\Contracts;

use App\Announcement;

interface AnnouncementRepository
{
    /**
     * Get the most recent announcement notifications for the application.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recent();

    /**
     * Create an application announcement with the given data.
     *
     * @param  \Illuminate\Contracts\Authenticatable
     * @param  array  $data
     * @return \App\Announcement
     */
    public function create($user, array $data);

    /**
     * Update the given announcement with the given data.
     *
     * @param  \App\Announcement  $announcement
     * @param  array  $data
     */
    public function update(Announcement $announcement, array $data);
}
