<?php

// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Google\Client as GoogleClient; // For Google API Client
use Google\Service\Sheets; // For Google Sheets service
// use App\Models\ImportedEmail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailConfiguration;
use Illuminate\Support\Facades\Hash;

class EmailController extends Controller
{
    public function form()
    {
        return view('loginpage');
    }

    public function data(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        $User = User::where('email', $credentials['email'])->first();
    
        if ($User && Hash::check($credentials['password'], $User->password)) {
            // session(['user' => $User->name]);  
            // @print_r("login sucessful.");
            Auth::guard('admin')->login($User); 
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.login')->with('error','Either password or email is incorrect');
        }
    }
    
    public function index(){
        return view("dashboard");
    }
    public function change_email(){
        $activeEmail = EmailConfiguration::where('status', 1)->first();

        // Get all other emails except the active one
        $emails = EmailConfiguration::where('status', '!=', 1)->get();

        return view('changeemail', compact('activeEmail', 'emails'));
    }
    public function logout()
    {
        Auth::guard('admin')->logout(); // Log out using the 'admin' guard
        return redirect()->route('admin.login'); // Redirect to login page
    }

    public function showComposePage()
    {
        return view('compose');
    }
    public function importGoogleSheet(Request $request)
    {
        // Debug: Log the incoming request
        \Log::debug('Import Google Sheet Request:', $request->all());
        
        // Debug: Verify file exists
        $credPath = storage_path('app/google-credentials.json');
        \Log::debug('Credentials path: '.$credPath);
        \Log::debug('File exists: '.(file_exists($credPath) ? 'YES' : 'NO'));
        
        // Validate Google Sheet URL
        $validator = Validator::make($request->all(), [
            'google_sheet_url' => 'required|url|regex:/\/spreadsheets\/d\//'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Extract Sheet ID from URL
            $sheetId = $this->extractSheetId($request->google_sheet_url);
            
            // Get data from Google Sheets
            $emails = $this->fetchGoogleSheetData($sheetId);

            // Initialize an empty array for errors
            $errors = [];

            // Validate emails
            foreach ($emails as $index => $email) {
                // Ensure $email is a string (if it's not, you might want to skip or handle it differently)
                if (is_string($email)) {
                    // Split emails if they're in a comma-separated format
                    $emailArray = explode(',', $email);

                    // Check if any email is not in correct format or is a duplicate
                    foreach ($emailArray as $key => $singleEmail) {
                        $trimmedEmail = trim($singleEmail);

                        // Check if email format is valid
                        if (!filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = "Invalid email format at line " . ($index + 1);
                        }

                        // Check for duplicates
                        if (in_array($trimmedEmail, array_slice($emailArray, 0, $key))) {
                            $errors[] = "Duplicate email found at line " . ($index + 1);
                        }
                    }
                } else {
                    // Handle the case where $email is not a string (you might want to log or skip this)
                    $errors[] = "Invalid email format at line " . ($index + 1) . " (not a string).";
                }
            }

            // Return errors and the emails to the view
            return view('emailsrecipients', compact('emails', 'errors'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to import: ' . $e->getMessage())
                ->withInput();
        }
    }


    private function extractSheetId($url)
    {
        preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $url, $matches);
        if (!isset($matches[1])) {
            throw new \Exception('Invalid Google Sheet URL. Sheet ID not found.');
        }
        return $matches[1] ?? null;
    }

    private function fetchGoogleSheetData($sheetId)
    {


        $client = new GoogleClient(); 
        $credPath = str_replace('\\', '/', storage_path('app/google-credentials.json'));
        $credPath = storage_path('app/google-credentials.json');
        $client->setAuthConfig($credPath);


       

        $client->addScope(Sheets::SPREADSHEETS_READONLY);
        
    

        $service = new Sheets($client);
        $range = 'Sheet1!A2:B'; // Adjust range as needed
        $response = $service->spreadsheets_values->get($sheetId, $range);
    
        $values = $response->getValues();
    
        if (empty($values)) {
            throw new \Exception('No data found in the Google Sheet.');
        }
        
        return array_map(function ($row) {
            return [
                'name' => $row[0] ?? null,
                'email' => $row[1] ?? null
            ];
        }, $values);
    }
   

    // private function storeEmails($emails)
    // {
    //     $chunks = array_chunk($emails, 100); // Batch insert 100 records at a time
        
    //     foreach ($chunks as $chunk) {
    //         ImportedEmail::insert(array_filter($chunk, function ($item) {
    //             return filter_var($item['email'], FILTER_VALIDATE_EMAIL);
    //         }));
    //     }
    // }
    public function showEmails()
{
    $emails = session('emails', []);
    $errors = [];

    // Validate emails
    foreach ($emails as $index => $email) {
        // Split emails if they're in a comma-separated format
        $emailArray = explode(',', $email);

        // Check if any email is not in correct format or is a duplicate
        foreach ($emailArray as $key => $singleEmail) {
            $trimmedEmail = trim($singleEmail);

            // Check if email format is valid
            if (!filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format at line " . ($index + 1);
            }

            // Check for duplicates
            if (in_array($trimmedEmail, array_slice($emailArray, 0, $key))) {
                $errors[] = "Duplicate email found at line " . ($index + 1);
            }
        }
    }

    // Return errors and the emails to view
    return view('emailsrecipients', compact('emails', 'errors'));
}


}

