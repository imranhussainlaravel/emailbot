<?php

// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


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

            // Store in database (batch insert)
            $this->storeEmails($emails);

            // Redirect to next step
            return redirect()->route('emails.recipients')->with('success', count($emails) . ' emails imported!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to import: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function extractSheetId($url)
    {
        preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    private function fetchGoogleSheetData($sheetId)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS_READONLY);

        $service = new Sheets($client);
        $range = 'Sheet1!A2:B'; // Adjust range as needed
        $response = $service->spreadsheets_values->get($sheetId, $range);

        return array_map(function ($row) {
            return [
                'email' => $row[0] ?? null,
                'name' => $row[1] ?? null
            ];
        }, $response->getValues());
    }

    private function storeEmails($emails)
    {
        $chunks = array_chunk($emails, 100); // Batch insert 100 records at a time
        
        foreach ($chunks as $chunk) {
            ImportedEmail::insert(array_filter($chunk, function ($item) {
                return filter_var($item['email'], FILTER_VALIDATE_EMAIL);
            }));
        }
    }


}

