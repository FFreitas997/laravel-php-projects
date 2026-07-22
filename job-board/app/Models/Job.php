<?php

namespace App\Models;

use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Job
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $salary
 * @property string $location
 * @property string $category
 * @property string $experience
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Job extends Model
{
    /** @use HasFactory<JobFactory> */
    use HasFactory;

    // Define the table name for the Job model
    protected $table = 'job_posts';

    // Define the possible experience levels for a job
    public static array $experience = ['entry', 'mid', 'senior'];

    // Define the possible categories for a job
    public static array $category = ['IT', 'Finance', 'Healthcare', 'Education', 'Marketing'];
}
