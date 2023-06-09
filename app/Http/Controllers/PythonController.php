<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Models\YourModel;

class PythonController extends Controller
{
    public function executePythonAndSearch(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('images');

        // Update the image path to include the full storage path
        $imageFullPath = storage_path('app/' . $imagePath);

        $process = new Process(['/usr/local/bin/python/3.7', '/Applications/MAMP/htdocs/laravel/test-app/app/Python/modelinfo.py', $imageFullPath]);

        try {
            $process->mustRun();

            $output = trim($process->getOutput());

            $results = YourModel::where('number', $output)->get();

            // Retrieve additional information from the matching record
            $record = YourModel::where('number', $output)->first();

            // Prepare the search results to be sent back as JSON
            $searchResults = [
                'results' => $results,
                'output' => $output,
                'record' => $record,
            ];

            // Return the search results as JSON
            return response()->json($searchResults);
        } catch (ProcessFailedException $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
