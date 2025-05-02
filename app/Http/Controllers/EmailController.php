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
use App\Models\EmailCampaign;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Transport;
// use Symfony\Component\Mailer\Mailer;
use Swift_SmtpTransport;
use Swift_Mailer;

use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;


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
        $emails = EmailConfiguration::where('status', '!=', 0)->get();

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
        $range = 'Sheet1!A2:C'; // Adjust range as needed
        $response = $service->spreadsheets_values->get($sheetId, $range);
    
        $values = $response->getValues();
    
        if (empty($values)) {
            throw new \Exception('No data found in the Google Sheet.');
        }
        
        return array_map(function ($row) {
            return [
                'name' => $row[0] ?? null,
                'email' => $row[1] ?? null,
                'phone' => $row[2] ?? null

            ];
        }, $values);
    }
   
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

    
   
    private function setMailConfig($config)
    {
        // Keep this method for other parts of your application that might use it
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp' => [
                'transport'  => 'smtp',
                'host'       => $config->mail_host,
                'port'       => $config->mail_port,
                'encryption' => $config->mail_scheme,
                'username'   => $config->mail_username,
                'password'   => $config->mail_password,
                'timeout'    => null,
                'auth_mode'  => null,
            ],
            'mail.from.address' => $config->mail_from_address,
            'mail.from.name'    => $config->name,
        ]);

        Mail::purge('smtp');
    }
    
    public function sendBatch(Request $request)
    {
        $batchNumber = (int) $request->query('batch', 1);
        $batchSize = 10;
        ini_set('max_execution_time', 1200);

        

        if ($batchNumber === 1) {
            $campaign = EmailCampaign::create([
                'title' => now()->format('l'),
                'subject' => 'Best Pricing & Premium Packaging Guaranteed',
                'content' => 'Your ',
                'sender_user_id' => auth()->id(),
                'smtp_from_email' => 'sender@example.com',
                'reply_to' => 'reply@example.com',
                'emails_sent' => 0,
                'emails_opened' => 0,
                'emails_bounced' => 0,
                'sent_at' => now(),
            ]);
    
            session(['campaign_id' => $campaign->id]);
        }
    
        $campaignId = session('campaign_id');
            
        if (!session()->has('bulk_emails') && $request->isMethod('post')) {
            $allEmails = array_map(fn($e) => json_decode($e, true), $request->input('emails', []));
            session(['bulk_emails' => $allEmails]);
        } else {
            $allEmails = session('bulk_emails', []); // ✅ ensure it's always defined
        }
        $totalEmails = count($allEmails);
        $start = ($batchNumber - 1) * $batchSize;
        $emailsToSend = array_slice($allEmails, $start, $batchSize);

        // If no emails left
        if (empty($emailsToSend)) {
            session()->forget(['bulk_emails', 'campaign_id']); 
            // session()->forget('bulk_emails');
            return redirect()->route('emails.compose')->with('success', '✅ All emails sent!');
        }

        // ✅ ROTATE SMTP every batch
        $smtpConfigs = DB::table('email_configurations')->get();
        $smtpConfig = $smtpConfigs[($batchNumber - 1) % count($smtpConfigs)];

        $this->setMailConfig($smtpConfig);
        $mailer = Mail::mailer('smtp');


        foreach ($emailsToSend as $emailData) {

            try {
                $trackingId = uniqid();
                $trackingPixel = route('track.open', ['id' => $trackingId]) . '?t=' . time();
                // $trackingPixel = route('track.open', ['id' => $trackingId]);
                $trackedLink = route('track.click', [
                    'id' => $trackingId,
                    'url' => urlencode('https://nexonpackaging.com')
                ]);
                $mailer->send('emails.template', [ 
                        'name' => $emailData['name'],
                        'tracking_pixel' => $trackingPixel,
                        'tracked_link' => $trackedLink,
                        'senderName' => $smtpConfig->name,
                        'senderRole' => 'Customer Relations Manager',
                        'companyWebsite' => 'https://nexonpackaging.com',
                        'disclaimer' => "Disclaimer: This email and any attachments are intended solely for the recipient(s) and may contain confidential or privileged information. If you are not the intended recipient, please delete this email immediately and notify the sender. Any unauthorized use, disclosure, or distribution is prohibited. While we take precautions to ensure our emails are free from viruses or malware, we recommend you perform your own checks before opening attachments. We accept no liability for any loss or damage arising from this email. If you no longer wish to receive emails from us, please let us know."
                    ], function ($msg) use ($emailData, $smtpConfig) {
                    $msg->to($emailData['email'])
                        ->from($smtpConfig->mail_from_address, $smtpConfig->name)
                        ->replyTo('elena@nexonpackaging.com', 'Elena Herman')
                        ->subject('Best Pricing & Premium Packaging Guaranteed');
                });
                EmailLog::create([
                    'recipients' => $emailData['email'],
                    'compaign_id' => $campaignId,
                    'status' => 'sent',
                    'subject' => 'Best Pricing & Premium Packaging Guaranteed',
                    'tracking_id' => $trackingId,
                    'agent_name' => $smtpConfig->name,
                    'phone' => $emailData['phone']
                ]);
                usleep(5000000);
                Log::debug("Preparing to send to: {$emailData['email']} using {$smtpConfig->mail_from_address}");

                echo "✅ Sent to {$emailData['email']} using {$smtpConfig->mail_from_address}<br>";
            } catch (\Exception $e) {
                echo "❌ Failed to send to {$emailData['email']} - {$e->getMessage()}<br>";

            }
        }
        usleep(5000000);
        // Redirect to next batch
        return redirect()->route('send.emails', ['batch' => $batchNumber + 1]);
    }

    public function email_compaigns(){
        // $campaigns = EmailCampaign::orderBy('created_at', 'desc')->get();
        $campaigns = EmailCampaign::orderBy('id', 'desc')->get(); // Latest first
        $campaignData = [];

        foreach ($campaigns as $campaign) {
            // Only organic emails
            $totalEmails = Emaillog::where('compaign_id', $campaign->id)
                                   ->count();
    
            // Opened emails
            $openedEmails = Emaillog::where('compaign_id', $campaign->id)
                                    ->where('status', 'opened')
                                    ->count();
    
            // Clicked emails
            $clickedEmails = Emaillog::where('compaign_id', $campaign->id)
                                        ->whereNotNull('opened_at')
                                     ->count();
    
            // CTR Calculations
            $openedCtr = ($totalEmails > 0) ? round(($openedEmails / $totalEmails) * 100, 2) : 0;
            $clickedCtr = ($totalEmails > 0) ? round(($clickedEmails / $totalEmails) * 100, 2) : 0;
    
            $campaignData[] = [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'subject' => $campaign->subject,
                'sent_at' => $campaign->sent_at,
                'total_emails' => $totalEmails,
                'opened_emails' => $openedEmails,
                'clicked_emails' => $clickedEmails,
                'opened_ctr' => $openedCtr,
                'clicked_ctr' => $clickedCtr,
            ];
        }
        return view('compaigns', compact('campaignData'));
    }
    public function campaigns_view($campaignId){
        // $campaignId = $request->campaignId;
        // print_r($campaignId);exit();
        $emailLogs = EmailLog::where('compaign_id', $campaignId)
        ->select('id', 'recipients', 'status', 'opened_times', 'agent_name', 'opened_at' ,'phone')
        ->orderByRaw('opened_at IS NOT NULL DESC') // Opened emails first
        ->orderByDesc('opened_times')              // Then by opened_times high to low
        ->get();

        return view('campaignlogs', compact('emailLogs'));
    }
   
   




}

