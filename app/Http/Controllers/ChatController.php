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
use League\Csv\ResultSet;
use Illuminate\Support\Facades\Response;

class ChatController extends Controller
{

    private function generateProcessedCSV($originalFilePath)
    {
        try {
            $uniqueRowCount = CsvData1::where('file_path', $originalFilePath)->distinct('row')->count();
            // Fetch data from CsvData1 model based on filename

            $originalFilename = pathinfo($originalFilePath, PATHINFO_FILENAME);

            // Create a new CSV file for processed data with the original filename
            $processedFilePath = storage_path("app/{$originalFilename}_processed.csv");
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
    public function downloadProcessedFile($filename) {
        $filePath = storage_path("app/{$filename}.csv");
        return response()->download($filePath, "{$filename}_processed.csv");
    }

    public function sendToOpenAIDoc3(Request $request)
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
            $csvData = new CsvData1([
                'row' => 0, // Adding 1 to convert from 0-based index to 1-based index
                'col' => 0,
                'prompt' =>  'Result' ,
                'data' =>  'Result' ,
                'file_path' => $filePath1,
                'created_by' => $user->id,
            ]);
            $csvData->save();
            foreach ($csv1 as $rowIndex => $row1) {
                foreach ($csv2 as $colIndex => $row2) {
                    $col = 1;

                    foreach ($row1 as $colValue1) {
                        $row = 0;

                        foreach ($row2 as $colValue2) {
                            // Process and store CSV data in the database
                            if (isset($colValue1) && trim($colValue1) !== '' && isset($colValue2) && trim($colValue2) !== '') {
                                try {
                                    // Uncomment the following lines to make OpenAI API requests

                                    $result = OpenAI::completions()->create([
                                        'max_tokens' => 100,
                                        'model' => 'gpt-3.5-turbo-instruct',
                                        'prompt' => $request->input('prompt') . ' ' . $colValue1 . ' and ' . $colValue2,
                                    ]);

                                    if ( $row === 0) {

                                        $csvData = new CsvData1([
                                            'row' => $row, // Adding 1 to convert from 0-based index to 1-based index
                                            'col' => $col,
                                            'prompt' =>  $colValue1 ,
                                            'data' =>  $colValue1 ,
                                            'file_path' => $filePath1,
                                            'created_by' => $user->id,
                                        ]);
                                        $csvData->save();
                                    }
                                    if ( $col === 1) {

                                        $csvData = new CsvData1([
                                            'row' => $row+1, // Adding 1 to convert from 0-based index to 1-based index
                                            'col' => $col,
                                            'prompt' =>  $colValue2 ,
                                            'data' =>  $colValue2 ,
                                            'file_path' => $filePath1,
                                            'created_by' => $user->id,
                                        ]);
                                        $csvData->save();
                                    }


                                    $csvData = new CsvData1([
                                        'row' => $row+1, // Adding 1 to convert from 0-based index to 1-based index
                                        'col' => $col+1,
                                        'prompt' => $request->input('prompt') . ' ' . $colValue1 . ' ' . $colValue2,
//                                        'data' => $request->input('prompt') . ' ' . $colValue1 . ' ' . $colValue2, // Change to $result->choices[0]->text if using API response
                                        'data' => $result->choices[0]->text, // Change to $result->choices[0]->text if using API response
                                        'file_path' => $filePath1, // or $filePath2, depending on your needs
                                        'created_by' => $user->id,
                                    ]);
                                    $csvData->save();
                                } catch (\Exception $e) {
                                    // Handle API request errors
                                    \Log::error('Error processing CSV row: ' . $e->getMessage());
                                }
                                // Uncomment the following line if you want to add a delay between OpenAI API hits
                                 sleep(25);
                            } $row++;

                        }
                        $col++;
                    }
                }
            }
            $processedFilePath = $this->generateProcessedCSV($filePath1); //
            $originalFilename = pathinfo($processedFilePath, PATHINFO_FILENAME);

            // You can redirect to a page with a download link or directly force download
            return response()->json($originalFilename, 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json('Error: ' . $e->getMessage(), 500);
        }
    }

    public function sendToOpenAIDoc4(Request $request)
    {
        $user = Auth::user();
        try {
            set_time_limit(0); // Disable time limit for this function

            $request->validate([
                'csvFile1' => 'required|mimes:csv,txt|max:10240',
                'csvFile2' => 'required|mimes:csv,txt|max:10240',
                'criteriaFile' => 'required|mimes:csv,txt|max:10240',
                'prompt' => 'required|string',
            ]);

            // Read CSV files
            $file1 = $request->file('csvFile1');
            $file2 = $request->file('csvFile2');
            $file3 = $request->file('criteriaFile');

            $filePath1 = $file1->storeAs('csv_files', now()->format('YmdHis') . 'uploaded_file1.csv');
            $filePath2 = $file2->storeAs('csv_files', now()->format('YmdHis') . 'uploaded_file2.csv');
            $filePath3 = $file3->storeAs('criteriaFile', now()->format('YmdHis') . 'uploaded_file3.csv');

            $csv1 = Reader::createFromPath(storage_path("app/{$filePath1}"), 'r');
            $csv2 = Reader::createFromPath(storage_path("app/{$filePath2}"), 'r');
            $csv3 = Reader::createFromPath(storage_path("app/{$filePath3}"), 'r');


            $brands = iterator_to_array($csv1->getRecords());
            $criteria = iterator_to_array($csv3->getRecords());

            // Save the combined values to a new CSV file
            $timestamp = now()->format('YmdHis');
            $combinedFilePath = storage_path("app/csv_files/combined_file_{$timestamp}.csv");
            $combinedCsv = Writer::createFromPath($combinedFilePath, 'w+');

            // Generate all possible combinations and write to the file
            foreach ($brands as $brand) {
                foreach ($criteria as $criterion) {
                    $dataRow = [];
                    foreach ($brand as $brandItem) {
                        foreach ($criterion as $criterionItem) {
                            $dataRow[] = $brandItem . '. Assume the following criteria: ' . $criterionItem  ;
                        }
                    }
                    $combinedCsv->insertOne($dataRow);
                }
            }
            $combinedCsv = Reader::createFromPath($combinedFilePath, 'r');

            // Validate the number of rows and columns
            $rows1 = iterator_count($csv1->getIterator());
            $rows2 = iterator_count($csv2->getIterator());

            if ($rows1 !== $rows2) {
                return response()->json('Error: Both CSV files must have the same number of rows.', 400);
            }
            $csvData = new CsvData1([
                'row' => 0, // Adding 1 to convert from 0-based index to 1-based index
                'col' => 0,
                'prompt' =>  'Result' ,
                'data' =>  'Result' ,
                'file_path' => $filePath1,
                'created_by' => $user->id,
            ]);
            $csvData->save();
            foreach ($combinedCsv as $rowIndex => $row1) {
                foreach ($csv2 as $colIndex => $row2) {
                    $col = 1;

                    foreach ($row1 as $colValue1) {
                        $row = 0;

                        foreach ($row2 as $colValue2) {
                            // Process and store CSV data in the database
                            if (isset($colValue1) && trim($colValue1) !== '' && isset($colValue2) && trim($colValue2) !== '') {
                                try {
                                    // Uncomment the following lines to make OpenAI API requests

                                    $result = OpenAI::completions()->create([
                                        'max_tokens' => 100,
                                        'model' => 'gpt-3.5-turbo-instruct',
                                        'prompt' => $request->input('prompt') . ' ' . $colValue1 . ', ' . $colValue2,
                                    ]);

                                    if ( $row === 0) {

                                        $csvData = new CsvData1([
                                            'row' => $row, // Adding 1 to convert from 0-based index to 1-based index
                                            'col' => $col,
                                            'prompt' =>  $colValue1 ,
                                            'data' =>  $colValue1 ,
                                            'file_path' => $filePath1,
                                            'created_by' => $user->id,
                                        ]);
                                        $csvData->save();
                                    }
                                    if ( $col === 1) {

                                        $csvData = new CsvData1([
                                            'row' => $row+1, // Adding 1 to convert from 0-based index to 1-based index
                                            'col' => $col,
                                            'prompt' =>  $colValue2 ,
                                            'data' =>  $colValue2 ,
                                            'file_path' => $filePath1,
                                            'created_by' => $user->id,
                                        ]);
                                        $csvData->save();
                                    }


                                    $csvData = new CsvData1([
                                        'row' => $row+1, // Adding 1 to convert from 0-based index to 1-based index
                                        'col' => $col+1,
                                        'prompt' => $request->input('prompt') . ' ' . $colValue1 . ', ' . $colValue2,
                                        'data' => $result->choices[0]->text, // Change to $result->choices[0]->text if using API response
//                                        'data' => $request->input('prompt') . ' ' . $colValue1 . ', ' . $colValue2, // Change to $result->choices[0]->text if using API response
                                        'file_path' => $filePath1, // or $filePath2, depending on your needs
                                        'created_by' => $user->id,
                                    ]);
                                    $csvData->save();
                                } catch (\Exception $e) {
                                    // Handle API request errors
                                    \Log::error('Error processing CSV row: ' . $e->getMessage());
                                }
                                // Uncomment the following line if you want to add a delay between OpenAI API hits
                                 sleep(25);
                            } $row++;

                        }
                        $col++;
                    }
                }
            }
            $processedFilePath = $this->generateProcessedCSV($filePath1); //
            $originalFilename = pathinfo($processedFilePath, PATHINFO_FILENAME);

            // You can redirect to a page with a download link or directly force download
            return response()->json($originalFilename, 200);

        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json('Error: ' . $e->getMessage(), 500);
        }
    }
}

