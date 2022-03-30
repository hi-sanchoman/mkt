<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mail;
use Swift_Mailer;
use Swift_SmtpTransport;
use App\Mail\ClientMail;
use Illuminate\Mail\Mailer;
use Inertia\Inertia;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Client;

class MailController extends Controller
{
    public function index(String $driver = 'mail', String $selected_folder = 'all', int $page = 1) {

        return Inertia::render('Mail/Index', $this->getMails($driver,$selected_folder, $page));
    }

    public function axiosIndex(String $driver, String $selected_folder = 'all', int $page = 1) {
       
        return $this->getMails($driver,$selected_folder, $page);
    }

    private function getMails($driver, String $selected_folder = 'all', int $page = 1)
    {
        $client = $this->connect($driver);
        //Get all Mailboxes
        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders();
        
        $mails = $this->prepareMails($selected_folder, $folders);
        
        $itemsOnPage = 2;
        $pages = ceil(count($mails) / $itemsOnPage);
        $currentPos = ($page == 1) ? 0 : ($page - 1) * $itemsOnPage;
        $mails = array_slice($mails, $currentPos, $itemsOnPage);

        return [
            'mails' => $mails,
            'folders' => $folders,
            'pages' => $pages,
            'currentPage' => $page,
            'currentFolder' => $selected_folder,
            'selectedDriver' => $driver,
        ];
    }

    public function send_mail(Request $request)
    {
        dd('');
        $data = [
            'title' => $request->data['subject'],
            'body' => $request->data['text']
        ];
        
        $config = [
            'smtp_host'    => 'smtp.mail.ru',
            'smtp_port'    => '465',
            'smtp_username'  => 'aytimovaz@mail.ru',
          
            'smtp_password'  => 'Sakura2000',
            'smtp_encryption'  => 'ssl',
            'from_email'    => 'aytimovaz@mail.ru',
            'from_name'    => 'Test User',
        ];
           
        $from = [
            'address' => $config['from_email'],
            'name' => $config['from_name']
        ];

        $subject = $request->data['subject'];
        $template = 'emails.client_mail';

        $transport = (new Swift_SmtpTransport($config['smtp_host'], $config['smtp_port']))
            ->setEncryption($config['smtp_encryption'])
            ->setUsername($config['smtp_username'])
            ->setPassword($config['smtp_password']);

        $mailer = app(Mailer::class);
        $mailer->setSwiftMailer(new Swift_Mailer($transport));
        $mailer->to($request->data['to'])->send(new ClientMail($template, $subject, $data, $from));

        // Mail::to($request->email)->send(new \App\Mail\MyTestMail($details));
        
        dd("Email is Sent.");
    }

    public function prepareMails(String $selected_folder, $folders) {
        
        $mails = [];
     
        if($selected_folder != 'all') {
            $folders = $folders->where('name', $selected_folder);
        }
        //Loop through every Mailbox
        /** @var \Webklex\PHPIMAP\Folder $folder */
        foreach($folders as $folder){
            //Get all Messages of the current Mailbox $folder
            /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
            $messages = $folder->messages()->all()->get();
            
            
            /** @var \Webklex\PHPIMAP\Message $message */
            foreach($messages as $message){
                
                $attrs = $message->getHeader()->getAttributes();

                array_push($mails, [
                    'title' => imap_utf8($message->getSubject()->first()),
                    'attachments' => $message->getAttachments()->count(),
                    'body' => $message->getHTMLBody(),
                    'flags' => $message->getFlags(),
                    'date' => $attrs['date']->toString(),
                    'from' => $attrs['from']->toString(),
                    'to' => $attrs['to']->toString(),
                    'text' => $message->getTextBody(),
                ]);
                //Move the current Message to 'INBOX.read'
                // if($message->move('INBOX.read') == true){
                //     echo 'Message has ben moved';
                // }else{
                //     echo 'Message could not be moved';
                // } 
            }

            
        }

        rsort($mails);
        return $mails;
    }

    private function connect(String $driver)
    {
    
        // IMAP_HOST=imap.mail.ru
        // IMAP_PORT=993
        // IMAP_ENCRYPTION=ssl
        // IMAP_VALIDATE_CERT=false
        // IMAP_USERNAME=oazis.test@mail.ru
        // IMAP_PASSWORD=tar123A+
        // IMAP_DEFAULT_ACCOUNT=default
        // IMAP_PROTOCOL=imap

        if($driver == 'mail') {
            $host = 'imap.mail.ru';
            $port = 993;
            $username = 'aytimovaz@mail.ru';
            $password = 'Sakura2000';
            $encryption = 'ssl';
        }

        if($driver == 'gmail') {
            $host = 'imap.gmail.com';
            $port = 993;
            $username = 'abik60000@gmail.com';
            $password = 'abikinYoutube21c2';
            $encryption = 'ssl';
        }

        if($driver == 'yandex') {
            $host = 'imap.yandex.com';
            $port = 993;
            $username = 'test@yandex.com';
            $password = 'tar123A+';
            $encryption = 'ssl';
        }
        

        try {
            $cm = new ClientManager($options = []);
            $client = $cm->account('default'); // Вот здесь будут проблемы. Другие значения не принимало. Нужно выяснить
            $client = $cm->make([
                'host'          => $host,
                'port'          => $port,
                'encryption'    => $encryption,
                'validate_cert' => true,
                'protocol'      => 'imap',
                'username'      => $username,
                'password'      => $password
            ]);

            $client->connect();

            return $client;
        } catch (\Exception $e) {
            dd($e);
        }

        
    }

   
}
