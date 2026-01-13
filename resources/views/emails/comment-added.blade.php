<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Comment Added</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #10b981;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .content h2 {
            color: #1f2937;
            font-size: 20px;
            margin-top: 0;
        }
        .ticket-info {
            background-color: #f9fafb;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
        }
        .ticket-info p {
            margin: 8px 0;
        }
        .ticket-info strong {
            color: #1f2937;
        }
        .comment-box {
            background-color: #ecfdf5;
            border: 1px solid #10b981;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #d1fae5;
        }
        .author-avatar {
            width: 40px;
            height: 40px;
            background-color: #10b981;
            color: #ffffff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            margin-right: 12px;
        }
        .author-info {
            flex: 1;
        }
        .author-name {
            font-weight: bold;
            color: #1f2937;
            margin: 0;
        }
        .comment-date {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }
        .comment-text {
            color: #374151;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #10b981;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #059669;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-support {
            background-color: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 New Comment on Your Ticket</h1>
        </div>

        <div class="content">
            <h2>Hello {{ $ticket->user->name }},</h2>

            <p>A new comment has been added to your support ticket by
                <strong>{{ $author->name }}</strong>
                @if($author->isSupport())
                <span class="badge badge-support">Support Team</span>
                @endif
            </p>

            <div class="ticket-info">
                <p><strong>Ticket ID:</strong> #{{ $ticket->id }}</p>
                <p><strong>Title:</strong> {{ $ticket->title }}</p>
                <p><strong>Category:</strong> {{ $ticket->category->name }}</p>
                <p><strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $ticket->status)) }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
            </div>

            <div class="comment-box">
                <div class="comment-header">
                    <div class="author-avatar">
                        {{ strtoupper(substr($author->name, 0, 1)) }}
                    </div>
                    <div class="author-info">
                        <p class="author-name">{{ $author->name }}</p>
                        <p class="comment-date">{{ $comment->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
                <div class="comment-text">
                    {{ $comment->comment }}
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('tickets.show', $ticket->id) }}" class="button">
                    View Ticket & Reply
                </a>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6b7280;">
                You can reply to this comment by viewing the ticket and adding your response.
                If you have any questions, our support team is here to help.
            </p>
        </div>

        <div class="footer">
            <p>This is an automated message from the IT Support Ticket System.</p>
            <p>You received this email because you are the owner of ticket #{{ $ticket->id }}.</p>
            <p>&copy; {{ date('Y') }} IT Support Ticket System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
