<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Status Changed</title>
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
            background-color: #3b82f6;
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
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
        }
        .ticket-info p {
            margin: 8px 0;
        }
        .ticket-info strong {
            color: #1f2937;
        }
        .status-change {
            background-color: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        .status-old {
            background-color: #e5e7eb;
            color: #374151;
        }
        .status-new {
            background-color: #10b981;
            color: #ffffff;
        }
        .arrow {
            margin: 0 10px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3b82f6;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #2563eb;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 Ticket Status Updated</h1>
        </div>

        <div class="content">
            <h2>Hello <?php echo e($ticket->user->name); ?>,</h2>

            <p>Your support ticket status has been updated.</p>

            <div class="ticket-info">
                <p><strong>Ticket ID:</strong> #<?php echo e($ticket->id); ?></p>
                <p><strong>Title:</strong> <?php echo e($ticket->title); ?></p>
                <p><strong>Category:</strong> <?php echo e($ticket->category->name); ?></p>
                <p><strong>Priority:</strong> <?php echo e(ucfirst($ticket->priority)); ?></p>
                <?php if($ticket->assignedTo): ?>
                <p><strong>Assigned to:</strong> <?php echo e($ticket->assignedTo->name); ?></p>
                <?php endif; ?>
            </div>

            <div class="status-change">
                <p style="margin-bottom: 10px; color: #374151;"><strong>Status Change:</strong></p>
                <span class="status-badge status-old"><?php echo e($oldStatus); ?></span>
                <span class="arrow">→</span>
                <span class="status-badge status-new"><?php echo e($newStatus); ?></span>
            </div>

            <div class="divider"></div>

            <p><strong>Description:</strong></p>
            <p style="color: #6b7280;"><?php echo e(Str::limit($ticket->description, 200)); ?></p>

            <div style="text-align: center;">
                <a href="<?php echo e(route('tickets.show', $ticket->id)); ?>" class="button">
                    View Ticket Details
                </a>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6b7280;">
                If you have any questions or concerns, please reply to this email or contact our support team.
            </p>
        </div>

        <div class="footer">
            <p>This is an automated message from the IT Support Ticket System.</p>
            <p>&copy; <?php echo e(date('Y')); ?> IT Support Ticket System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\BARIŞ\Desktop\OOP\it_support_ticket_system\resources\views/emails/ticket-status-changed.blade.php ENDPATH**/ ?>