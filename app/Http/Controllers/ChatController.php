<?php

// app/Http/Controllers/ChatController.php

namespace App\Http\Controllers;

use App\Models\ApiLog;
use App\Models\CsvData1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use League\Csv\Writer;
use OpenAI\Laravel\Facades\OpenAI;
use League\Csv\Reader;
use Illuminate\Support\Facades\Response;

class ChatController extends Controller
{
    // ... (your existing code)

    public function sendToOpenAI(Request $request)
    {
        $user = Auth::user();
        try {
            set_time_limit(0); // Disable time limit for this function

            $request->validate([
                'csvFile' => 'required|mimes:csv,txt|max:10240', // Adjust max file size as needed
                'prompt' => 'required|string',
            ]);

            $file = $request->file('csvFile');
            $filePath = $file->storeAs('csv_files',  now()->format('YmdHis') .'uploaded_file.csv');

            // Read CSV file
            $csv = Reader::createFromPath(storage_path("app/{$filePath}"), 'r');

            // Loop through each row in the CSV file
            foreach ($csv as $index => $row) {


                // Loop through each column in the row
                foreach ($row as $colIndex => $colValue) {
                    // Process and store CSV data in the database
                    if (isset($colValue)  && trim($colValue) !== '') {
                        try {

                            $result = OpenAI::completions()->create([
                                'max_tokens' => 100,
                                'model' => 'gpt-3.5-turbo-instruct',
                                'prompt' => $request->input('prompt').' '.$colValue,
                            ]);
                            $csvData = new CsvData1([
                                'row' => $index,
                                'col' => $colIndex + 1, // Assuming columns start from 1
                                'prompt' => $request->input('prompt').' '.$colValue,
                                'data' => $result->choices[0]->text,
                                'file_path' => $filePath,
                                'created_by' => $user->id,
                            ]);

                            $csvData->save();
                        } catch (\Exception $e) {
                            new ApiLog([
                                'method' => 'sendToOpenAI',
                                'response_content' => $e->getMessage(),
                            ]);
                            // Handle API request errors
                            \Log::error('Error processing CSV row: ' . $e->getMessage());
                        }
                        sleep(25); // Add a delay between OpenAI API hits
                    }
                }
            }
            $processedFilePath = $this->generateProcessedCSV($filePath);

            // You can redirect to a page with a download link or directly force download
            return response()->json('success',200);

        } catch (\Exception $e) {
            return response()->json('error',500);

        }
    }

    private function generateProcessedCSV($originalFilePath)
    {
        try {
            $uniqueRowCount = CsvData1::where('file_path', $originalFilePath)->distinct('row')->count();
            // Fetch data from CsvData1 model based on filename

            // Create a new CSV file for processed data
            $processedFilePath = storage_path('app/processed_file.csv');
            $processedCSV = Writer::createFromPath($processedFilePath, 'w+');

            for ($r=0;$r<=$uniqueRowCount;$r++) {
                // Fetch data for the current row
                $rowData = CsvData1::where('file_path', $originalFilePath)
                    ->where('row', $r)
                    ->pluck('data')
                    ->toArray();

                // Insert data for the current row into the processed CSV
                $processedCSV->insertOne($rowData);
            }

            return $processedFilePath;
        }catch (\Exception $e) {
            throw $e;
        }
    }
    public function downloadProcessedFile() {
        $filePath = storage_path("app/processed_file.csv");
        return response()->download($filePath, 'processed_file.csv');
    }

    public function sendToOpenAIDoc2(Request $request)
    {
        $user = Auth::user();
        try {
            set_time_limit(0); // Disable time limit for this function

            $request->validate([
                'csvFile1' => 'required|mimes:csv,txt|max:10240',
                'csvFile2' => 'required|mimes:csv,txt|max:10240',
                'prompt' => 'required|string',
            ]);

            // Read CSV files
            $file1 = $request->file('csvFile1');
            $file2 = $request->file('csvFile2');

            $filePath1 = $file1->storeAs('csv_files', now()->format('YmdHis') . 'uploaded_file1.csv');
            $filePath2 = $file2->storeAs('csv_files', now()->format('YmdHis') . 'uploaded_file2.csv');

            $csv1 = Reader::createFromPath(storage_path("app/{$filePath1}"), 'r');
            $csv2 = Reader::createFromPath(storage_path("app/{$filePath2}"), 'r');

            // Validate the number of rows and columns
            $rows1 = iterator_count($csv1->getIterator());
            $rows2 = iterator_count($csv2->getIterator());

            if ($rows1 !== $rows2) {
                return response()->json('Error: Both CSV files must have the same number of rows.', 400);
            }

            foreach ($csv1 as $index => $row1) {
                $row2 = $csv2->fetchOne($index); // Fetch corresponding row from the second file

                foreach ($row1 as $colIndex => $colValue1) {
                    $colValue2 = $row2[$colIndex];

                    // Process and store CSV data in the database
                    if (isset($colValue1) && trim($colValue1) !== '' && isset($colValue2) && trim($colValue2) !== '') {
                        try {
                            $result = OpenAI::completions()->create([
                                'max_tokens' => 100,
                                'model' => 'gpt-3.5-turbo-instruct',
                                'prompt' => $request->input('prompt') . ' ' . $colValue1 . ' and ' . $colValue2,
                            ]);

                            $csvData = new CsvData1([
                                'row' => $index,
                                'col' => $colIndex + 1, // Assuming columns start from 1
                                'prompt' => $request->input('prompt') . ' ' . $colValue1 . ' and ' . $colValue2,
                                'data' => $result->choices[0]->text,
                                'file_path' => $filePath1, // or $filePath2, depending on your needs
                                'created_by' => $user->id,
                            ]);

                            $csvData->save();
                        } catch (\Exception $e) {
                            // Handle API request errors
                            \Log::error('Error processing CSV row: ' . $e->getMessage());
                        }
                        sleep(25); // Add a delay between OpenAI API hits
                    }
                }
            }

            $processedFilePath = $this->generateProcessedCSV($filePath1); // or $this->generateProcessedCSV($filePath2);

            // You can redirect to a page with a download link or directly force download
            return response()->json('success', 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json('Error: ' . $e->getMessage(), 500);
        }
    }

}

