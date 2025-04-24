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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\EmailLog;


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

            // Validate emails
            $errors = [];
            $allEmails = []; // Track all valid emails for duplicate checking
    
            foreach ($emails as $index => $emailLine) {
                // Check if line contains a comma
                if (!filter_var($emailLine['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = sprintf("B %d", $index + 2);
                    continue;
                }
                if (strpos($emailLine['email'], ',') !== false) {
                    $errors[] = sprintf("B %d", $index + 2);
                    continue;
                }
            }

            if (!empty($errors)) {
                return redirect()->back()
                    ->withErrors($errors)
                    ->withInput()
                    ->with('error_details', $errors); // Additional error details
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
        $allEmails = []; // Track all valid emails for duplicate checking
    
        foreach ($emails as $index => $emailLine) {
            // Check if line contains a comma
            if (strpos($emailLine, ',') !== false) {
                $errors[] = "Invalid email format: Comma found at line " . ($index + 1);
                continue;
            }
    
            $trimmedEmail = trim($emailLine);
    
            // Validate email format
            if (!filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format at line " . ($index + 1);
            } else {
                // Check for duplicates across all emails
                if (in_array($trimmedEmail, $allEmails)) {
                    $errors[] = "Duplicate email found at line " . ($index + 1);
                } else {
                    $allEmails[] = $trimmedEmail;
                }
            }
        }
    
        return view('emailsrecipients', compact('emails', 'errors'));
    }

    // app/Http/Controllers/EmailController.php
    public function sendBatch(Request $request)
    {
        // 1. Get emails from request
        $emails = array_map(function($e) {
            return json_decode($e, true);
        }, $request->input('emails', []));
    
        // 2. Simple validation
        if (empty($emails)) {
            return back()->with('error', 'No emails to send!');
        }

        $smtpConfigs = DB::table('email_configurations')
        ->where('status', true)
        ->orderBy('created_at')
        ->get();

        if ($smtpConfigs->isEmpty()) {
            return back()->with('error', 'No active email configurations found');
        }
        // $totalEmails = 100;
        // $emails = $this->getEmailList($totalEmails); // Implement your email source
    
        // 3. Send in batches of 10 with SMTP rotation
        $sentCount = 0;
        $currentConfigIndex = 0;

        $totalEmails = count($emails);

    
        foreach (array_chunk($emails, 10) as $batch) {
            // Rotate SMTP config every batch
            $config = $smtpConfigs[$currentConfigIndex % count($smtpConfigs)];
            Log::info('Loaded SMTP configurations:', $smtpConfigs->toArray());
            Log::info("Using SMTP configuration: " . json_encode($config));
            
            // Update live configuration
            $this->setMailConfig($config);
            
            // Send batch
            try {
                foreach ($batch as $emailData) {

                    Log::info('Preparing to send email to:', ['email' => $emailData['email'], 'name' => $emailData['name']]);
                    $trackingId = uniqid();

                    $trackingPixel = route('track.open', ['id' => $trackingId]);
                    $trackedLink = route('track.click', [
                        'id' => $trackingId,
                        'url' => urlencode('https://nexonpackaging.com')
                    ]);
                    // $emailData = json_decode($emailData);
                    Mail::send('emails.template', [
                        'name' => $emailData['name'],
                        'tracking_pixel' => $trackingPixel,
                        'tracked_link' => $trackedLink,
                        // 'content' => "Dear {$emailData['name']},...", // Your custom content
                        // 'unsubscribeLink' => route('unsubscribe', $unsubscribeToken),
                        'senderName' => $config->name,
                        'senderRole' => 'Customer Relations Manager',
                        'companyWebsite' => 'https://nexonpackaging.com',
                        'disclaimer' => "Disclaimer: This email and any attachments are intended solely for the recipient(s) and may contain confidential or privileged information. If you are not the intended recipient, please delete this email immediately and notify the sender. Any unauthorized use, disclosure, or distribution is prohibited. While we take precautions to ensure our emails are free from viruses or malware, we recommend you perform your own checks before opening attachments. We accept no liability for any loss or damage arising from this email. If you no longer wish to receive emails from us, please let us know."
                    ], function ($message) use ($emailData, $config) {
                        $message->to($emailData['email']) 
                                ->subject('Best Pricing & Premium Packaging Guaranteed')
                                ->from($config->mail_from_address);
                    });
                    Log::info("Email sent to: {$emailData['email']} with subject: 'Best Pricing & Premium Packaging Guaranteed'");
                    
                    EmailLog::create([
                        'recipients' => $emailData['email'],          // receiver's email
                        'compaign_id' => 0,
                        'status' => 'sent',
                        'subject' => 'Best Pricing & Premium Packaging Guaranteed',           // default status
                        'tracking_id' => $trackingId,            // for tracking pixel/link
                    ]);

                    $sentCount++;
                    $currentProgress = $sentCount + ($currentConfigIndex * 10);
                    // print_r("Sent {$currentProgress}/{$totalEmails} emails");
                    // Or with printf for formatted output:
                    echo "<script>console.clear();</script>";
                    printf("Sent %d/%d emails\n", $currentProgress, $totalEmails);
                    usleep(5000000); // 0.5 second delay between emails
                    // usleep(5000000); // 5-second delay between emails
                    

                

                }
              
    
            } catch (\Exception $e) {
                // Log error and rotate config
                Log::error("Email send failed with config {$config->name}: " . $e->getMessage());
                return redirect()->route('emails.compose')->with('error', 'Error: ' . $e->getMessage());
                // return redirect()->route('emails.compose')
                // ->withInput()
                // ->with('success', "Successfully sent {$sentCount}/{$totalEmails} emails"); // Additional error details
                // Log::error("Email send failed with config {$config->name}: " . $e->getMessage());
            }

            
            
            
            $currentConfigIndex++;
            sleep(10); // 10 second delay between batches
        }
    
        if (!empty($errors)) {
            return redirect()->route('emails.compose')
                ->withInput()
                ->with('success', "Successfully sent {$sentCount}/{$totalEmails} emails"); // Additional error details
        }
        else {
            Log::info("Successfully sent {$sentCount}/{$totalEmails} emails");

            return redirect()->route('emails.compose')
                ->withInput()
                ->with('success', "Successfully sent {$sentCount}/{$totalEmails} emails"); // Additional error details
        }
        
        
    }
//     public function sendBatch(Request $request)
// {
//     // Enable detailed error reporting
//     ini_set('display_errors', 1);
//     error_reporting(E_ALL);

//     // Validate input
//     $emails = $request->input('emails', []); // Directly use the array
//     $emails = $request->input('emails', []);
//     // if (empty($emails)) {
//     //     return response()->json(['error' => 'No emails provided'], 400);
//     // }

//     // Get active SMTP configurations
//     $smtpConfigs = DB::table('email_configurations')
//         ->where('status', true)
//         ->orderBy('created_at')
//         ->get();

//     if ($smtpConfigs->isEmpty()) {
//         return response()->json(['error' => 'No active email configurations'], 400);
//     }

//     $sentCount = 0;
//     $errors = [];
//     $currentConfigIndex = 0;

//     foreach (array_chunk($emails, 10) as $batchIndex => $batch) {
//         $config = $smtpConfigs[$currentConfigIndex % count($smtpConfigs)];
//         $this->setMailConfig($config);

//         foreach ($batch as $emailIndex => $emailData) {
//             try {
//                 $emailData = json_decode($emailData);
                
//                 Mail::send('emails.template', [
//                     'name' => $emailData->name,
//                     'senderName' => $config->name,
//                     'senderRole' => 'Customer Relations Manager',
//                     'companyWebsite' => 'https://nexonpackaging.com',
//                     'disclaimer' => "Disclaimer text..."
//                 ], function ($message) use ($emailData, $config) {
//                     $message->to($emailData->email,)
//                             ->subject('Best Pricing & Premium Packaging Guaranteed')
//                             ->from($config->mail_from_address);
//                 });

//                 $sentCount++;
//                 usleep(500000); // 0.5 second delay between emails
//             } catch (\Exception $e) {
//                 $errors[] = "Failed to send to : " . $e->getMessage();
//                 Log::error("Email failed to : " . $e->getMessage());
//             }
//         }

//         $currentConfigIndex++;
//         if ($batchIndex < count($emails) / 10 - 1) {
//             sleep(10); // 10 second delay between batches
//         }
//     }

//     if (!empty($errors)) {
//         return response()->json([
//             'message' => "Sent {$sentCount}/" . count($emails) . " emails",
//             'errors' => $errors
//         ], 206); // Partial content
//     }

//     return response()->json([
//         'message' => "Successfully sent {$sentCount} emails"
//     ]);
// }

    private function setMailConfig($config)
    {
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $config->mail_host,
            'port' => $config->mail_port,
            'encryption' => $config->mail_scheme,
            'username' => $config->mail_username,
            'password' => $config->mail_password,
            'timeout' => 30,
        ]);
    }


}

