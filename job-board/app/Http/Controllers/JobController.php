<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Job::query();

        $query->when(request('search'), function ($q, $search) {

            $q->where(function ($q) use ($search) {

                $q
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');

            });

        });

        $query->when(request('salary_min'), function ($q, $salaryMin) {
            $q->where('salary', '>=', $salaryMin);
        });

        $query->when(request('salary_max'), function ($q, $salaryMax) {
            $q->where('salary', '<=', $salaryMax);
        });

        $query->when(request('experience'), function ($q, $experience) {
            $q->where('experience', $experience);
        });

        $query->when(request('category'), function ($q, $category) {
            $q->where('category', $category);
        });

        return view('job.index', ['jobs' => $query->get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return view('job.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
