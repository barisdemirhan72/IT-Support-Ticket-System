<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Users
        $this->command->info('Creating users...');

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $support1 = User::create([
            'name' => 'John Support',
            'email' => 'support1@example.com',
            'password' => Hash::make('password'),
            'role' => 'support',
            'email_verified_at' => now(),
        ]);

        $support2 = User::create([
            'name' => 'Jane Support',
            'email' => 'support2@example.com',
            'password' => Hash::make('password'),
            'role' => 'support',
            'email_verified_at' => now(),
        ]);

        $user1 = User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $user3 = User::create([
            'name' => 'Charlie Brown',
            'email' => 'charlie@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Users created successfully!');

        // Create Categories
        $this->command->info('Creating categories...');

        $hardware = Category::create([
            'name' => 'Hardware',
            'description' => 'Hardware related issues including computers, printers, monitors, and other physical equipment',
            'is_active' => true,
        ]);

        $software = Category::create([
            'name' => 'Software',
            'description' => 'Software and application issues, installation problems, and license management',
            'is_active' => true,
        ]);

        $network = Category::create([
            'name' => 'Network',
            'description' => 'Network connectivity issues, Wi-Fi problems, VPN access, and internet connection',
            'is_active' => true,
        ]);

        $access = Category::create([
            'name' => 'Access',
            'description' => 'Access rights, permissions, password resets, and account management',
            'is_active' => true,
        ]);

        $email = Category::create([
            'name' => 'Email',
            'description' => 'Email client issues, configuration, spam, and mailbox problems',
            'is_active' => true,
        ]);

        $other = Category::create([
            'name' => 'Other',
            'description' => 'Other IT related issues that do not fit into specific categories',
            'is_active' => true,
        ]);

        $this->command->info('Categories created successfully!');

        // Create Tickets
        $this->command->info('Creating tickets...');

        // Ticket 1 - New ticket from user1
        $ticket1 = Ticket::create([
            'title' => 'Laptop screen flickering',
            'description' => 'My laptop screen has been flickering intermittently for the past two days. It happens mostly when I open multiple applications. The flickering is more noticeable when using graphic-intensive programs.',
            'status' => 'new',
            'priority' => 'high',
            'user_id' => $user1->id,
            'category_id' => $hardware->id,
            'assigned_to' => null,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        // Ticket 2 - In progress ticket
        $ticket2 = Ticket::create([
            'title' => 'Unable to access company VPN',
            'description' => 'I am unable to connect to the company VPN from home. I receive an error message "Authentication failed" even though I am using the correct credentials. This started happening after the recent password change.',
            'status' => 'in_progress',
            'priority' => 'urgent',
            'user_id' => $user2->id,
            'category_id' => $network->id,
            'assigned_to' => $support1->id,
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subHours(2),
        ]);

        // Ticket 3 - Completed ticket
        $ticket3 = Ticket::create([
            'title' => 'Request for Microsoft Office installation',
            'description' => 'I need Microsoft Office installed on my new workstation. I require Word, Excel, PowerPoint, and Outlook for my daily tasks.',
            'status' => 'completed',
            'priority' => 'medium',
            'user_id' => $user3->id,
            'category_id' => $software->id,
            'assigned_to' => $support2->id,
            'resolved_at' => now()->subHours(5),
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subHours(5),
        ]);

        // Ticket 4 - New ticket
        $ticket4 = Ticket::create([
            'title' => 'Printer not responding',
            'description' => 'The printer on the 3rd floor (Printer-03) is not responding to print jobs. The printer shows as online in the system but documents sent to it are stuck in the queue.',
            'status' => 'new',
            'priority' => 'medium',
            'user_id' => $user1->id,
            'category_id' => $hardware->id,
            'assigned_to' => null,
            'created_at' => now()->subHours(4),
            'updated_at' => now()->subHours(4),
        ]);

        // Ticket 5 - In progress
        $ticket5 = Ticket::create([
            'title' => 'Email client not syncing',
            'description' => 'My Outlook email client is not syncing with the server. I can send emails but not receive new ones. The last email I received was from yesterday morning.',
            'status' => 'in_progress',
            'priority' => 'high',
            'user_id' => $user2->id,
            'category_id' => $email->id,
            'assigned_to' => $support1->id,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subHours(1),
        ]);

        // Ticket 6 - New urgent ticket
        $ticket6 = Ticket::create([
            'title' => 'Cannot access shared network drive',
            'description' => 'I cannot access the shared network drive (\\\\server\\shared). I receive an "Access Denied" error. I need access urgently as all my project files are stored there.',
            'status' => 'new',
            'priority' => 'urgent',
            'user_id' => $user3->id,
            'category_id' => $access->id,
            'assigned_to' => null,
            'created_at' => now()->subHours(1),
            'updated_at' => now()->subHours(1),
        ]);

        // Ticket 7 - Completed
        $ticket7 = Ticket::create([
            'title' => 'Password reset request',
            'description' => 'I forgot my password and need it to be reset. I have verified my identity through the security questions.',
            'status' => 'completed',
            'priority' => 'high',
            'user_id' => $user1->id,
            'category_id' => $access->id,
            'assigned_to' => $support2->id,
            'resolved_at' => now()->subDays(1),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(1),
        ]);

        // Ticket 8 - Low priority
        $ticket8 = Ticket::create([
            'title' => 'Request for software license information',
            'description' => 'Can you provide information about our Adobe Creative Cloud license? I need to know how many seats are available and how to request access.',
            'status' => 'new',
            'priority' => 'low',
            'user_id' => $user2->id,
            'category_id' => $software->id,
            'assigned_to' => null,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        // Ticket 9 - Closed
        $ticket9 = Ticket::create([
            'title' => 'Mouse not working properly',
            'description' => 'My wireless mouse is not working properly. The cursor moves erratically and sometimes does not respond at all. I have already tried changing the batteries.',
            'status' => 'closed',
            'priority' => 'low',
            'user_id' => $user3->id,
            'category_id' => $hardware->id,
            'assigned_to' => $support1->id,
            'resolved_at' => now()->subDays(3),
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(3),
        ]);

        // Ticket 10 - In progress
        $ticket10 = Ticket::create([
            'title' => 'Slow computer performance',
            'description' => 'My computer has been running very slowly for the past week. Applications take a long time to open, and the system frequently freezes. I have already tried restarting it multiple times.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'user_id' => $user1->id,
            'category_id' => $hardware->id,
            'assigned_to' => $support2->id,
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subHours(3),
        ]);

        $this->command->info('Tickets created successfully!');

        // Create Comments
        $this->command->info('Creating comments...');

        // Comments for ticket 2 (VPN issue)
        Comment::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $support1->id,
            'comment' => 'Thank you for reporting this issue. I am looking into the VPN authentication problem. Can you please confirm which VPN client version you are using?',
            'is_internal' => false,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        Comment::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $user2->id,
            'comment' => 'I am using Cisco AnyConnect version 4.9. The issue persists even after reinstalling the client.',
            'is_internal' => false,
            'created_at' => now()->subDays(2)->addHours(2),
            'updated_at' => now()->subDays(2)->addHours(2),
        ]);

        Comment::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $support1->id,
            'comment' => 'Internal note: Need to check if user account was properly synced with VPN server after password change. May need to reset VPN credentials.',
            'is_internal' => true,
            'created_at' => now()->subDays(2)->addHours(3),
            'updated_at' => now()->subDays(2)->addHours(3),
        ]);

        Comment::create([
            'ticket_id' => $ticket2->id,
            'user_id' => $support1->id,
            'comment' => 'I have reset your VPN credentials on our end. Please try to reconnect using your current Windows password. Let me know if you still experience issues.',
            'is_internal' => false,
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        // Comments for ticket 3 (Office installation - completed)
        Comment::create([
            'ticket_id' => $ticket3->id,
            'user_id' => $support2->id,
            'comment' => 'I have scheduled the Microsoft Office installation for your workstation. I will perform the installation remotely this afternoon.',
            'is_internal' => false,
            'created_at' => now()->subDays(5)->addHours(3),
            'updated_at' => now()->subDays(5)->addHours(3),
        ]);

        Comment::create([
            'ticket_id' => $ticket3->id,
            'user_id' => $support2->id,
            'comment' => 'Microsoft Office 365 has been successfully installed on your workstation. All applications (Word, Excel, PowerPoint, and Outlook) are configured and ready to use. Please restart your computer and verify that everything is working correctly.',
            'is_internal' => false,
            'created_at' => now()->subHours(5),
            'updated_at' => now()->subHours(5),
        ]);

        Comment::create([
            'ticket_id' => $ticket3->id,
            'user_id' => $user3->id,
            'comment' => 'Thank you! Everything is working perfectly. I appreciate your quick response.',
            'is_internal' => false,
            'created_at' => now()->subHours(4),
            'updated_at' => now()->subHours(4),
        ]);

        // Comments for ticket 5 (Email sync issue)
        Comment::create([
            'ticket_id' => $ticket5->id,
            'user_id' => $support1->id,
            'comment' => 'I am investigating your email synchronization issue. Have you made any recent changes to your email settings or password?',
            'is_internal' => false,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        Comment::create([
            'ticket_id' => $ticket5->id,
            'user_id' => $user2->id,
            'comment' => 'No, I have not made any changes. The issue started suddenly yesterday morning.',
            'is_internal' => false,
            'created_at' => now()->subDays(1)->addHours(1),
            'updated_at' => now()->subDays(1)->addHours(1),
        ]);

        Comment::create([
            'ticket_id' => $ticket5->id,
            'user_id' => $support1->id,
            'comment' => 'Internal: Server logs show connection issues from this user. Mailbox may have exceeded quota. Checking storage limits.',
            'is_internal' => true,
            'created_at' => now()->subHours(1),
            'updated_at' => now()->subHours(1),
        ]);

        // Comments for ticket 7 (Password reset - completed)
        Comment::create([
            'ticket_id' => $ticket7->id,
            'user_id' => $support2->id,
            'comment' => 'Your password has been reset. You should receive an email with temporary credentials shortly. Please change your password after logging in.',
            'is_internal' => false,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        // Comments for ticket 10 (Slow performance)
        Comment::create([
            'ticket_id' => $ticket10->id,
            'user_id' => $support2->id,
            'comment' => 'I will run a remote diagnostic on your computer to identify the cause of the slow performance. This may take some time. Please leave your computer on.',
            'is_internal' => false,
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ]);

        Comment::create([
            'ticket_id' => $ticket10->id,
            'user_id' => $support2->id,
            'comment' => 'Internal: Diagnostics show high disk usage. Hard drive is 95% full. Also found several startup programs consuming resources. Will clean up and optimize.',
            'is_internal' => true,
            'created_at' => now()->subHours(3),
            'updated_at' => now()->subHours(3),
        ]);

        Comment::create([
            'ticket_id' => $ticket10->id,
            'user_id' => $support2->id,
            'comment' => 'I have identified the issue. Your hard drive is almost full, and there are several unnecessary programs running at startup. I will clean up temporary files and disable unused startup programs. This should significantly improve performance.',
            'is_internal' => false,
            'created_at' => now()->subHours(3)->addMinutes(30),
            'updated_at' => now()->subHours(3)->addMinutes(30),
        ]);

        $this->command->info('Comments created successfully!');

        $this->command->info('');
        $this->command->info('==============================================');
        $this->command->info('Database seeding completed successfully!');
        $this->command->info('==============================================');
        $this->command->info('');
        $this->command->info('Test Users Created:');
        $this->command->info('-------------------------------------------');
        $this->command->info('Admin:    admin@example.com / password');
        $this->command->info('Support:  support1@example.com / password');
        $this->command->info('Support:  support2@example.com / password');
        $this->command->info('User:     alice@example.com / password');
        $this->command->info('User:     bob@example.com / password');
        $this->command->info('User:     charlie@example.com / password');
        $this->command->info('-------------------------------------------');
        $this->command->info('');
        $this->command->info('Categories: 6 created');
        $this->command->info('Tickets: 10 created');
        $this->command->info('Comments: 14 created');
        $this->command->info('');
    }
}
